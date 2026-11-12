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
     * Tampilan form masuk/identifikasi Mahasiswa UBAYA (Nama & NRP).
     */
    public function showLoginForm()
    {
        // Jika sudah memiliki sesi mahasiswa, langsung arahkan ke beranda
        if (session()->has('student_nrp')) {
            return redirect()->route('skms.beranda');
        }

        return view('skms.masuk');
    }

    /**
     * Proses validasi Nama & NRP Mahasiswa (wajib 9 digit angka).
     */
    public function processLogin(Request $request)
    {
        $nama = trim((string) $request->input('nama', ''));
        $nrp  = trim((string) $request->input('nrp', ''));

        if (empty($nama)) {
            return back()->withInput()->with('error', 'Nama lengkap wajib diisi.');
        }

        // Validasi format NRP UBAYA (wajib tepat 9 digit angka)
        if (!preg_match('/^[0-9]{9}$/', $nrp)) {
            return back()->withInput()->with('error', 'NRP tidak valid. NRP mahasiswa UBAYA harus terdiri dari 9 digit angka (contoh: 160421001).');
        }

        // Simpan data mahasiswa dalam session
        session([
            'student_name' => $nama,
            'student_nrp'  => $nrp,
        ]);

        return redirect()->route('skms.beranda')->with('success', 'Selamat datang di Pusat Informasi SKMS, ' . $nama . '!');
    }

    /**
     * Keluar dari sesi mahasiswa / ganti identitas.
     */
    public function logoutStudent()
    {
        session()->forget(['student_name', 'student_nrp']);
        return redirect()->route('skms.beranda')->with('info', 'Anda telah keluar dari sesi identitas mahasiswa.');
    }

    /**
     * Halaman Beranda Publik SKMS — menampilkan FAQ yang sudah terpublish.
     * Pengguna TIDAK diharuskan login (bebas diakses), namun dapat mengidentifikasikan diri
     * dengan Nama & NRP mahasiswa jika diinginkan.
     */
    public function index(Request $request, TfIdfService $tfIdf)
    {
        $studentName = session('student_name', null);
        $studentNrp  = session('student_nrp', null);
        $helpfulFaqs = session('helpful_faqs', []);

        $keyword = trim((string) $request->input('search', ''));

        // Ambil kategori terpilih (mendukung multiple checkbox: categories[])
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

        // Ambil semua kategori beserta hitungan FAQ yang terpublish
        $categories = Category::withCount(['faqs' => function ($q) {
            $q->where('status', 'published');
        }])->orderBy('nama')->get();

        // ─── MODE PENCARIAN (TF-IDF) ─────────────────────────────────
        if ($keyword !== '') {
            $searched = true;

            // Gunakan TfIdfService yang sudah ada
            $similarity = $tfIdf->checkSimilarity($keyword, 0.01);

            if (!empty($similarity['candidates'])) {
                // Ambil ID FAQ yang ditemukan, urutkan berdasarkan skor tertinggi
                $faqIds = collect($similarity['candidates'])->pluck('faq.id')->toArray();
                $scores = collect($similarity['candidates'])->pluck('score', 'faq.id')->toArray();

                $query = Faq::with('categories')
                    ->where('status', 'published')
                    ->whereIn('id', $faqIds);

                // Filter multi-kategori jika ada yang dipilih
                if (!empty($selectedCategories)) {
                    $query->whereHas('categories', function ($q) use ($selectedCategories) {
                        $q->whereIn('categories.id', $selectedCategories);
                    });
                }

                $faqs = $query->get();

                // Urutkan berdasarkan skor TF-IDF (tertinggi dulu)
                $results = $faqs->sortByDesc(function ($faq) use ($scores) {
                    return $scores[$faq->id] ?? 0;
                })->values()->map(function ($faq) use ($scores) {
                    $faq->relevance_score = $scores[$faq->id] ?? 0;
                    return $faq;
                });
            }

            // Catat riwayat pencarian ke tabel searches (NRP diisi jika mahasiswa sudah masuk)
            try {
                Search::create([
                    'nrp'        => $studentNrp ?: null,
                    'kata_kunci' => $keyword,
                    'faq_id'     => $results->first()?->id ?? null,
                ]);
            } catch (\Throwable $e) {
                // Abaikan kesalahan pencatatan agar alur pengguna tidak terputus
            }
        }
        // ─── MODE DEFAULT (Tampilkan semua FAQ published) ────────────
        else {
            $query = Faq::with('categories')
                ->where('status', 'published')
                ->orderByDesc('updated_at');

            // Filter multi-kategori jika ada yang dipilih
            if (!empty($selectedCategories)) {
                $query->whereHas('categories', function ($q) use ($selectedCategories) {
                    $q->whereIn('categories.id', $selectedCategories);
                });
            }

            $results = $query->get();
        }

        return view('skms.beranda', compact(
            'results', 
            'categories', 
            'keyword', 
            'selectedCategories', 
            'searched', 
            'studentName', 
            'studentNrp',
            'helpfulFaqs'
        ));
    }

    /**
     * Endpoint interaktif untuk menandai bahwa FAQ ini "Membantu 👍".
     */
    public function toggleHelpful(Faq $faq)
    {
        $helpfulFaqs = session()->get('helpful_faqs', []);
        $isHelpful   = in_array($faq->id, $helpfulFaqs);

        if ($isHelpful) {
            if ($faq->helpful > 0) {
                $faq->decrement('helpful');
            }
            $helpfulFaqs = array_values(array_diff($helpfulFaqs, [$faq->id]));
            session(['helpful_faqs' => $helpfulFaqs]);
            $newStatus = false;
        } else {
            $faq->increment('helpful');
            session()->push('helpful_faqs', $faq->id);
            $newStatus = true;
        }

        $faq->refresh();

        return response()->json([
            'success'    => true,
            'helpful'    => (int) $faq->helpful,
            'is_helpful' => $newStatus,
        ]);
    }
}
