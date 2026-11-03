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
     * Tampilkan daftar informasi FAQs (khusus informasi yang sudah dipublikasikan)
     */
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $query = Faq::with(['categories', 'user'])
            ->where('status', 'published')
            ->latest();

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

        // Paginate berdasarkan preferensi pengguna (default: 10)
        $faqs = $query->paginate($perPage)->withQueryString();
        $categories = Category::orderBy('nama', 'asc')->get();

        return view('faqs.index', compact('faqs', 'categories', 'categoryId', 'search', 'perPage'));
    }

    /**
     * Tampilkan halaman kelola pengajuan & draf FAQ (Semua Status: Draft, Pending, Rejected, Published)
     */
    public function submissions(Request $request)
    {
        $status = $request->query('status'); // draft, pending, rejected, published, atau all
        $categoryId = $request->query('category_id');
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Hitung total untuk badge tab filter
        $countDraft = Faq::where('status', 'draft')->count();
        $countPending = Faq::where('status', 'pending')->count();
        $countRejected = Faq::where('status', 'rejected')->count();
        $countPublished = Faq::where('status', 'published')->count();
        $countAll = $countDraft + $countPending + $countRejected + $countPublished;

        $query = Faq::with(['categories', 'user', 'latestReview.user', 'reviews.user'])
            ->latest();

        if ($search) {
            $query->where('pertanyaan', 'like', "%{$search}%");
        }

        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        if ($status && in_array($status, ['draft', 'pending', 'rejected', 'published'])) {
            $query->where('status', $status);
        }

        $faqs = $query->paginate($perPage)->withQueryString();
        $categories = Category::orderBy('nama', 'asc')->get();

        return view('faqs.submissions', compact(
            'faqs',
            'categories',
            'status',
            'categoryId',
            'search',
            'perPage',
            'countAll',
            'countDraft',
            'countPending',
            'countRejected',
            'countPublished'
        ));
    }

    /**
     * Tampilkan halaman formulir input FAQ baru
     */
    public function create()
    {
        $categories = Category::orderBy('nama', 'asc')->get();
        return view('faqs.create', compact('categories'));
    }

    /**
     * Tampilkan halaman formulir edit / revisi FAQ
     */
    public function edit(Faq $faq)
    {
        $faq->load(['categories', 'latestReview.user']);
        $categories = Category::orderBy('nama', 'asc')->get();
        return view('faqs.edit', compact('faq', 'categories'));
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

        return redirect()->route('faqs.submissions', ['status' => $status])->with('success', $message);
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
     * Perbarui informasi FAQ dan sinkronisasi multi-kategori (dukungan revisi langsung)
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
            'action_type'    => 'nullable|in:draft,pending,resubmit,published',
        ]);

        $categoryIds = $request->input('category_ids', []);
        if (empty($categoryIds) && $request->filled('category_id')) {
            $categoryIds = [$request->input('category_id')];
        }

        $oldStatus = $faq->status;
        $actionType = $request->input('action_type');
        $user = Auth::user();

        // Alur penentuan status baru:
        if ($actionType === 'draft') {
            $newStatus = 'draft';
        } elseif ($actionType === 'published' && $user->role === 'super_admin') {
            // Super Admin berhak update langsung published
            $newStatus = 'published';
        } elseif ($oldStatus === 'published' && $user->role === 'data_entry') {
            // Pendekatan A: Data Entry mengedit FAQ published -> Re-Validation (masuk antrean validasi lagi)
            $newStatus = 'pending';
        } elseif ($actionType === 'resubmit' || $actionType === 'pending') {
            $newStatus = 'pending';
        } else {
            $newStatus = $request->input('status', $oldStatus);
            if ($oldStatus === 'published' && $user->role === 'data_entry') {
                $newStatus = 'pending';
            }
        }

        $faq->update([
            'pertanyaan' => $request->pertanyaan,
            'jawaban'    => $request->jawaban,
            'status'     => $newStatus,
        ]);

        if (!empty($categoryIds)) {
            $faq->categories()->sync($categoryIds);
        }

        if ($oldStatus === 'published' && $newStatus === 'pending') {
            $logMsg = $user->name . " merevisi FAQ terbit: \"{$faq->pertanyaan}\" (diajukan ulang untuk validasi)";
            $successMsg = 'Perubahan FAQ berhasil disimpan! Karena sebelumnya sudah terbit, FAQ kini ditarik dari publik dan masuk ke antrean Validator untuk diverifikasi ulang.';
        } elseif ($oldStatus === 'rejected' && $newStatus === 'pending') {
            $logMsg = $user->name . " merevisi dan mengajukan kembali FAQ ke Validator: \"{$faq->pertanyaan}\"";
            $successMsg = 'Informasi FAQ berhasil direvisi dan telah diajukan kembali ke antrean Validator!';
        } elseif ($newStatus === 'pending') {
            $logMsg = $user->name . " mengajukan FAQ untuk divalidasi: \"{$faq->pertanyaan}\"";
            $successMsg = 'Informasi FAQ berhasil diajukan dan sedang menunggu validasi!';
        } elseif ($newStatus === 'draft') {
            $logMsg = $user->name . " menyimpan draf informasi FAQ: \"{$faq->pertanyaan}\"";
            $successMsg = 'Draf informasi FAQ berhasil disimpan!';
        } else {
            $logMsg = $user->name . " memperbarui informasi FAQ: \"{$faq->pertanyaan}\"";
            $successMsg = 'Informasi FAQ berhasil diperbarui!';
        }

        ActivityLog::log($logMsg, 'faq');

        if ($newStatus === 'published') {
            return redirect()->route('faqs.index')->with('success', $successMsg);
        } else {
            return redirect()->route('faqs.submissions', ['status' => $newStatus])->with('success', $successMsg);
        }
    }

    /**
     * Nonaktifkan FAQ: tarik dari publik dan jadikan status draft
     */
    public function unpublish(Faq $faq)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['data_entry', 'super_admin'])) {
            abort(403, 'Anda tidak memiliki hak akses untuk menonaktifkan FAQ ini.');
        }

        $faq->update(['status' => 'draft']);

        ActivityLog::log(
            $user->name . " menonaktifkan FAQ: \"{$faq->pertanyaan}\" (ditarik ke draf)",
            'faq'
        );

        return redirect()->route('faqs.submissions', ['status' => 'draft'])
            ->with('success', "Informasi FAQ \"{$faq->pertanyaan}\" berhasil dinonaktifkan dari publik dan dipindahkan ke Draf.");
    }

    /**
     * Hapus informasi FAQ
     */
    public function destroy(Faq $faq)
    {
        $user = Auth::user();

        // Keamanan: Hanya Super Admin atau pembuat FAQ yang berhak menghapus
        if ($user->role !== 'super_admin' && $faq->user_id !== $user->id) {
            return redirect()->back()->withErrors([
                'error' => 'Anda tidak memiliki hak akses untuk menghapus FAQ ini.'
            ]);
        }

        $title = $faq->pertanyaan;
        $faq->categories()->detach();
        $faq->delete();

        ActivityLog::log(
            $user->name . " menghapus informasi FAQ: \"{$title}\"",
            'faq'
        );

        return redirect()->back()->with('success', 'Informasi FAQ berhasil dihapus secara permanen!');
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
