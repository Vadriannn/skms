@extends('layouts.app', [
    'title' => 'Pusat informasi kampus - SKMS'
])

@section('content')
@php
    $pendingTotal = \App\Models\Faq::where('status', 'pending')->count();
    $publishedTotal = \App\Models\Faq::where('status', 'published')->count();
    $lastUpdatedFaq = \App\Models\Faq::where('status', 'published')->latest('updated_at')->first();
@endphp

<div class="faq-page-container">

    <!-- Page Intro Header -->
    <div class="skmk-page-intro">
        <div>
            <h1 class="skmk-page-main-title">Pusat informasi kampus</h1>
            <p class="skmk-page-subtitle">Kelola jawaban tepercaya untuk setiap pertanyaan mahasiswa.</p>
        </div>
        <div>
            @if(Auth::check() && Auth::user()->role === 'validator')
                <a href="{{ route('validator.antrian') }}" class="btn-secondary" style="font-weight: 600; color: #16322F;">
                    <span>Lihat antrean</span>
                    <span>&rarr;</span>
                </a>
            @elseif(Auth::check() && in_array(Auth::user()->role, ['data_entry', 'super_admin']))
                <a href="{{ route('faqs.create') }}" class="btn-tambah-faq">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span>Tambah informasi baru</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Hero Banner Mint (Sesuai Gambar 2) -->
    <div class="skmk-hero-banner">
        <div>
            <div class="skmk-hero-kicker">RUANG PENGETAHUAN SKMS</div>
            <div class="skmk-hero-heading">Satu sumber informasi.<br>Banyak jawaban berarti.</div>
            <div class="skmk-hero-desc">Informasi yang sudah divalidasi, siap membantu mahasiswa.</div>
        </div>
        <div class="skmk-hero-metrics">
            <div class="skmk-hero-metric-item">
                <div class="skmk-hero-metric-val">{{ str_pad($publishedTotal, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="skmk-hero-metric-label">informasi terbit</div>
            </div>
            <div class="skmk-hero-metric-item">
                <div class="skmk-hero-metric-val">{{ str_pad($pendingTotal, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="skmk-hero-metric-label">menunggu validasi</div>
            </div>
        </div>
    </div>

    <!-- White Card Wrapping Filter & Table (Sesuai Gambar 2) -->
    <div class="faq-table-container" style="padding: 0; overflow: visible;">

        <!-- Card Top Header -->
        <div style="padding: 20px 24px 16px 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #16322F; margin: 0;">Daftar informasi</h2>
                <span style="background: #E8F5F1; color: #0D7F74; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 9999px;">
                    {{ $faqs->total() }} informasi
                </span>
            </div>
            <div style="font-size: 12.5px; color: #8FA19D;">
                @if($lastUpdatedFaq)
                    Terakhir diperbarui {{ $lastUpdatedFaq->updated_at->format('d M Y') }}
                @endif
            </div>
        </div>

        <!-- Filter Bar Inside Card -->
        <div style="padding: 0 24px 18px 24px;">
            <form method="GET" action="{{ route('faqs.index') }}" id="filterForm" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <input type="hidden" name="per_page" id="perPageInput" value="{{ $perPage ?? 10 }}">

                <div class="faq-search-wrapper" style="flex: 1; min-width: 240px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8FA19D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text"
                           name="search"
                           id="searchInput"
                           value="{{ request('search') }}"
                           placeholder="Cari pertanyaan atau kata kunci..."
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
                    <svg class="chevron-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#647874" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>

                <a href="{{ route('faqs.index') }}" class="btn-secondary" style="padding: 10px 16px; font-size: 13px; font-weight: 600; color: #16322F;">
                    Atur ulang
                </a>
            </form>
        </div>

        <!-- Table -->
        <table class="faq-custom-table">
            <thead>
                <tr>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #647874; padding: 12px 24px;">PERTANYAAN</th>
                    <th style="width: 240px; font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #647874; padding: 12px 20px;">KATEGORI</th>
                    <th style="width: 110px; font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #647874; padding: 12px 16px; text-align: center;">DILIHAT</th>
                    <th style="width: 115px; font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #647874; padding: 12px 16px; text-align: center;">MEMBANTU</th>
                    <th style="width: 100px; font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #647874; padding: 12px 24px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $item)
                    <tr>
                        <td class="col-pertanyaan" style="padding: 16px 24px;">
                            <a href="{{ route('faqs.show', ['faq' => $item->id, 'from' => 'index']) }}" class="faq-title-text" style="text-decoration: none; font-weight: 600; color: #16322F;">
                                {{ Str::limit($item->pertanyaan, 110) }}
                            </a>
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
                        <td style="padding: 16px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #3D524E; white-space: nowrap;">
                            {{ number_format($item->views ?? 0) }}
                        </td>
                        <td style="padding: 16px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #0D7F74; white-space: nowrap;">
                            {{ number_format($item->helpful ?? 0) }}
                        </td>
                        <td style="padding: 16px 24px; text-align: right; white-space: nowrap;">
                            <a href="{{ route('faqs.show', ['faq' => $item->id, 'from' => 'index']) }}" class="skmk-link-action">
                                <span>Lihat</span>
                                <span>&rarr;</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 44px 24px; color: #8FA19D;">
                            Tidak ada informasi ditemukan untuk kata kunci atau kategori yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Card Footer: Summary & Pagination -->
        <div style="padding: 14px 24px; border-top: 1px solid #EDF2F0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; font-size: 12.5px; color: #8FA19D;">
            <div>
                @if($faqs->total() > 0)
                    Menampilkan {{ $faqs->firstItem() }}–{{ $faqs->lastItem() }} dari {{ $faqs->total() }} informasi
                @else
                    Menampilkan 0 informasi
                @endif
            </div>

            <div style="display: flex; align-items: center; gap: 6px;">
                @if($faqs->onFirstPage())
                    <span class="page-nav disabled" style="min-width: 28px; height: 28px; font-size: 14px;">&lsaquo;</span>
                @else
                    <a href="{{ $faqs->previousPageUrl() }}" class="page-nav" style="min-width: 28px; height: 28px; font-size: 14px; color: #647874;">&lsaquo;</a>
                @endif

                @foreach(range(1, max(1, $faqs->lastPage())) as $page)
                    @if($page == $faqs->currentPage())
                        <span class="page-number active" style="min-width: 28px; height: 28px; font-size: 12.5px;">{{ $page }}</span>
                    @else
                        <a href="{{ $faqs->url($page) }}" class="page-number" style="min-width: 28px; height: 28px; font-size: 12.5px;">{{ $page }}</a>
                    @endif
                @endforeach

                @if($faqs->hasMorePages())
                    <a href="{{ $faqs->nextPageUrl() }}" class="page-nav" style="min-width: 28px; height: 28px; font-size: 14px; color: #647874;">&rsaquo;</a>
                @else
                    <span class="page-nav disabled" style="min-width: 28px; height: 28px; font-size: 14px;">&rsaquo;</span>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
