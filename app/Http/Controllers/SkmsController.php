<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Category;
use App\Models\Search;
use App\Models\FaqHelpfulVote;
use App\Models\QuestionSubmission;
use App\Services\TfIdfService;
use Illuminate\Http\Request;

class SkmsController extends Controller
{
    /**
     * Tampilan form masuk/identifikasi Mahasiswa UBAYA (hanya NRP).
     */
    public function showLoginForm(Request $request)
    {
        if (session()->has('student_nrp')) {
            if ($request->query('redirect') === 'tanya') {
                return redirect()->route('skms.tanya', array_filter(['q' => $request->query('q')]));
            }
            return redirect()->route('skms.beranda');
        }

        return view('skms.masuk', [
            'redirect' => $request->query('redirect'),
            'prefillQ' => $request->query('q'),
        ]);
    }

    /**
     * Proses validasi NRP Mahasiswa (wajib 9 digit angka).
     */
    public function processLogin(Request $request)
    {
        $nrp = trim((string) $request->input('nrp', ''));

        // Validasi format NRP UBAYA (wajib tepat 9 digit angka)
        if (!preg_match('/^[0-9]{9}$/', $nrp)) {
            return back()->withInput()->with('error', 'NRP tidak valid. NRP mahasiswa UBAYA harus terdiri dari 9 digit angka (contoh: 160421001).');
        }

        // Simpan data mahasiswa dalam session
        session([
            'student_nrp' => $nrp,
        ]);

        if ($request->input('redirect') === 'tanya') {
            return redirect()
                ->route('skms.tanya', array_filter(['q' => $request->input('q')]))
                ->with('success', 'Identitas NRP ' . $nrp . ' terverifikasi. Silakan tuliskan pertanyaan Anda kepada staf.');
        }

        return redirect()->route('skms.beranda')->with('success', 'Selamat datang di SKMS UBAYA (NRP: ' . $nrp . ')!');
    }

    /**
     * Keluar dari sesi mahasiswa.
     */
    public function logoutStudent()
    {
        session()->forget(['student_name', 'student_nrp', 'helpful_faqs', 'skms_last_activity']);
        session()->regenerate();
        return redirect()->route('skms.beranda')->with('info', 'Anda telah keluar dari sesi identitas mahasiswa.');
    }

    /**
     * Halaman Beranda Publik SKMS.
     * Kondisi Awal: Hanya menampilkan header, tulisan SKMS UBAYA, search bar, dan tombol login NRP.
     * Setelah Pencarian: Menampilkan hasil FAQ (TF-IDF), filter kategori combobox, dan Top FAQ di sebelah kanan.
     */
    public function index(Request $request, TfIdfService $tfIdf)
    {
        $studentNrp = session('student_nrp', null);
        $keyword    = trim((string) $request->input('search', ''));

        // Ambil filter kategori jika ada
        $selectedCategories = $request->input('categories', []);
        if (empty($selectedCategories) && $request->filled('category_id')) {
            $selectedCategories = [$request->input('category_id')];
        }
        if (!is_array($selectedCategories)) {
            $selectedCategories = $selectedCategories ? [$selectedCategories] : [];
        }
        $selectedCategories = array_map('intval', array_filter($selectedCategories));

        $results  = collect();
        $searched = false;
        $showAll  = $request->boolean('all');

        // Ambil semua kategori untuk kebutuhan filter
        $categories = Category::withCount(['faqs' => function ($q) {
            $q->where('status', 'published');
        }])->orderBy('nama')->get();

        // Ambil Top FAQ berdasarkan view terbanyak (digunakan di Beranda & panel kanan Pencarian)
        $topFaqs = Faq::with('categories')
            ->where('status', 'published')
            ->orderByDesc('views')
            ->take(5)
            ->get();

        // Mode jika user melakukan pencarian, memfilter kategori, atau memilih "Lihat semua FAQ"
        if ($keyword !== '' || !empty($selectedCategories) || $showAll) {
            $searched = true;

            if ($keyword !== '') {
                // Pencarian menggunakan TF-IDF
                $similarity = $tfIdf->checkSimilarity($keyword, 0.01);

                if (!empty($similarity['candidates'])) {
                    $faqIds = collect($similarity['candidates'])->pluck('faq.id')->toArray();
                    $scores = collect($similarity['candidates'])->pluck('score', 'faq.id')->toArray();

                    $query = Faq::with('categories')
                        ->where('status', 'published')
                        ->whereIn('id', $faqIds);

                    if (!empty($selectedCategories)) {
                        $query->whereHas('categories', function ($q) use ($selectedCategories) {
                            $q->whereIn('categories.id', $selectedCategories);
                        });
                    }

                    $faqs = $query->get();

                    $results = $faqs->sortByDesc(function ($faq) use ($scores) {
                        return $scores[$faq->id] ?? 0;
                    })->values()->map(function ($faq) use ($scores) {
                        $faq->relevance_score = $scores[$faq->id] ?? 0;
                        return $faq;
                    });
                } else {
                    // Fallback pencarian teks jika TF-IDF tidak menghasilkan kandidat
                    $query = Faq::with('categories')
                        ->where('status', 'published')
                        ->where(function ($q) use ($keyword) {
                            $q->where('pertanyaan', 'LIKE', '%' . $keyword . '%')
                              ->orWhere('jawaban', 'LIKE', '%' . $keyword . '%');
                        });

                    if (!empty($selectedCategories)) {
                        $query->whereHas('categories', function ($q) use ($selectedCategories) {
                            $q->whereIn('categories.id', $selectedCategories);
                        });
                    }

                    $results = $query->orderByDesc('views')->get();
                }

                // Catat riwayat pencarian mahasiswa
                try {
                    Search::create([
                        'nrp'        => $studentNrp ?: null,
                        'kata_kunci' => $keyword,
                        'faq_id'     => $results->first()?->id ?? null,
                    ]);
                } catch (\Throwable $e) {
                    // Abaikan kesalahan pencatatan
                }
            } else {
                // Hanya filter kategori tanpa kata kunci pencarian
                $query = Faq::with('categories')
                    ->where('status', 'published');

                if (!empty($selectedCategories)) {
                    $query->whereHas('categories', function ($q) use ($selectedCategories) {
                        $q->whereIn('categories.id', $selectedCategories);
                    });
                }

                $results = $query->orderByDesc('views')->get();
            }
        }

        // Ambil daftar FAQ yang sudah ditandai membantu oleh pengguna ini
        $voterHash = $studentNrp 
            ? hash('sha256', 'nrp_' . $studentNrp)
            : hash('sha256', 'session_' . session()->getId());

        $helpfulVotes = FaqHelpfulVote::where('voter_hash', $voterHash)->pluck('faq_id')->toArray();
        $sessionHelpful = session('helpful_faqs', []);
        $helpfulFaqs = array_values(array_unique(array_merge($helpfulVotes, $sessionHelpful)));

        return view('skms.beranda', compact(
            'results',
            'topFaqs',
            'categories',
            'keyword',
            'selectedCategories',
            'searched',
            'studentNrp',
            'helpfulFaqs'
        ));
    }

