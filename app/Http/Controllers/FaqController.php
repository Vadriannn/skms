<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\DuplicateLog;
use App\Models\Faq;
use App\Services\TfIdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    /**
     * Tampilkan daftar informasi FAQs
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $categoryId = $request->query('category_id');
        $search = $request->query('search');

        $query = Faq::with(['categories', 'user'])->latest();

        // Filter berdasarkan pencarian pertanyaan
        if ($search) {
            $query->where('pertanyaan', 'like', "%{$search}%");
        }

        // Filter berdasarkan kategori (Many-to-Many)
        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        // Filter berdasarkan status
        if ($status && in_array($status, ['draft', 'pending', 'published', 'rejected'])) {
            $query->where('status', $status);
        }

        // 3 per halaman persis seperti di mockup gambar (< 1 2 3 >)
        $faqs = $query->paginate(3)->withQueryString();
        $categories = Category::orderBy('nama', 'asc')->get();

        return view('faqs.index', compact('faqs', 'categories', 'status', 'categoryId', 'search'));
    }

    /**
     * Simpan informasi FAQ baru (Mendukung Multi-Kategori & Deteksi TF-IDF)
     */
    public function store(Request $request, TfIdfService $tfidfService)
    {
        $request->validate([
            'pertanyaan'     => 'required|string|max:500',
            'jawaban'        => 'required|string',
            'category_ids'   => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'category_id'    => 'nullable|exists:categories,id',
            'action_type'    => 'nullable|in:draft,pending',
        ], [
            'pertanyaan.required' => 'Pertanyaan informasi wajib diisi.',
            'jawaban.required'    => 'Jawaban informasi wajib diisi.',
        ]);

        // Ambil array category_ids atau fallback ke single category_id
        $categoryIds = $request->input('category_ids', []);
        if (empty($categoryIds) && $request->filled('category_id')) {
            $categoryIds = [$request->input('category_id')];
        }

        if (empty($categoryIds)) {
            return redirect()->back()->withErrors([
                'category_ids' => 'Pilih minimal satu kategori informasi.'
            ])->withInput();
        }

        $status = $request->input('action_type', 'draft');
        if (!in_array($status, ['draft', 'pending'])) {
            $status = 'draft';
        }

        // Jalankan perbandingan teks pertanyaan dengan repositori FAQ via TF-IDF
        $similarity = $tfidfService->checkSimilarity($request->pertanyaan, 0.60);

        $faq = Faq::create([
            'user_id'    => Auth::id(),
            'pertanyaan' => $request->pertanyaan,
            'jawaban'    => $request->jawaban,
            'status'     => $status,
            'views'      => 0,
        ]);

        // Hubungkan banyak kategori ke FAQ (Pivot table category_faq)
        $faq->categories()->sync($categoryIds);

        // Catat ke tabel duplicate_logs hanya jika terdeteksi duplikasi (skor >= threshold 60%)
        if ($similarity['is_duplicate'] && !empty($similarity['similar_faq'])) {
            DuplicateLog::create([
                'faq_id'         => $faq->id,
                'similar_faq_id' => $similarity['similar_faq']->id,
                'score'          => $similarity['highest_score'],
                'checked_at'     => now(),
            ]);
        }

        $statusLabel = $status === 'pending' ? 'dikirim untuk validasi' : 'disimpan sebagai draft';
        $logMessage = Auth::user()->name . " menambahkan informasi baru: \"{$faq->pertanyaan}\" ({$statusLabel})";
        if ($similarity['is_duplicate'] && !empty($similarity['similar_faq'])) {
            $logMessage .= " [Peringatan TF-IDF: Kemiripan {$similarity['percentage']} dengan FAQ #{$similarity['similar_faq']->id}]";
        }

        ActivityLog::log($logMessage, 'faq');

        $message = $status === 'pending'
            ? 'Informasi baru berhasil diajukan dan sedang menunggu validasi!'
            : 'Draft informasi baru berhasil disimpan!';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Memeriksa kemiripan teks pertanyaan dengan repositori FAQ via TF-IDF & Cosine Similarity
     */
    public function checkSimilarity(Request $request, TfIdfService $tfidfService)
    {
        $pertanyaan = trim((string) $request->input('pertanyaan', ''));

        if ($pertanyaan === '' || mb_strlen($pertanyaan) < 3) {
            return response()->json([
                'success'       => true,
                'has_checked'   => false,
                'highest_score' => 0,
                'percentage'    => '0%',
                'is_duplicate'  => false,
                'similar_faq'   => null,
                'candidates'    => [],
            ]);
        }

        $threshold = (float) $request->input('threshold', 0.60);
        $excludeId = $request->input('exclude_id') ? (int) $request->input('exclude_id') : null;

        $result = $tfidfService->checkSimilarity($pertanyaan, $threshold, $excludeId);

        $formattedCandidates = [];
        foreach ($result['candidates'] as $candidate) {
            $faq = $candidate['faq'];
            $formattedCandidates[] = [
                'id'          => $faq->id,
                'pertanyaan'  => $faq->pertanyaan,
                'jawaban'     => Str::limit(strip_tags($faq->jawaban), 120),
                'status'      => $faq->status,
                'status_label'=> [
                    'draft'     => 'Draft',
                    'pending'   => 'Menunggu Validasi',
                    'published' => 'Dipublikasikan',
                    'rejected'  => 'Ditolak',
                ][$faq->status] ?? ucfirst($faq->status),
                'score'       => $candidate['score'],
                'percentage'  => $candidate['percentage'],
                'categories'  => $faq->categories->pluck('nama')->toArray(),
            ];
        }

        $similarFaqData = null;
        if (!empty($result['similar_faq'])) {
            $sim = $result['similar_faq'];
            $similarFaqData = [
                'id'          => $sim->id,
                'pertanyaan'  => $sim->pertanyaan,
                'jawaban'     => Str::limit(strip_tags($sim->jawaban), 120),
                'status'      => $sim->status,
                'status_label'=> [
                    'draft'     => 'Draft',
                    'pending'   => 'Menunggu Validasi',
                    'published' => 'Dipublikasikan',
                    'rejected'  => 'Ditolak',
                ][$sim->status] ?? ucfirst($sim->status),
                'categories'  => $sim->categories->pluck('nama')->toArray(),
            ];
        }

        return response()->json([
            'success'       => true,
            'has_checked'   => true,
            'highest_score' => $result['highest_score'],
            'percentage'    => $result['percentage'],
            'is_duplicate'  => $result['is_duplicate'],
            'similar_faq'   => $similarFaqData,
            'candidates'    => $formattedCandidates,
        ]);
    }

    /**
     * Perbarui informasi FAQ dan sinkronisasi multi-kategori
     */
    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'pertanyaan'     => 'required|string|max:500',
            'jawaban'        => 'required|string',
            'category_ids'   => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'category_id'    => 'nullable|exists:categories,id',
            'status'         => 'nullable|in:draft,pending,published,rejected',
        ]);

        $categoryIds = $request->input('category_ids', []);
        if (empty($categoryIds) && $request->filled('category_id')) {
            $categoryIds = [$request->input('category_id')];
        }

        $faq->update([
            'pertanyaan' => $request->pertanyaan,
            'jawaban'    => $request->jawaban,
            'status'     => $request->input('status', $faq->status),
        ]);

        if (!empty($categoryIds)) {
            $faq->categories()->sync($categoryIds);
        }

        ActivityLog::log(
            Auth::user()->name . " memperbarui informasi FAQ: \"{$faq->pertanyaan}\"",
            'faq'
        );

        return redirect()->back()->with('success', 'Informasi FAQ berhasil diperbarui!');
    }

    /**
     * Hapus informasi FAQ
     */
    public function destroy(Faq $faq)
    {
        $title = $faq->pertanyaan;
        $faq->categories()->detach();
        $faq->delete();

        ActivityLog::log(
            Auth::user()->name . " menghapus informasi FAQ: \"{$title}\"",
            'faq'
        );

        return redirect()->back()->with('success', 'Informasi FAQ berhasil dihapus!');
    }

    /**
     * Tampilkan riwayat pengajuan FAQ
     */
    public function history()
    {
        $user = Auth::user();

        $historyFaqs = Faq::with('categories')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('faqs.history', compact('historyFaqs'));
    }
}
