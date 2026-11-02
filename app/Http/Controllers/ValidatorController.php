<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Faq;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidatorController extends Controller
{
    /**
     * Dashboard Validator: ringkasan statistik + daftar terbaru.
     */
    public function dashboard()
    {
        $menungguValidasi = Faq::where('status', 'pending')->count();
        $disetujui        = Faq::where('status', 'published')->count();

        // Daftar terbaru: gabungan pending & published, 5 terakhir
        $daftarTerbaru = Faq::with(['categories', 'user'])
            ->whereIn('status', ['pending', 'published', 'rejected'])
            ->latest()
            ->take(5)
            ->get();

        return view('validator.dashboard', compact('menungguValidasi', 'disetujui', 'daftarTerbaru'));
    }

    /**
     * Daftar Antrean FAQ yang menunggu validasi (status = pending).
     */
    public function antrian(Request $request)
    {
        $search     = $request->query('search');
        $categoryId = $request->query('category_id');

        $query = Faq::with(['categories', 'user', 'reviews.user'])
            ->where('status', 'pending')
            ->latest();

        if ($search) {
            $query->where('pertanyaan', 'like', "%{$search}%");
        }

        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        $faqs       = $query->paginate(10)->withQueryString();
        $categories = \App\Models\Category::orderBy('nama', 'asc')->get();

        return view('validator.antrian', compact('faqs', 'categories', 'search', 'categoryId'));
    }

    /**
     * Daftar FAQ yang sudah disetujui (status = published).
     */
    public function disetujui(Request $request)
    {
        $search = $request->query('search');

        $query = Faq::with(['categories', 'user', 'reviews' => function ($q) {
            $q->where('keputusan', 'approved')->latest()->limit(1);
        }])
            ->where('status', 'published')
            ->latest();

        if ($search) {
            $query->where('pertanyaan', 'like', "%{$search}%");
        }

        $faqs = $query->paginate(10)->withQueryString();

        return view('validator.disetujui', compact('faqs', 'search'));
    }

    /**
     * Daftar semua FAQ (untuk referensi validator).
     */
    public function semuaFaq(Request $request)
    {
        $search     = $request->query('search');
        $status     = $request->query('status');
        $categoryId = $request->query('category_id');

        $query = Faq::with(['categories', 'user', 'reviews'])
            ->latest();

        if ($search) {
            $query->where('pertanyaan', 'like', "%{$search}%");
        }

        if ($status && in_array($status, ['draft', 'pending', 'published', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        $faqs       = $query->paginate(10)->withQueryString();
        $categories = \App\Models\Category::orderBy('nama', 'asc')->get();

        return view('validator.semua_faq', compact('faqs', 'categories', 'search', 'status', 'categoryId'));
    }

    /**
     * Tampilkan detail satu FAQ beserta riwayat review.
     */
    public function show(Faq $faq)
    {
        $faq->load(['categories', 'user', 'reviews.user']);
        return view('validator.detail', compact('faq'));
    }

    /**
     * Menyetujui FAQ: ubah status menjadi published, simpan review.
     */
    public function approve(Request $request, Faq $faq)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:1000',
        ]);

        // Simpan keputusan ke tabel reviews
        Review::create([
            'faq_id'    => $faq->id,
            'user_id'   => Auth::id(),
            'keputusan' => 'approved',
            'catatan'   => $request->input('catatan'),
        ]);

        // Ubah status FAQ menjadi published
        $faq->update(['status' => 'published']);

        ActivityLog::log(
            Auth::user()->name . " menyetujui FAQ: \"{$faq->pertanyaan}\"",
            'faq'
        );

        return redirect()->route('validator.antrian')
            ->with('success', "FAQ \"{$faq->pertanyaan}\" berhasil disetujui dan dipublikasikan!");
    }

    /**
     * Menolak FAQ: ubah status menjadi rejected, simpan review beserta catatan.
     */
    public function reject(Request $request, Faq $faq)
    {
        $request->validate([
            'catatan' => 'required|string|min:5|max:1000',
        ], [
            'catatan.required' => 'Catatan perbaikan wajib diisi saat menolak FAQ.',
            'catatan.min'      => 'Catatan perbaikan minimal 5 karakter.',
        ]);

        // Simpan keputusan ke tabel reviews
        Review::create([
            'faq_id'    => $faq->id,
            'user_id'   => Auth::id(),
            'keputusan' => 'rejected',
            'catatan'   => $request->input('catatan'),
        ]);

        // Ubah status FAQ menjadi rejected
        $faq->update(['status' => 'rejected']);

        ActivityLog::log(
            Auth::user()->name . " menolak FAQ: \"{$faq->pertanyaan}\" (Catatan: {$request->catatan})",
            'faq'
        );

        return redirect()->route('validator.antrian')
            ->with('success', "FAQ \"{$faq->pertanyaan}\" telah ditolak. Catatan perbaikan telah disimpan.");
    }
}
