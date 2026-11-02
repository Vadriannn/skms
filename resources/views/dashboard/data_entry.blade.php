@extends('layouts.app', [
    'title' => 'Dashboard Data Entry - SKMS',
    'topbar_class' => 'seamless-topbar'
])

@section('topbar_left')
    <h1 class="topbar-page-title">Dashboard Data Entry</h1>
@endsection

@section('content')
<div class="data-entry-container">
    <!-- Grid 2 Kartu Statistik -->
    <div class="data-entry-stats-grid">
        <!-- Kartu 1: Total Draft -->
        <a href="{{ route('faqs.index', ['status' => 'draft']) }}" class="de-stat-card blue" title="Klik untuk melihat seluruh draft">
            <div class="de-stat-content">
                <div class="de-stat-label">Total Draft</div>
                <div class="de-stat-value">{{ $totalDraft ?? 0 }}</div>
            </div>
            <div class="de-stat-icon blue">
                <!-- Icon Message Bubble with Inner Heart -->
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#687AC3" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    <path d="M12 7.8c-1-1.2-2.7-.7-2.7 1 0 1.2 1.5 2.4 2.7 3.4 1.2-1 2.7-2.2 2.7-3.4 0-1.7-1.7-2.2-2.7-1z"></path>
                </svg>
            </div>
        </a>

        <!-- Kartu 2: Menunggu Validasi -->
        <a href="{{ route('faqs.index', ['status' => 'pending']) }}" class="de-stat-card amber" title="Klik untuk melihat informasi menunggu validasi">
            <div class="de-stat-content">
                <div class="de-stat-label">Menunggu Validasi</div>
                <div class="de-stat-value">{{ $menungguValidasi ?? 0 }}</div>
            </div>
            <div class="de-stat-icon amber">
                <!-- Icon Hourglass -->
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#DDA236" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 22h14"></path>
                    <path d="M5 2h14"></path>
                    <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"></path>
                    <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"></path>
                </svg>
            </div>
        </a>

        <!-- Tombol Tambah Informasi Baru (Full Width Grid) -->
        <div class="de-action-wrapper">
            <a href="{{ route('faqs.create') }}" class="btn-add-info" style="text-decoration: none;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Informasi Baru</span>
            </a>
        </div>
    </div>
</div>
@endsection
