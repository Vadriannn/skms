@extends('layouts.app', ['title' => 'Dashboard Super Admin - SKMS'])

@section('content')
    <div class="skmk-page-intro">
        <div>
            <h1 class="skmk-page-main-title">Ruang kerja administrator</h1>
            <p class="skmk-page-subtitle">Pantau seluruh ekosistem pengetahuan, pengguna, dan aktivitas sistem.</p>
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
            <div class="skmk-hero-kicker">PUSAT KENDALI SKMS</div>
            <div class="skmk-hero-heading">Satu sumber informasi.<br>Banyak jawaban berarti.</div>
            <div class="skmk-hero-desc">Kelola pengguna, taksonomi kategori, dan kualitas informasi kampus dalam satu ruang kerja.</div>
        </div>
        <div class="skmk-hero-metrics">
            <div class="skmk-hero-metric-item">
                <div class="skmk-hero-metric-val">{{ str_pad($dipublikasikan, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="skmk-hero-metric-label">informasi terbit</div>
            </div>
            <div class="skmk-hero-metric-item">
                <div class="skmk-hero-metric-val">{{ str_pad($menungguValidasi, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="skmk-hero-metric-label">menunggu validasi</div>
            </div>
        </div>
    </div>

    <div class="skmk-stat-row">
        <a href="{{ route('users.index') }}" class="skmk-stat-box" style="text-decoration: none;">
            <div class="skmk-stat-num">{{ str_pad($totalPengguna, 2, '0', STR_PAD_LEFT) }}</div>
            <div>
                <div class="skmk-stat-meta-title">Total pengguna</div>
                <div class="skmk-stat-meta-sub">Kelola akun &amp; peran &rarr;</div>
            </div>
        </a>
        <a href="{{ route('faqs.submissions') }}" class="skmk-stat-box" style="text-decoration: none;">
            <div class="skmk-stat-num">{{ str_pad($totalPertanyaan, 2, '0', STR_PAD_LEFT) }}</div>
            <div>
                <div class="skmk-stat-meta-title">Total pertanyaan</div>
                <div class="skmk-stat-meta-sub">Seluruh status informasi &rarr;</div>
            </div>
        </a>
        <a href="{{ route('faqs.submissions', ['status' => 'pending']) }}" class="skmk-stat-box" style="text-decoration: none;">
            <div class="skmk-stat-num" style="color: #B4690E;">{{ str_pad($menungguValidasi, 2, '0', STR_PAD_LEFT) }}</div>
            <div>
                <div class="skmk-stat-meta-title">Menunggu validasi</div>
                <div class="skmk-stat-meta-sub">Dalam antrean tinjauan &rarr;</div>
            </div>
        </a>
        <a href="{{ route('faqs.index') }}" class="skmk-stat-box" style="text-decoration: none;">
            <div class="skmk-stat-num teal">{{ str_pad($dipublikasikan, 2, '0', STR_PAD_LEFT) }}</div>
            <div>
                <div class="skmk-stat-meta-title">Dipublikasikan</div>
                <div class="skmk-stat-meta-sub">Tayang untuk mahasiswa &rarr;</div>
            </div>
        </a>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="section-card">
        <div class="section-card-header">
            <h2>Aktivitas terbaru</h2>
            <a href="{{ route('faqs.history') }}">Lihat riwayat pengajuan &rarr;</a>
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