    /**
     * Halaman Detail Jawaban FAQ Mahasiswa (Image 3).
     */
    public function show(Request $request, Faq $faq)
    {
        if ($faq->status !== 'published') {
            abort(404);
        }

        $studentNrp = session('student_nrp', null);

        // Tambah jumlah view (cegah duplikasi berulang pada sesi yang sama)
        $viewedFaqs = session('viewed_faqs', []);
        if (!in_array($faq->id, $viewedFaqs)) {
            $faq->increment('views');
            $faq->refresh();
            $viewedFaqs[] = $faq->id;
            session(['viewed_faqs' => $viewedFaqs]);
        }

        $faq->load('categories');
        $categoryIds = $faq->categories->pluck('id')->toArray();

        // Ambil FAQ terkait (kategori sama atau populer)
        $relatedFaqs = Faq::with('categories')
            ->where('status', 'published')
            ->where('id', '!=', $faq->id)
            ->when(!empty($categoryIds), function ($q) use ($categoryIds) {
                $q->whereHas('categories', function ($cq) use ($categoryIds) {
                    $cq->whereIn('categories.id', $categoryIds);
                });
            })
            ->orderByDesc('views')
            ->take(4)
            ->get();

        if ($relatedFaqs->count() < 3) {
            $excludeIds = array_merge([$faq->id], $relatedFaqs->pluck('id')->toArray());
            $fallbackFaqs = Faq::with('categories')
                ->where('status', 'published')
                ->whereNotIn('id', $excludeIds)
                ->orderByDesc('views')
                ->take(3 - $relatedFaqs->count())
                ->get();
            $relatedFaqs = $relatedFaqs->concat($fallbackFaqs);
        }

        // Cek apakah mahasiswa/pengunjung sudah menandai FAQ ini membantu
        $voterHash = $studentNrp
            ? hash('sha256', 'nrp_' . $studentNrp)
            : hash('sha256', 'session_' . session()->getId());

        $hasVotedDb = FaqHelpfulVote::where('faq_id', $faq->id)
            ->where('voter_hash', $voterHash)
            ->exists();
        $sessionHelpful = session('helpful_faqs', []);
        $isHelpful = $hasVotedDb || in_array($faq->id, $sessionHelpful);

        return view('skms.detail', compact(
            'faq',
            'relatedFaqs',
            'studentNrp',
            'isHelpful'
        ));
    }

    /**
     * Endpoint untuk menambah jumlah view FAQ saat diklik/dibaca oleh pengguna.
     */
    public function incrementView(Faq $faq)
    {
        $faq->increment('views');

        return response()->json([
            'success' => true,
            'views'   => (int) $faq->views,
        ]);
    }

