<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Category;
use App\Models\Search;
use App\Services\TfIdfService;
use Illuminate\Http\Request;

class SkmsController extends Controller
{
    /**
     * Tampilan form masuk/identifikasi Mahasiswa UBAYA (hanya NRP).
     */
    public function showLoginForm()
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
        session()->forget(['student_name', 'student_nrp']);
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
        $topFaqs  = collect();
        $searched = false;

        // Ambil semua kategori untuk kebutuhan filter
        $categories = Category::withCount(['faqs' => function ($q) {
            $q->where('status', 'published');
        }])->orderBy('nama')->get();

        // Mode jika user melakukan pencarian atau memfilter kategori
        if ($keyword !== '' || !empty($selectedCategories)) {
            $searched = true;

            // Ambil Top FAQ berdasarkan view terbanyak untuk panel sebelah kanan
            $topFaqs = Faq::with('categories')
                ->where('status', 'published')
                ->orderByDesc('views')
                ->take(5)
                ->get();

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

        return view('skms.beranda', compact(
            'results',
            'topFaqs',
            'categories',
            'keyword',
            'selectedCategories',
            'searched',
            'studentNrp'
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
}
