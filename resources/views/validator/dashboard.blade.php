@extends('layouts.app', [
    'title' => 'Dashboard Validator - SKMS',
    'topbar_class' => 'seamless-topbar'
])

@section('topbar_left')
    <h1 class="topbar-page-title">Halaman Validator</h1>
@endsection

@section('content')
<div class="validator-dashboard-container">

    {{-- ── Statistik Kartu ─────────────────────────────────────── --}}
    <div class="validator-stats-grid">

        {{-- Kartu: Menunggu Validasi --}}
        <a href="{{ route('validator.antrian') }}" class="vd-stat-card amber" title="Klik untuk melihat antrean validasi">
            <div class="vd-stat-content">
                <div class="vd-stat-label">Menunggu Validasi</div>
                <div class="vd-stat-value">{{ $menungguValidasi }}</div>
            </div>
            <div class="vd-stat-icon amber">
                {{-- Hourglass icon --}}
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#DDA236" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 22h14"></path>
                    <path d="M5 2h14"></path>
                    <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"></path>
                    <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"></path>
                </svg>
            </div>
        </a>

        {{-- Kartu: Disetujui --}}
        <a href="{{ route('validator.disetujui') }}" class="vd-stat-card emerald" title="Klik untuk melihat FAQ yang disetujui">
            <div class="vd-stat-content">
                <div class="vd-stat-label">Disetujui</div>
                <div class="vd-stat-value">{{ $disetujui }}</div>
            </div>
            <div class="vd-stat-icon emerald">
                {{-- Checkmark circle icon --}}
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
        </a>
    </div>

    {{-- ── Daftar Terbaru ──────────────────────────────────────── --}}
    <div class="vd-recent-section">
        <div class="vd-recent-header">
            <h2 class="vd-recent-title">Daftar Terbaru</h2>
            <a href="{{ route('validator.antrian') }}" class="vd-see-all-link">Lihat Semua</a>
        </div>

        <div class="vd-recent-list">
            @forelse($daftarTerbaru as $faq)
                <div class="vd-recent-item">
                    <span class="vd-recent-question">{{ Str::limit($faq->pertanyaan, 70) }}</span>
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
                            <a href="{{ route('validator.faq.show', $faq->id) }}" class="vd-review-btn">Tinjau</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="vd-recent-empty">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <p>Belum ada FAQ yang diajukan.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
