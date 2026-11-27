@extends('layouts.app', [
    'title' => 'FAQ Tidak Membantu - SKMS'
])

@section('content')
@php
    $unhelpfulTotal = $faqs->total();
    $totalNotHelpfulVotes = \App\Models\Faq::unhelpful()->sum('not_helpful');
    $totalHelpfulVotes = \App\Models\Faq::unhelpful()->sum('helpful');
@endphp

<div class="faq-page-container">

    <!-- Page Intro Header -->
    <div class="skmk-page-intro">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                <span class="badge" style="background: #FDF2F2; color: #C93535; border: 1px solid #F5C6C6; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                    Ambang Batas &lt; 70%
                </span>
            </div>
            <h1 class="skmk-page-main-title">Daftar FAQ Tidak Membantu</h1>
            <p class="skmk-page-subtitle">Daftar informasi FAQ yang dinilai kurang membantu oleh mahasiswa dan memerlukan peninjauan kembali.</p>
        </div>
        <div>
            @if(Auth::check() && in_array(Auth::user()->role, ['data_entry', 'super_admin']))
                <a href="{{ route('faqs.submissions') }}" class="btn-secondary" style="font-weight: 600; color: #1F1415;">
                    <span>Lihat pengajuan &amp; draf</span>
                    <span>&rarr;</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Alert Penjelasan Ambang Batas 70% / 30% -->
    <div style="background: #FFF8F0; border: 1px solid #FAD7A0; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 14px;">
        <div style="width: 32px; height: 32px; border-radius: 8px; background: #FEF08A; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #B45309;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <div style="flex: 1; font-size: 13px; color: #78350F; line-height: 1.55;">
            <strong>Standar Kualitas Umpan Balik Mahasiswa:</strong>
            Sebuah FAQ dikategorikan <strong>"Membantu"</strong> jika tingkat respon positifnya mencapai <strong>&ge; 70%</strong>. 
            Informasi di bawah ini memenuhi ambang batas <strong>kurang membantu (&lt; 70% Membantu atau &gt; 30% Tidak Membantu)</strong> berdasarkan klik langsung mahasiswa pada tombol respon di portal SKMS. Silakan tinjau dan perbaiki penjelasan agar lebih jelas dan solutif.
        </div>
    </div>

    <!-- Hero Banner Metrik Evaluasi -->
    <div class="skmk-hero-banner" style="background: linear-gradient(135deg, #FFF1F2 0%, #FFE4E6 100%); border-color: #FECDD3;">
        <div>
            <div class="skmk-hero-kicker" style="color: #9F1239;">EVALUASI KUALITAS PENGETAHUAN</div>
            <div class="skmk-hero-heading" style="color: #4C0519;">Perbaiki informasi.<br>Tingkatkan kepuasan mahasiswa.</div>
        </div>
        <div class="skmk-hero-metrics">
            <div class="skmk-hero-metric-item" style="border-left-color: rgba(159, 18, 57, 0.2);">
                <div class="skmk-hero-metric-val" style="color: #E11D48;">{{ str_pad($unhelpfulTotal, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="skmk-hero-metric-label" style="color: #9F1239;">FAQ perlu evaluasi</div>
            </div>
            <div class="skmk-hero-metric-item" style="border-left-color: rgba(159, 18, 57, 0.2);">
                <div class="skmk-hero-metric-val" style="color: #9F1239;">{{ $totalNotHelpfulVotes }}</div>
                <div class="skmk-hero-metric-label" style="color: #9F1239;">vote tidak membantu</div>
            </div>
        </div>
    </div>

    <!-- White Card Wrapping Filter & Table -->
    <div class="faq-table-container" style="padding: 0; overflow: visible;">

        <!-- Card Top Header -->
        <div style="padding: 20px 24px 16px 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #1F1415; margin: 0;">FAQ Kurang Membantu</h2>
                <span style="background: #FDF2F2; color: #C93535; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 9999px;">
                    {{ $faqs->total() }} informasi
                </span>
            </div>
            <div style="font-size: 12.5px; color: #948486;">
                Patokan ambang batas: Membantu &lt; 70%
            </div>
        </div>

        <!-- Filter Bar Inside Card -->
        <div style="padding: 0 24px 18px 24px;">
            <form method="GET" action="{{ route('faqs.unhelpful') }}" id="filterForm" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <input type="hidden" name="per_page" id="perPageInput" value="{{ $perPage ?? 10 }}">

                <div class="faq-search-wrapper" style="flex: 1; min-width: 240px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#948486" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text"
                           name="search"
                           id="searchInput"
                           value="{{ request('search') }}"
                           placeholder="Cari pertanyaan FAQ yang perlu dievaluasi..."
                           class="faq-search-field"
                           autocomplete="off">
                </div>

                <div class="faq-select-wrapper" style="width: 190px;">
                    <select name="category_id" id="categorySelect" onchange="document.getElementById('filterForm').submit()" class="faq-category-dropdown">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (string)request('category_id') === (string)$category->id ? 'selected' : '' }}>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="chevron-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6B5B5D" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>

                <a href="{{ route('faqs.unhelpful') }}" class="btn-secondary" style="padding: 10px 16px; font-size: 13px; font-weight: 600; color: #1F1415;">
                    Atur ulang
                </a>
            </form>
        </div>

        <!-- Table -->
        <table class="faq-custom-table">
            <thead>
                <tr>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 24px;">PERTANYAAN</th>
                    <th style="width: 200px; font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 20px;">KATEGORI</th>
                    <th style="width: 140px; font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 16px; text-align: center;">VOTE MAHASISWA</th>
                    <th style="width: 190px; font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 16px; text-align: center;">PROPORSI KEPUASAN</th>
                    <th style="width: 150px; font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 16px; text-align: center;">STATUS</th>
                    <th style="width: 140px; font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 24px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $item)
                    @php
                        $helpfulPercent = $item->helpful_percentage;
                        $notHelpfulPercent = $item->not_helpful_percentage;
                    @endphp
                    <tr>
                        <td class="col-pertanyaan" style="padding: 16px 24px;">
                            <a href="{{ route('faqs.show', ['faq' => $item->id, 'from' => 'unhelpful']) }}" class="faq-title-text" style="text-decoration: none; font-weight: 600; color: #1F1415;">
                                {{ Str::limit($item->pertanyaan, 110) }}
                            </a>
                            <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 3px;">
                                Dilihat {{ number_format($item->views) }} kali &bull; Terbit {{ $item->created_at->format('d M Y') }}
                            </div>
                        </td>
                        <td class="col-kategori" style="padding: 16px 20px;">
                            <div class="category-pills-container">
                                @forelse($item->categories as $cat)
                                    <span class="category-pill">{{ $cat->nama }}</span>
                                @empty
                                    <span class="category-pill-empty">-</span>
                                @endforelse
                            </div>
                        </td>
                        <td style="padding: 16px 16px; text-align: center; font-size: 13px;">
                            <div style="display: inline-flex; align-items: center; gap: 8px;">
                                <span style="background: #E8F5F1; color: #0D7F74; padding: 2px 7px; border-radius: 6px; font-weight: 700; font-size: 12px;" title="Ya, Membantu">
                                    👍 {{ $item->helpful ?? 0 }}
                                </span>
                                <span style="background: #FDF2F2; color: #C93535; padding: 2px 7px; border-radius: 6px; font-weight: 700; font-size: 12px;" title="Tidak Membantu">
                                    👎 {{ $item->not_helpful ?? 0 }}
                                </span>
                            </div>
                            <div style="font-size: 11px; color: #948486; margin-top: 4px;">
                                Total: {{ $item->total_feedback }} respon
                            </div>
                        </td>
                        <td style="padding: 16px 16px; text-align: center;">
                            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 11.5px; font-weight: 700; margin-bottom: 4px;">
                                <span style="color: #0D7F74;">{{ $helpfulPercent }}%</span>
                                <span style="color: #C93535;">{{ $notHelpfulPercent }}%</span>
                            </div>
                            <div style="background: #FEE2E2; height: 8px; border-radius: 999px; overflow: hidden; display: flex; width: 100%;">
                                <div style="background: #10B981; width: {{ $helpfulPercent }}%; height: 100%;"></div>
                                <div style="background: #EF4444; width: {{ $notHelpfulPercent }}%; height: 100%;"></div>
                            </div>
                            <div style="font-size: 10.5px; color: #948486; margin-top: 3px; display: flex; justify-content: space-between;">
                                <span>Membantu</span>
                                <span>Tidak</span>
                            </div>
                        </td>
                        <td style="padding: 16px 16px; text-align: center;">
                            <span class="badge" style="background: #FDF2F2; color: #C93535; border: 1px solid #F5C6C6; font-size: 11px; font-weight: 700; padding: 4px 8px; white-space: nowrap;">
                                &lt; 70% Membantu
                            </span>
                        </td>
                        <td style="padding: 16px 24px; text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                @if(Auth::check() && in_array(Auth::user()->role, ['data_entry', 'super_admin']))
                                    <a href="{{ route('faqs.edit', $item->id) }}" class="btn-primary" style="padding: 5px 10px; font-size: 12px; font-weight: 600; text-decoration: none; border-radius: 6px;" title="Revisi / Edit FAQ ini">
                                        Perbaiki
                                    </a>
                                @endif
                                <a href="{{ route('faqs.show', ['faq' => $item->id, 'from' => 'unhelpful']) }}" class="btn-secondary" style="padding: 5px 8px; font-size: 12px; font-weight: 600; text-decoration: none; border-radius: 6px;">
                                    Lihat &rarr;
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 50px 24px; color: #948486;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                                <div style="width: 48px; height: 48px; border-radius: 12px; background: #E8F5F1; display: flex; align-items: center; justify-content: center; color: #0D7F74;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </div>
                                <div style="font-weight: 700; font-size: 15px; color: #1F1415;">
                                    Tidak Ada FAQ yang Kurang Membantu
                                </div>
                                <div style="font-size: 13px; color: #6B5B5D; max-width: 420px; line-height: 1.5;">
                                    Hebat! Seluruh FAQ yang memiliki umpan balik dari mahasiswa telah memenuhi standar kepuasan (&ge; 70% Membantu).
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Card Footer: Summary & Pagination -->
        <div style="padding: 14px 24px; border-top: 1px solid #F3ECEC; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; font-size: 12.5px; color: #948486;">
            <div>
                @if($faqs->total() > 0)
                    Menampilkan {{ $faqs->firstItem() }}–{{ $faqs->lastItem() }} dari {{ $faqs->total() }} informasi kurang membantu
                @else
                    0 informasi
                @endif
            </div>

            <!-- Custom Clean Pagination -->
            @if($faqs->hasPages())
                <div class="faq-pagination-custom">
                    {{-- Tombol Sebelumnya --}}
                    @if($faqs->onFirstPage())
                        <span class="faq-page-link disabled">&laquo;</span>
                    @else
                        <a href="{{ $faqs->previousPageUrl() }}" class="faq-page-link">&laquo;</a>
                    @endif

                    {{-- Elemen Nomor Halaman --}}
                    @foreach($faqs->getUrlRange(1, $faqs->lastPage()) as $page => $url)
                        @if($page == $faqs->currentPage())
                            <span class="faq-page-link active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="faq-page-link">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Tombol Selanjutnya --}}
                    @if($faqs->hasMorePages())
                        <a href="{{ $faqs->nextPageUrl() }}" class="faq-page-link">&raquo;</a>
                    @else
                        <span class="faq-page-link disabled">&raquo;</span>
                    @endif
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
