@extends('layouts.app', [
    'title' => 'Dashboard Validator - SKMS'
])

@section('content')
<div class="validator-dashboard-container">

    <div class="skmk-page-intro">
        <div>
            <h1 class="skmk-page-main-title">Ruang kerja validator</h1>
            <p class="skmk-page-subtitle">Jaga kualitas dan akurasi informasi sebelum diterbitkan untuk mahasiswa.</p>
        </div>
        <div>
            <a href="{{ route('validator.antrian') }}" class="btn-tambah-faq">
                <span>Buka antrean validasi</span>
                <span>&rarr;</span>
            </a>
        </div>
    </div>

    <div class="skmk-hero-banner">
        <div>
            <div class="skmk-hero-kicker">VERIFIKASI PENGETAHUAN SKMS</div>
            <div class="skmk-hero-heading">Satu sumber informasi.<br>Banyak jawaban berarti.</div>
            <div class="skmk-hero-desc">Informasi yang sudah divalidasi siap membantu mahasiswa menemukan jawaban tepercaya.</div>
        </div>
        <div class="skmk-hero-metrics">
            <div class="skmk-hero-metric-item">
                <div class="skmk-hero-metric-val">{{ str_pad($disetujui, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="skmk-hero-metric-label">informasi terbit</div>
            </div>
            <div class="skmk-hero-metric-item">
                <div class="skmk-hero-metric-val">{{ str_pad($menungguValidasi, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="skmk-hero-metric-label">menunggu validasi</div>
            </div>
        </div>
    </div>

    <div class="skmk-stat-row">
        <a href="{{ route('validator.antrian') }}" class="skmk-stat-box" style="text-decoration: none;">
            <div class="skmk-stat-num" style="color: #B4690E;">{{ str_pad($menungguValidasi, 2, '0', STR_PAD_LEFT) }}</div>
            <div>
                <div class="skmk-stat-meta-title">Menunggu validasi</div>
                <div class="skmk-stat-meta-sub">Perlu segera ditinjau &rarr;</div>
            </div>
        </a>
        <a href="{{ route('faqs.index') }}" class="skmk-stat-box" style="text-decoration: none;">
            <div class="skmk-stat-num teal">{{ str_pad($disetujui, 2, '0', STR_PAD_LEFT) }}</div>
            <div>
                <div class="skmk-stat-meta-title">Informasi terbit</div>
                <div class="skmk-stat-meta-sub">Lihat daftar informasi publik &rarr;</div>
            </div>
        </a>
    </div>

    {{-- ── Daftar Terbaru ──────────────────────────────────────── --}}
    <div class="vd-recent-section">
        <div class="vd-recent-header">
            <h2 class="vd-recent-title">Daftar pengajuan terbaru</h2>
            <a href="{{ route('validator.antrian') }}" class="skmk-link-action">Lihat semua &rarr;</a>
        </div>

        <div class="vd-recent-list">
            @forelse($daftarTerbaru as $faq)
                <div class="vd-recent-item">
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; flex: 1; min-width: 0;">
                        <span class="vd-recent-question" style="flex: unset; font-weight: 600;">{{ Str::limit($faq->pertanyaan, 75) }}</span>
                        @if($faq->latestDuplicateLog && $faq->status === 'pending')
                            @php
                                $dupScoreVal = (float) $faq->latestDuplicateLog->score;
                                $dupPct = $dupScoreVal <= 1 ? round($dupScoreVal * 100) : round($dupScoreVal);
                            @endphp
                            <span style="background-color: #FFF4EB; color: #B4690E; border: 1px solid #F5DFB0; font-size: 11px; padding: 2px 8px; border-radius: 6px; font-weight: 600;">
                                Mirip {{ $dupPct }}%
                            </span>
                        @endif
                    </div>
                    <div class="vd-recent-right">
                        @if($faq->status === 'pending')
                            <span class="vd-status-badge vd-status-pending">Menunggu</span>
                        @elseif($faq->status === 'published')
                            <span class="vd-status-badge vd-status-approved">Disetujui</span>
                        @elseif($faq->status === 'rejected')
                            <span class="vd-status-badge vd-status-rejected">Ditolak</span>
                        @else
                            <span class="vd-status-badge vd-status-draft">Draft</span>
                        @endif
                        @if($faq->status === 'pending')
                            <a href="{{ route('validator.faq.show', $faq->id) }}" class="skmk-link-action">Tinjau &rarr;</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="vd-recent-empty">
                    <p>Belum ada FAQ yang diajukan.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
