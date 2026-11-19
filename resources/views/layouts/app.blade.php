<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SKMS - Student Knowledge Management System' }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>

    <div class="app-container">
        <!-- SIDEBAR -->
        <aside class="app-sidebar">
            <div>
                <!-- Brand Header (SKMK Dual-Tone Book Logo) -->
                <div class="sidebar-header" style="padding: 22px 20px 18px 20px;">
                    <svg width="34" height="34" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" style="flex-shrink: 0;">
                        <path d="M4 7.5C4 6.11929 5.11929 5 6.5 5C10.5 5 14.5 6.5 17.5 9V14.5L21 17.5L17.5 20.5V29.5C14.5 27 10.5 25.5 6.5 25.5C5.11929 25.5 4 24.3807 4 23V7.5Z" fill="#0D7F74"/>
                        <path d="M32 7.5C32 6.11929 30.8807 5 29.5 5C25.5 5 21.5 6.5 18.5 9V14L22 17.5L18.5 21V29.5C21.5 27 25.5 25.5 29.5 25.5C30.8807 25.5 32 24.3807 32 23V7.5Z" fill="#8ED8C3"/>
                    </svg>
                    <div style="display: flex; flex-direction: column; line-height: 1.15;">
                        <span class="brand-title" style="font-size: 19px; font-weight: 800; color: #16322F; letter-spacing: -0.4px;">SKMS</span>
                        <span style="font-size: 11px; color: #647874; font-weight: 500; margin-top: 2px;">Knowledge, connected.</span>
                    </div>
                </div>

                <div style="padding: 4px 24px 6px 24px; font-size: 10.5px; font-weight: 700; color: #8FA19D; letter-spacing: 0.8px; text-transform: uppercase;">
                    RUANG KERJA
                </div>

                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ Auth::check() && Auth::user()->role === 'validator' ? route('validator.dashboard') : route('dashboard') }}" class="sidebar-link {{ (request()->routeIs('dashboard*') || request()->routeIs('validator.dashboard')) ? 'active' : '' }}">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @if(Auth::check() && Auth::user()->role === 'super_admin')
                    <li>
                        <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Kelola pengguna</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            <span>Kategori</span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::check() && Auth::user()->role === 'validator')
                    {{-- Menu khusus Validator --}}
                    <li>
                        <a href="{{ route('faqs.index') }}" class="sidebar-link {{ request()->routeIs('faqs.index') || request()->routeIs('validator.disetujui') ? 'active' : '' }}">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                            <span>Daftar informasi</span>
                        </a>
                    </li>
                    <li>
                        @php $pendingCount = \App\Models\Faq::where('status','pending')->count(); @endphp
                        <a href="{{ route('validator.antrian') }}" class="sidebar-link {{ request()->routeIs('validator.antrian') || request()->routeIs('validator.faq.*') ? 'active' : '' }}">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M8 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                <line x1="9" y1="12" x2="15" y2="12"></line>
                                <line x1="9" y1="16" x2="13" y2="16"></line>
                            </svg>
                            <span>Antrean validasi</span>
                            @if($pendingCount > 0)
                                <span class="sidebar-badge">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('validator.semua_faq') }}" class="sidebar-link {{ request()->routeIs('validator.semua_faq') ? 'active' : '' }}">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="1 4 1 10 7 10"></polyline>
                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                                <polyline points="12 7 12 12 15 15"></polyline>
                            </svg>
                            <span>Riwayat validasi</span>
                        </a>
                    </li>
                    @else
                    <li>
                        <a href="{{ route('faqs.index') }}" class="sidebar-link {{ request()->routeIs('faqs.index') ? 'active' : '' }}">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                            <span>Daftar informasi</span>
                        </a>
                    </li>
                    @if(Auth::check() && in_array(Auth::user()->role, ['data_entry', 'super_admin']))
                    <li>
                        @php 
                            $revisiCount = \App\Models\Faq::where('status', 'rejected')->count(); 
                        @endphp
                        <a href="{{ route('faqs.submissions') }}" class="sidebar-link {{ request()->routeIs('faqs.submissions') || request()->routeIs('faqs.create') || request()->routeIs('faqs.edit') || request()->routeIs('faqs.show') ? 'active' : '' }}">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <span>Pengajuan &amp; draf</span>
                            @if($revisiCount > 0)
                                <span class="sidebar-badge" style="background-color: #FDF2F2; color: #C93535;" title="{{ $revisiCount }} FAQ perlu revisi">{{ $revisiCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('faqs.history') }}" class="sidebar-link {{ request()->routeIs('faqs.history') ? 'active' : '' }}">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="1 4 1 10 7 10"></polyline>
                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                                <polyline points="12 7 12 12 15 15"></polyline>
                            </svg>
                            <span>Riwayat pengajuan</span>
                        </a>
                    </li>
                    @endif
                    @endif
                    <li>
                        <a href="{{ route('settings.profile') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            <span>Pengaturan</span>
                        </a>
                    </li>
                </ul>

            </div>

            <!-- Footer: Tagline & Keluar akun -->
            <div>
                <div style="padding: 0 20px 14px 20px; font-size: 11.5px; color: #8FA19D; line-height: 1.45;">
                    Pengetahuan yang lebih baik,<br>dimulai dari informasi tepercaya.
                </div>
                <div class="sidebar-footer">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="sidebar-logout-btn">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Keluar akun</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- MAIN SECTION -->
        <main class="app-main">
            <!-- Topbar Breadcrumb & Profil Pengguna (Sesuai Mockup SKMK) -->
            <header class="app-topbar" style="min-height: 58px; background: #FFFFFF; border-bottom: 1px solid var(--border-color); padding: 0 36px; position: sticky; top: 0; z-index: 30;">
                <div class="topbar-left" style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted);">
                    <span>Ruang kerja</span>
                    <span style="color: var(--text-subtle);">&rsaquo;</span>
                    <span style="color: var(--text-heading); font-weight: 600;">
                        @php
                            $crumbTitle = 'Dashboard';
                            if (request()->routeIs('faqs.index')) $crumbTitle = 'Daftar informasi';
                            elseif (request()->routeIs('faqs.submissions')) $crumbTitle = 'Pengajuan & draf';
                            elseif (request()->routeIs('faqs.create')) $crumbTitle = 'Tambah informasi';
                            elseif (request()->routeIs('faqs.edit')) $crumbTitle = 'Edit informasi';
                            elseif (request()->routeIs('faqs.show')) $crumbTitle = 'Detail FAQ';
                            elseif (request()->routeIs('faqs.history')) $crumbTitle = 'Riwayat pengajuan';
                            elseif (request()->routeIs('validator.antrian')) $crumbTitle = 'Antrean validasi';
                            elseif (request()->routeIs('validator.faq.*')) $crumbTitle = 'Detail FAQ';
                            elseif (request()->routeIs('validator.semua_faq')) $crumbTitle = 'Riwayat validasi';
                            elseif (request()->routeIs('users.*')) $crumbTitle = 'Kelola pengguna';
                            elseif (request()->routeIs('categories.*')) $crumbTitle = 'Kategori';
                            elseif (request()->routeIs('settings.*')) $crumbTitle = 'Pengaturan';
                        @endphp
                        {{ $crumbTitle }}
                    </span>
                </div>
                <div class="topbar-right" style="display: flex; align-items: center; gap: 18px;">
                    @auth
                        @php
                            $user = Auth::user();
                            $roleLabels = [
                                'super_admin' => 'Administrator utama',
                                'validator'   => 'Pengelola pengetahuan',
                                'data_entry'  => 'Penyusun informasi',
                            ];
                        @endphp
                        <a href="{{ route('skms.beranda') }}" target="_blank" title="Buka Portal Mahasiswa" style="color: var(--text-muted); display: inline-flex; align-items: center; gap: 5px; font-size: 12.5px; text-decoration: none; padding: 5px 10px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-page);">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            <span>Portal Mahasiswa</span>
                        </a>
                        <a href="{{ route('settings.profile') }}" class="user-profile-badge" style="text-decoration: none; gap: 10px;">
                            <div class="user-info-text" style="text-align: right;">
                                <div class="user-info-name" style="font-size: 13px; font-weight: 700; color: var(--text-heading);">{{ $user->name }}</div>
                                <div class="user-info-role" style="font-size: 11.5px; color: var(--text-muted);">{{ $roleLabels[$user->role] ?? ucfirst($user->role) }}</div>
                            </div>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#8FA19D" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    @endauth
                </div>
            </header>

            <!-- Page Header Optional Hook -->
            @hasSection('topbar_left')
                <div style="padding: 24px 36px 0 36px; max-width: 1200px;">
                    @yield('topbar_left')
                </div>
            @endif

            <!-- Content Body -->
            <div class="content-body" style="padding: 22px 36px 40px 36px;">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
