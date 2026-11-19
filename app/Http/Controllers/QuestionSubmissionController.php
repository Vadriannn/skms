<?php

namespace App\Http\Controllers;

use App\Models\QuestionSubmission;
use App\Services\TfIdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Cari kandidat FAQ yang mirip menggunakan TF-IDF untuk membantu Data Entry meninjau tiket
        $similarity = $tfIdf->checkSimilarity($questionSubmission->pertanyaan, 0.20);
        $similarCandidates = collect($similarity['candidates'] ?? [])->take(5);

        return view('questions.show', [
            'ticket'            => $questionSubmission,
            'similarCandidates' => $similarCandidates,
            'similarity'        => $similarity,
        ]);
    }
}
