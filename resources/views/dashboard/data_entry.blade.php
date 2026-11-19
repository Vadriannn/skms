@extends('layouts.app', [
    'title' => 'Dashboard Data Entry - SKMS'
])

@section('content')
<div>
    <div class="skmk-page-intro">
        <div>
            <h1 class="skmk-page-main-title">Ruang kerja penyusun informasi</h1>
            <p class="skmk-page-subtitle">Susun draf baru, pantau antrean validasi, dan tindak lanjuti revisi.</p>
        </div>
        <div>
            <a href="{{ route('faqs.create') }}" class="btn-tambah-faq">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Tambah informasi baru</span>
            </a>
        </div>
    </div>

    <div class="skmk-hero-banner">
        <div>
            <div class="skmk-hero-kicker">PENYUSUNAN PENGETAHUAN SKMS</div>
            <div class="skmk-hero-heading">Terbuka untuk belajar.<br>Terhubung untuk berbagi.</div>
            <div class="skmk-hero-desc">Setiap pertanyaan yang disusun membantu mahasiswa menemukan jawaban resmi lebih cepat.</div>
        </div>
        <div class="skmk-hero-metrics">
            <div class="skmk-hero-metric-item">
                <div class="skmk-hero-metric-val">{{ str_pad($totalDraft ?? 0, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="skmk-hero-metric-label">draf tersimpan</div>
            </div>
            <div class="skmk-hero-metric-item">
                <div class="skmk-hero-metric-val">{{ str_pad($menungguValidasi ?? 0, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="skmk-hero-metric-label">menunggu validasi</div>
            </div>
        </div>
    </div>

    <div class="skmk-stat-row">
        <a href="{{ route('faqs.submissions', ['status' => 'draft']) }}" class="skmk-stat-box" style="text-decoration: none;">
            <div class="skmk-stat-num teal">{{ str_pad($totalDraft ?? 0, 2, '0', STR_PAD_LEFT) }}</div>
            <div>
                <div class="skmk-stat-meta-title">Total draf</div>
                <div class="skmk-stat-meta-sub">Siap disempurnakan &rarr;</div>
            </div>
        </a>
        <a href="{{ route('faqs.submissions', ['status' => 'pending']) }}" class="skmk-stat-box" style="text-decoration: none;">
            <div class="skmk-stat-num" style="color: #B4690E;">{{ str_pad($menungguValidasi ?? 0, 2, '0', STR_PAD_LEFT) }}</div>
            <div>
                <div class="skmk-stat-meta-title">Menunggu validasi</div>
                <div class="skmk-stat-meta-sub">Sedang ditinjau validator &rarr;</div>
            </div>
        </a>
        <a href="{{ route('faqs.submissions', ['status' => 'rejected']) }}" class="skmk-stat-box" style="text-decoration: none;">
            <div class="skmk-stat-num" style="color: #C93535;">{{ str_pad($perluRevisi ?? 0, 2, '0', STR_PAD_LEFT) }}</div>
            <div>
                <div class="skmk-stat-meta-title">Perlu revisi</div>
                <div class="skmk-stat-meta-sub">Lihat catatan perbaikan &rarr;</div>
            </div>
        </a>
        @php
            $tiketPendingCount = \App\Models\QuestionSubmission::where('status', 'pending')->count();
        @endphp
        <a href="{{ route('questions.index', ['status' => 'pending']) }}" class="skmk-stat-box" style="text-decoration: none;">
            <div class="skmk-stat-num teal">{{ str_pad($tiketPendingCount, 2, '0', STR_PAD_LEFT) }}</div>
            <div>
                <div class="skmk-stat-meta-title">Tiket mahasiswa</div>
                <div class="skmk-stat-meta-sub">Pertanyaan menunggu tanggapan &rarr;</div>
            </div>
        </a>
    </div>
</div>
@endsection
