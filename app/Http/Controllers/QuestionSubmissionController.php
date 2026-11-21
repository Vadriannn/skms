<?php

namespace App\Http\Controllers;

use App\Mail\FaqAnsweredMail;
use App\Models\ActivityLog;
use App\Models\Faq;
use App\Models\QuestionSubmission;
use App\Services\TfIdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class QuestionSubmissionController extends Controller
{
    /**
     * Pastikan hanya Data Entry dan Super Admin yang dapat mengakses modul antrean tiket mahasiswa.
     */
    private function ensureAuthorized(): void
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['data_entry', 'super_admin'], true)) {
            abort(403, 'Akses ditolak. Halaman ini khusus untuk Data Entry dan Super Admin.');
        }
    }

    /**
     * Daftar Antrean Tiket Pengajuan Pertanyaan Mahasiswa (SB-027).
     */
    public function index(Request $request)
    {
        $this->ensureAuthorized();

        $status  = $request->query('status', 'all');
        $search  = trim((string) $request->query('search', ''));
        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        // Hitung jumlah tiket untuk tab navigasi
        $countAll      = QuestionSubmission::count();
        $countPending  = QuestionSubmission::where('status', 'pending')->count();
        $countAnswered = QuestionSubmission::where('status', 'answered')->count();

        $query = QuestionSubmission::with(['faq', 'user']);

        if (in_array($status, ['pending', 'answered'], true)) {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('pertanyaan', 'like', '%' . $search . '%')
                  ->orWhere('nrp', 'like', '%' . $search . '%')
                  ->orWhere('note', 'like', '%' . $search . '%');
            });
        }

        // Prioritaskan tiket yang masih pending di urutan atas, lalu urutkan terbaru
        $tickets = $query
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('questions.index', compact(
            'tickets',
            'status',
            'search',
            'perPage',
            'countAll',
            'countPending',
            'countAnswered'
        ));
    }

    /**
     * Halaman Detail Tiket Pengajuan Pertanyaan Mahasiswa beserta Analisis Kemiripan TF-IDF.
     */
    public function show(QuestionSubmission $questionSubmission, TfIdfService $tfIdf)
    {
        $this->ensureAuthorized();

        $questionSubmission->load(['faq.categories', 'user']);

        // Cari kandidat FAQ yang mirip menggunakan TF-IDF HANYA dari FAQ yang sudah berstatus 'published' (resmi terbit)
        $similarity = $tfIdf->checkSimilarity($questionSubmission->pertanyaan, 0.20, null, 'published');
        $similarCandidates = collect($similarity['candidates'] ?? [])->take(5);

        // Ambil daftar seluruh FAQ yang sudah terbit (published) untuk pilihan penautan manual
        $publishedFaqs = Faq::with('categories')
            ->where('status', 'published')
            ->orderBy('pertanyaan')
            ->get();

        return view('questions.show', [
            'ticket'            => $questionSubmission,
            'similarCandidates' => $similarCandidates,
            'similarity'        => $similarity,
            'publishedFaqs'     => $publishedFaqs,
        ]);
    }

    /**
     * Endpoint AJAX untuk mencari & memuat daftar FAQ lama (published) beserta jawaban lengkapnya (SB-028).
     */
    public function searchFaqs(Request $request)
    {
        $this->ensureAuthorized();

        $q = trim((string) $request->query('q', ''));

        $query = Faq::with('categories')
            ->where('status', 'published');

        if ($q !== '') {
            $cleanId = ltrim($q, '#');
            $query->where(function ($sub) use ($q, $cleanId) {
                $sub->where('pertanyaan', 'like', '%' . $q . '%')
                    ->orWhere('jawaban', 'like', '%' . $q . '%')
                    ->orWhereHas('categories', function ($cq) use ($q) {
                        $cq->where('nama', 'like', '%' . $q . '%');
                    });

                if (ctype_digit($cleanId)) {
                    $sub->orWhere('id', (int) $cleanId);
                }
            });
        }

        $faqs = $query->orderByDesc('views')->latest()->take(30)->get()->map(function ($faq) {
            return [
                'id'         => $faq->id,
                'pertanyaan' => $faq->pertanyaan,
                'jawaban'    => $faq->jawaban,
                'categories' => $faq->categories->pluck('nama')->values()->all(),
                'views'      => (int) $faq->views,
            ];
        });

        return response()->json([
            'success' => true,
            'count'   => $faqs->count(),
            'faqs'    => $faqs,
        ]);
    }

    /**
     * Alur Kerja Penanganan Tiket 1 (SB-028): Tautkan Tiket ke FAQ Lama yang Sudah Ada.
     */
    public function linkFaq(Request $request, QuestionSubmission $questionSubmission)
    {
        $this->ensureAuthorized();

        $validated = $request->validate([
            'faq_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('faqs', 'id')->where(function ($query) {
                    $query->where('status', 'published');
                }),
            ],
        ], [
            'faq_id.required' => 'Silakan pilih FAQ yang ingin ditautkan ke tiket ini.',
            'faq_id.exists'   => 'FAQ yang dipilih tidak valid atau belum berstatus Published (resmi terbit).',
        ]);

        $faq = Faq::findOrFail($validated['faq_id']);

        $questionSubmission->update([
            'faq_id'  => $faq->id,
            'user_id' => Auth::id(),
            'note'    => null,
            'status'  => 'answered',
        ]);

        ActivityLog::log(
            Auth::user()->name . " menyelesaikan Tiket Pertanyaan #{$questionSubmission->id}" . ($questionSubmission->nrp ? " (NRP {$questionSubmission->nrp})" : "") . " dengan menautkan ke FAQ #{$faq->id}: \"{$faq->pertanyaan}\"",
            'faq'
        );

        // SKENARIO 2: Pemicu pengiriman email otomatis ke email kampus mahasiswa jika pengaju mencantumkan NRP
        if (!empty($questionSubmission->nrp)) {
            $recipientEmail = $questionSubmission->student_email;
            try {
                Mail::to($recipientEmail)->send(new FaqAnsweredMail($questionSubmission, $faq, 'existing_faq'));
                Log::info("Email jawaban FAQ tertaut berhasil dikirim ke {$recipientEmail} untuk Tiket #{$questionSubmission->id}");
            } catch (\Throwable $e) {
                Log::error("Gagal mengirim email jawaban FAQ ke {$recipientEmail} untuk Tiket #{$questionSubmission->id}: " . $e->getMessage());
            }
        }

        return redirect()
            ->route('questions.show', $questionSubmission->id)
            ->with('success', 'Tiket #' . $questionSubmission->id . ' berhasil ditautkan ke FAQ #' . $faq->id . ' dan ditandai sebagai Sudah Dijawab!');
    }
}
