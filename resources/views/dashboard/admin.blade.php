@extends('layouts.app', ['title' => 'Dashboard Super Admin - SKMS'])

@section('content')
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
    </div>

    <!-- 4 Cards Statistik -->
    <div class="stats-grid">
        <!-- 1. Total Pengguna -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Total Pengguna</div>
                <div class="stat-value">{{ $totalPengguna }}</div>
            </div>
            <div class="stat-icon blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
        </div>

        <!-- 2. Total Pertanyaan -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Total Pertanyaan</div>
                <div class="stat-value">{{ $totalPertanyaan }}</div>
            </div>
            <div class="stat-icon indigo">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
            </div>
        </div>

        <!-- 3. Menunggu Validasi -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Menunggu Validasi</div>
                <div class="stat-value">{{ $menungguValidasi }}</div>
            </div>
            <div class="stat-icon amber">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 22h14"></path>
                    <path d="M5 2h14"></path>
                    <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"></path>
                    <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"></path>
                </svg>
            </div>
        </div>

        <!-- 4. Dipublikasikan -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Dipublikasikan</div>
                <div class="stat-value">{{ $dipublikasikan }}</div>
            </div>
            <div class="stat-icon emerald">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="section-card">
        <div class="section-card-header">
            <h2>Aktivitas Terbaru</h2>
            <a href="javascript:void(0)" onclick="alert('Menampilkan seluruh riwayat aktivitas sistem.')">Lihat Semua</a>
        </div>
        <ul class="activity-list">
            @forelse($aktivitasTerbaru as $log)
                <li class="activity-item">
                    <div class="activity-left">
                        <svg class="activity-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span class="activity-title">{{ $log->aktivitas }}</span>
                    </div>
                    <span class="activity-time">{{ $log->created_at->diffForHumans() }}</span>
                </li>
            @empty
                <li class="activity-item" style="color: var(--text-muted); justify-content: center;">
                    Belum ada aktivitas tercatat.
                </li>
            @endforelse
        </ul>
    </div>
@endsection
