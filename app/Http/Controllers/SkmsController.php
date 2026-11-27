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
     * Halaman Idle / Attract Screen Kiosk Layar Sentuh SKMS UBAYA.
     * Membersihkan sesi NRP mahasiswa sebelumnya agar pengguna berikutnya mulai dari sesi baru.
     */
    public function idle()
    {
        session()->forget(['student_name', 'student_nrp', 'helpful_faqs', 'skms_last_activity']);
        session()->regenerate();

        return view('skms.idle');
    }

    /**
     * Tampilan form masuk/identifikasi Mahasiswa UBAYA (hanya NRP).
     */
    public function showLoginForm(Request $request)
    {
        if (session()->has('student_nrp')) {
            return redirect()->route('skms.beranda');
        }

        return view('skms.masuk');
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

                // Catat riwayat pencarian mahasiswa (Hanya jika kata kunci tidak kosong)
                if (trim($keyword) !== '') {
                    try {
                        Search::create([
                            'nrp'        => $studentNrp ?: null,
                            'kata_kunci' => trim($keyword),
                            'faq_id'     => $results->first()?->id ?? null,
                        ]);
                    } catch (\Throwable $e) {
                        // Abaikan kesalahan pencatatan
                    }
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

        // Cek apakah mahasiswa/pengunjung sudah menandai FAQ ini membantu atau tidak membantu
        $voterHash = $studentNrp
            ? hash('sha256', 'nrp_' . $studentNrp)
            : hash('sha256', 'session_' . session()->getId());

        $userVote = FaqHelpfulVote::where('faq_id', $faq->id)
            ->where('voter_hash', $voterHash)
            ->first();

        $sessionHelpful = session('helpful_faqs', []);
        $sessionNotHelpful = session('not_helpful_faqs', []);

        $isHelpful = ($userVote && ($userVote->vote_type ?? 'helpful') === 'helpful') || in_array($faq->id, $sessionHelpful);
        $isNotHelpful = ($userVote && ($userVote->vote_type ?? '') === 'not_helpful') || in_array($faq->id, $sessionNotHelpful);

        return view('skms.detail', compact(
            'faq',
            'relatedFaqs',
            'studentNrp',
            'isHelpful',
            'isNotHelpful'
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

        // Periksa apakah pemilih ini sudah pernah memberi suara pada FAQ ini
        $existingVote = FaqHelpfulVote::where('faq_id', $faq->id)
            ->where('voter_hash', $voterHash)
            ->first();

        $sessionHelpful = session('helpful_faqs', []);
        $sessionNotHelpful = session('not_helpful_faqs', []);

        if ($existingVote) {
            if (($existingVote->vote_type ?? 'helpful') === 'helpful') {
                // Batalkan vote membantu (toggle off)
                $existingVote->delete();
                if ($faq->helpful > 0) {
                    $faq->decrement('helpful');
                }
                $isHelpful = false;
                $isNotHelpful = false;
                $sessionHelpful = array_values(array_diff($sessionHelpful, [$faq->id]));
            } else {
                // Ubah pilihan dari not_helpful ke helpful
                $existingVote->update(['vote_type' => 'helpful']);
                if ($faq->not_helpful > 0) {
                    $faq->decrement('not_helpful');
                }
                $faq->increment('helpful');
                $isHelpful = true;
                $isNotHelpful = false;
                if (!in_array($faq->id, $sessionHelpful)) {
                    $sessionHelpful[] = $faq->id;
                }
                $sessionNotHelpful = array_values(array_diff($sessionNotHelpful, [$faq->id]));
            }
        } else {
            // Simpan vote baru "helpful"
            try {
                FaqHelpfulVote::create([
                    'faq_id'      => $faq->id,
                    'student_nrp' => $nrp ?: null,
                    'ip_address'  => $ip,
                    'voter_hash'  => $voterHash,
                    'vote_type'   => 'helpful',
                ]);
                $faq->increment('helpful');
                $isHelpful = true;
                $isNotHelpful = false;

                if (!in_array($faq->id, $sessionHelpful)) {
                    $sessionHelpful[] = $faq->id;
                }
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                $isHelpful = true;
                $isNotHelpful = false;
            }
        }

        session(['helpful_faqs' => $sessionHelpful, 'not_helpful_faqs' => $sessionNotHelpful]);
        $faq->refresh();

        return response()->json([
            'success'        => true,
            'helpful'        => (int) $faq->helpful,
            'not_helpful'    => (int) $faq->not_helpful,
            'is_helpful'     => $isHelpful,
            'is_not_helpful' => $isNotHelpful,
        ]);
    }

    /**
     * Endpoint interaktif untuk menandai / membatalkan tanda FAQ "Tidak Membantu 👎".
     */
    public function toggleNotHelpful(Request $request, Faq $faq)
    {
        $nrp = session('student_nrp', null);
        $ip  = $request->ip();
        $voterHash = $nrp 
            ? hash('sha256', 'nrp_' . $nrp)
            : hash('sha256', 'session_' . session()->getId());

        $existingVote = FaqHelpfulVote::where('faq_id', $faq->id)
            ->where('voter_hash', $voterHash)
            ->first();

        $sessionHelpful = session('helpful_faqs', []);
        $sessionNotHelpful = session('not_helpful_faqs', []);

        if ($existingVote) {
            if (($existingVote->vote_type ?? 'helpful') === 'not_helpful') {
                // Batalkan vote tidak membantu (toggle off)
                $existingVote->delete();
                if ($faq->not_helpful > 0) {
                    $faq->decrement('not_helpful');
                }
                $isHelpful = false;
                $isNotHelpful = false;
                $sessionNotHelpful = array_values(array_diff($sessionNotHelpful, [$faq->id]));
            } else {
                // Ubah pilihan dari helpful ke not_helpful
                $existingVote->update(['vote_type' => 'not_helpful']);
                if ($faq->helpful > 0) {
                    $faq->decrement('helpful');
                }
                $faq->increment('not_helpful');
                $isHelpful = false;
                $isNotHelpful = true;
                if (!in_array($faq->id, $sessionNotHelpful)) {
                    $sessionNotHelpful[] = $faq->id;
                }
                $sessionHelpful = array_values(array_diff($sessionHelpful, [$faq->id]));
            }
        } else {
            // Simpan vote baru "not_helpful"
            try {
                FaqHelpfulVote::create([
                    'faq_id'      => $faq->id,
                    'student_nrp' => $nrp ?: null,
                    'ip_address'  => $ip,
                    'voter_hash'  => $voterHash,
                    'vote_type'   => 'not_helpful',
                ]);
                $faq->increment('not_helpful');
                $isHelpful = false;
                $isNotHelpful = true;

                if (!in_array($faq->id, $sessionNotHelpful)) {
                    $sessionNotHelpful[] = $faq->id;
                }
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                $isHelpful = false;
                $isNotHelpful = true;
            }
        }

        session(['helpful_faqs' => $sessionHelpful, 'not_helpful_faqs' => $sessionNotHelpful]);
        $faq->refresh();

        return response()->json([
            'success'        => true,
            'helpful'        => (int) $faq->helpful,
            'not_helpful'    => (int) $faq->not_helpful,
            'is_helpful'     => $isHelpful,
            'is_not_helpful' => $isNotHelpful,
        ]);
    }

    /**
     * Halaman Formulir Pengajuan Pertanyaan Baru (Question Submission / Tanya Staf) (SB-026).
     * Aturan Bisnis:
     * Formulir HANYA DAPAT DIAKSES ketika mahasiswa SUDAH melakukan pencarian dan ternyata TIDAK ADA FAQ yang relevan.
     */
    public function showQuestionForm(Request $request, TfIdfService $tfIdf)
    {
        $studentNrp = session('student_nrp', null);

        // Jika mahasiswa baru saja mengirim tiket, izinkan menampilkan kartu konfirmasi
        if (session('submitted_ticket')) {
            $prefillQuestion = '';
            $studentEmail = $studentNrp ? "s{$studentNrp}@student.ubaya.ac.id" : null;
            $myQuestions = $studentNrp
                ? QuestionSubmission::with(['faq', 'user'])->where('nrp', $studentNrp)->latest()->get()
                : collect();

            return view('skms.tanya', compact(
                'studentNrp',
                'prefillQuestion',
                'studentEmail',
                'myQuestions'
            ));
        }

        $keyword = trim((string) $request->query('q', ''));

        // 1. Validasi: Mahasiswa harus sudah melakukan pencarian terlebih dahulu
        if ($keyword === '') {
            return redirect()
                ->route('skms.beranda')
                ->with('warning', 'Formulir pengajuan pertanyaan hanya dapat diakses setelah Anda melakukan pencarian di repositori FAQ dan tidak menemukan jawaban yang relevan.');
        }

        // 2. Validasi: Periksa apakah memang benar-benar tidak ada FAQ yang relevan
        $similarity = $tfIdf->checkSimilarity($keyword, 0.05);
        $hasCandidates = !empty($similarity['candidates']);

        if (!$hasCandidates) {
            $hasCandidates = Faq::where('status', 'published')
                ->where(function ($q) use ($keyword) {
                    $q->where('pertanyaan', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('jawaban', 'LIKE', '%' . $keyword . '%');
                })->exists();
        }

        // Jika ternyata MASIH ADA FAQ yang relevan, arahkan ke hasil pencarian FAQ tersebut
        if ($hasCandidates) {
            return redirect()
                ->route('skms.beranda', ['search' => $keyword])
                ->with('info', "Kami menemukan beberapa informasi FAQ yang relevan dengan kata kunci \"{$keyword}\". Silakan pelajari jawaban yang tersedia terlebih dahulu sebelum mengajukan pertanyaan baru.");
        }

        // 3. Validasi: Mahasiswa WAJIB masuk dengan 9 digit NRP terlebih dahulu
        if (!$studentNrp) {
            return redirect()
                ->route('skms.login')
                ->with('info', 'Silakan masukkan 9 digit NRP Anda terlebih dahulu untuk masuk ke portal SKMS sebelum mengajukan pertanyaan.');
        }

        // 4. Jika pencarian NIHIL dan mahasiswa SUDAH masuk NRP: AKSES DIBERIKAN!
        $prefillQuestion = $keyword;
        $studentEmail = "s{$studentNrp}@student.ubaya.ac.id";

        $myQuestions = $studentNrp
            ? QuestionSubmission::with(['faq', 'user'])
                ->where('nrp', $studentNrp)
                ->latest()
                ->get()
            : collect();

        return view('skms.tanya', compact(
            'studentNrp',
            'prefillQuestion',
            'studentEmail',
            'myQuestions'
        ));
    }

    /**
     * Proses penyimpanan tiket pengajuan pertanyaan baru dari Mahasiswa ke tabel question_submissions (SB-026).
     * Aturan bisnis:
     * - Mahasiswa WAJIB mengisi 9 digit NRP untuk dapat mengajukan pertanyaan (tidak boleh kosong/anonim).
     * - Jika mahasiswa sudah login NRP, NRP diambil otomatis dari sesi.
     * - Email tujuan jawaban resmi otomatis dibentuk: {nrp}@student.ubaya.ac.id.
     */
    public function submitQuestion(Request $request)
    {
        $studentNrp = session('student_nrp', null);

        $requestData = $request->all();
        if ($studentNrp && empty($requestData['nrp'])) {
            $requestData['nrp'] = $studentNrp;
        }

        $validated = \Illuminate\Support\Facades\Validator::make($requestData, [
            'pertanyaan' => 'required|string|min:5|max:500',
            'nrp'        => ['required', 'regex:/^[0-9]{9}$/'],
        ], [
            'pertanyaan.required' => 'Pertanyaan wajib diisi.',
            'pertanyaan.min'      => 'Pertanyaan minimal terdiri dari 5 karakter.',
            'pertanyaan.max'      => 'Pertanyaan maksimal 500 karakter.',
            'nrp.required'        => 'NRP mahasiswa wajib diisi untuk dapat mengajukan pertanyaan.',
            'nrp.regex'           => 'Format NRP tidak valid. NRP mahasiswa UBAYA harus terdiri dari tepat 9 digit angka (contoh: 161124011).',
        ])->validate();

        $nrp = trim($validated['nrp']);

        // Simpan sesi NRP jika belum login
        if (!session()->has('student_nrp') || session('student_nrp') !== $nrp) {
            session(['student_nrp' => $nrp]);
        }

        $submission = QuestionSubmission::create([
            'nrp'        => $nrp,
            'pertanyaan' => trim($validated['pertanyaan']),
            'status'     => 'pending',
        ]);

        $studentEmail = $submission->student_email;

        return redirect()
            ->route('skms.tanya')
            ->with('submitted_ticket', [
                'id'         => $submission->id,
                'nrp'        => $submission->nrp,
                'email'      => $studentEmail,
                'pertanyaan' => $submission->pertanyaan,
            ]);
    }
}