    /**
     * Endpoint interaktif untuk menandai / membatalkan tanda FAQ "Membantu 👍".
     * Mencegah manipulasi/spam angka dengan mencatat voter_hash unik di tabel faq_helpful_votes.
     * Satu identitas (NRP mahasiswa atau Guest Device) hanya bisa memberi 1 vote per FAQ.
     */
    public function toggleHelpful(Request $request, Faq $faq)
    {
        $nrp = session('student_nrp', null);
        $ip  = $request->ip();
        $voterHash = $nrp 
            ? hash('sha256', 'nrp_' . $nrp)
            : hash('sha256', 'session_' . session()->getId());

        // Periksa apakah pemilih ini sudah pernah memberi tanda membantu pada FAQ ini
        $existingVote = FaqHelpfulVote::where('faq_id', $faq->id)
            ->where('voter_hash', $voterHash)
            ->first();

        if ($existingVote) {
            // Jika sudah ada, klik berikutnya berfungsi membatalkan (toggle off)
            $existingVote->delete();
            if ($faq->helpful > 0) {
                $faq->decrement('helpful');
            }
            $isHelpful = false;

            $sessionHelpful = session('helpful_faqs', []);
            $sessionHelpful = array_values(array_diff($sessionHelpful, [$faq->id]));
            session(['helpful_faqs' => $sessionHelpful]);
        } else {
            // Jika belum ada, simpan vote baru dengan proteksi unique constraint
            try {
                FaqHelpfulVote::create([
                    'faq_id'      => $faq->id,
                    'student_nrp' => $nrp ?: null,
                    'ip_address'  => $ip,
                    'voter_hash'  => $voterHash,
                ]);
                $faq->increment('helpful');
                $isHelpful = true;

                $sessionHelpful = session('helpful_faqs', []);
                if (!in_array($faq->id, $sessionHelpful)) {
                    $sessionHelpful[] = $faq->id;
                    session(['helpful_faqs' => $sessionHelpful]);
                }
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                $isHelpful = true;
            }
        }

        $faq->refresh();

        return response()->json([
            'success'    => true,
            'helpful'    => (int) $faq->helpful,
            'is_helpful' => $isHelpful,
        ]);
    }

    /**
     * Halaman Formulir Pengajuan Pertanyaan Baru (Question Submission / Tanya Staf) Beridentitas NRP (SB-026).
     */
    public function showQuestionForm(Request $request)
    {
        $studentNrp = session('student_nrp', null);

        if (!$studentNrp) {
            return redirect()
                ->route('skms.login', array_filter([
                    'redirect' => 'tanya',
                    'q'        => $request->query('q'),
                ]))
                ->with('info', 'Silakan masukkan 9 digit NRP Anda terlebih dahulu untuk mengajukan pertanyaan kepada staf.');
        }

        $prefillQuestion = trim((string) $request->query('q', ''));
        $studentEmail = 's' . $studentNrp . '@student.ubaya.ac.id';

        // Ambil riwayat pengajuan pertanyaan (question_submissions) dari NRP mahasiswa yang sedang aktif
        $myQuestions = QuestionSubmission::with(['faq', 'user'])
            ->where('nrp', $studentNrp)
            ->latest()
            ->get();

        return view('skms.tanya', compact(
            'studentNrp',
            'prefillQuestion',
            'studentEmail',
            'myQuestions'
        ));
    }

    /**
     * Proses penyimpanan tiket pengajuan pertanyaan baru dari Mahasiswa ke tabel question_submissions (SB-026).
     */
    public function submitQuestion(Request $request)
    {
        $studentNrp = session('student_nrp', null);

        if (!$studentNrp || !preg_match('/^[0-9]{9}$/', $studentNrp)) {
            return redirect()
                ->route('skms.login', ['redirect' => 'tanya'])
                ->with('error', 'Sesi NRP Anda telah berakhir. Silakan masuk kembali menggunakan 9 digit NRP.');
        }

        $validated = $request->validate([
            'pertanyaan' => 'required|string|min:5|max:500',
        ], [
            'pertanyaan.required' => 'Pertanyaan wajib diisi.',
            'pertanyaan.min'      => 'Pertanyaan minimal terdiri dari 5 karakter.',
            'pertanyaan.max'      => 'Pertanyaan maksimal 500 karakter.',
        ]);

        $submission = QuestionSubmission::create([
            'nrp'        => $studentNrp,
            'pertanyaan' => trim($validated['pertanyaan']),
            'status'     => 'pending',
        ]);

        $studentEmail = 's' . $studentNrp . '@student.ubaya.ac.id';

        return redirect()
            ->route('skms.tanya')
            ->with('submitted_ticket', [
                'id'         => $submission->id,
                'nrp'        => $studentNrp,
                'email'      => $studentEmail,
                'pertanyaan' => $submission->pertanyaan,
            ]);
    }
}
