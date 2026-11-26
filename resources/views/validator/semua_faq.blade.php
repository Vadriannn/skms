@extends('layouts.app', [
    'title' => 'Riwayat validasi - SKMS'
])

@section('content')
@php
    $totalKeputusan = \App\Models\Faq::whereIn('status', ['published', 'rejected', 'inactive'])->count();
    $totalDisetujui = \App\Models\Faq::where('status', 'published')->count();
    $totalNonaktif  = \App\Models\Faq::where('status', 'inactive')->count();
@endphp

<div class="validator-page-container">

    <!-- Page Intro Header (Sesuai Gambar 5) -->
    <div class="skmk-page-intro">
        <div>
            <h1 class="skmk-page-main-title">Riwayat validasi</h1>
            <p class="skmk-page-subtitle">Jejak keputusan yang transparan, tersimpan dalam satu tempat.</p>
        </div>
        <div>
            <span style="background: #FDF2F3; color: #8B0D18; font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 9999px;">
                Arsip keputusan
            </span>
        </div>
    </div>

    <!-- 3 Stat Boxes (Sesuai Gambar 5) -->
    <div class="skmk-stat-row">
        <div class="skmk-stat-box">
            <div class="skmk-stat-num">{{ str_pad($totalKeputusan, 2, '0', STR_PAD_LEFT) }}</div>
            <div>
                <div class="skmk-stat-meta-title">Total keputusan</div>
                <div class="skmk-stat-meta-sub">Berdasarkan daftar saat ini</div>
            </div>
        </div>
        <div class="skmk-stat-box">
            <div class="skmk-stat-num teal">{{ str_pad($totalDisetujui, 2, '0', STR_PAD_LEFT) }}</div>
            <div>
                <div class="skmk-stat-meta-title">Disetujui</div>
                <div class="skmk-stat-meta-sub">Berdasarkan daftar saat ini</div>
            </div>
        </div>
        <div class="skmk-stat-box">
            <div class="skmk-stat-num muted">{{ str_pad($totalNonaktif, 2, '0', STR_PAD_LEFT) }}</div>
            <div>
                <div class="skmk-stat-meta-title">Nonaktif</div>
                <div class="skmk-stat-meta-sub">Berdasarkan daftar saat ini</div>
            </div>
        </div>
    </div>

    <!-- White Card Wrapping Header, Filters, Table, and Footer -->
    <div class="faq-table-container" style="padding: 0; overflow: visible;">

        <!-- Top Card Header -->
        <div style="padding: 18px 24px 12px 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 13.5px; font-weight: 700; color: #8B0D18;">Semua riwayat</span>
                <span style="background: #FDF2F3; color: #8B0D18; font-size: 11.5px; font-weight: 700; padding: 1px 8px; border-radius: 9999px;">
                    {{ $faqs->total() }}
                </span>
            </div>
            <div style="font-size: 12px; color: #948486;">
                Keputusan terbaru lebih dulu
            </div>
        </div>

        <!-- Filter Bar -->
        <div style="padding: 0 24px 16px 24px;">
            <form method="GET" action="{{ route('validator.semua_faq') }}" id="semuaFaqFilterForm" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <div class="faq-search-wrapper" style="flex: 1; min-width: 220px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#948486" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ $search ?? '' }}"
                           placeholder="Cari pertanyaan..."
                           class="faq-search-field"
                           autocomplete="off">
                </div>
                <div class="faq-select-wrapper" style="width: 170px;">
                    <select name="scope" id="scopeSelect" onchange="document.getElementById('semuaFaqFilterForm').submit()" class="faq-category-dropdown">
                        <option value="all"  {{ ($scope ?? 'all') === 'all'  ? 'selected' : '' }}>Semua validator</option>
                        <option value="mine" {{ ($scope ?? 'all') === 'mine' ? 'selected' : '' }}>Validasi saya saja</option>
                    </select>
                    <svg class="chevron-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6B5B5D" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                <div class="faq-select-wrapper" style="width: 165px;">
                    <select name="status" id="statusSelect" onchange="document.getElementById('semuaFaqFilterForm').submit()" class="faq-category-dropdown">
                        <option value="">Semua status</option>
                        <option value="published" {{ ($status ?? '') === 'published' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected"  {{ ($status ?? '') === 'rejected'  ? 'selected' : '' }}>Ditolak</option>
                        <option value="inactive"  {{ ($status ?? '') === 'inactive'  ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <svg class="chevron-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6B5B5D" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                <div class="faq-select-wrapper" style="width: 175px;">
                    <select name="category_id" id="categorySelectSemua" onchange="document.getElementById('semuaFaqFilterForm').submit()" class="faq-category-dropdown">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (string)($categoryId ?? '') === (string)$category->id ? 'selected' : '' }}>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="chevron-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6B5B5D" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                <button type="submit" class="btn-filter-search" style="padding: 10px 20px;">Cari</button>
            </form>
        </div>

        <!-- Table (Sesuai Gambar 5) -->
        <table class="faq-custom-table">
            <thead>
                <tr>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 24px;">PERTANYAAN</th>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 20px;">KATEGORI</th>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 20px;">PENGAJU / VALIDATOR</th>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 20px;">STATUS</th>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 24px;">TANGGAL</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                    <tr>
                        <td class="col-pertanyaan" style="padding: 16px 24px;">
                            <a href="{{ route('validator.faq.show', $faq->id) }}" class="validator-faq-link" style="font-weight: 600; font-size: 14px; color: #1F1415;">
                                {{ Str::limit($faq->pertanyaan, 85) }}
                            </a>
                        </td>
                        <td class="col-kategori" style="padding: 16px 20px;">
                            <div class="category-pills-container">
                                @forelse($faq->categories as $cat)
                                    <span class="category-pill">{{ $cat->nama }}</span>
                                @empty
                                    <span class="category-pill-empty">-</span>
                                @endforelse
                            </div>
                        </td>
                        <td style="padding: 16px 20px; font-size: 12.5px; line-height: 1.45;">
                            <div style="color: #3E2F30; font-weight: 500;">{{ $faq->user?->name ?? '-' }}</div>
                            @php
                                $lastValidator = $faq->latestReview?->user;
                                $isCurrentValidator = $lastValidator && $lastValidator->id === Auth::id();
                            @endphp
                            <div style="color: #8B0D18; font-size: 11.5px; font-weight: 600;">
                                Validator: {{ $lastValidator ? $lastValidator->name : '—' }}
                                @if($isCurrentValidator)
                                    &bull; Anda
                                @endif
                            </div>
                        </td>
                        <td style="padding: 16px 20px;">
                            @if($faq->status === 'published')
                                <span style="background: #FDF2F3; color: #8B0D18; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">Disetujui</span>
                            @elseif($faq->status === 'rejected')
                                <span style="background: #FDF2F2; color: #C93535; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">Ditolak</span>
                            @elseif($faq->status === 'inactive')
                                <span style="background: #F6F0F0; color: #6B5B5D; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">Nonaktif</span>
                            @endif
                        </td>
                        <td style="padding: 16px 24px; font-size: 12px; line-height: 1.45; white-space: nowrap;">
                            <div style="color: #6B5B5D;">{{ $faq->created_at->format('d M Y') }}</div>
                            @if($faq->status === 'inactive' && $faq->deactivated_at)
                                <div style="color: #B4690E; font-size: 11.5px;">Nonaktif: {{ $faq->deactivated_at->format('d M Y') }}</div>
                            @endif
                            <a href="{{ route('validator.faq.show', $faq->id) }}" class="skmk-link-action" style="font-size: 12px; margin-top: 2px;">
                                <span>Lihat detail</span>
                                <span>&rarr;</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="validator-empty-row">
                            <p>Tidak ada riwayat validasi FAQ ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer Summary & Pagination -->
        <div style="padding: 14px 24px; border-top: 1px solid #F3ECEC; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; font-size: 12.5px; color: #948486;">
            <div>
                @if($faqs->total() > 0)
                    Menampilkan {{ $faqs->firstItem() }}–{{ $faqs->lastItem() }} dari {{ $faqs->total() }} riwayat
                @else
                    Menampilkan 0 riwayat
                @endif
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                @if($faqs->onFirstPage())
                    <span class="page-nav disabled" style="min-width: 28px; height: 28px; font-size: 14px;">&lsaquo;</span>
                @else
                    <a href="{{ $faqs->previousPageUrl() }}" class="page-nav" style="min-width: 28px; height: 28px; font-size: 14px; color: #6B5B5D;">&lsaquo;</a>
                @endif
                @foreach(range(1, max(1, $faqs->lastPage())) as $page)
                    @if($page == $faqs->currentPage())
                        <span class="page-number active" style="min-width: 28px; height: 28px; font-size: 12.5px;">{{ $page }}</span>
                    @else
                        <a href="{{ $faqs->url($page) }}" class="page-number" style="min-width: 28px; height: 28px; font-size: 12.5px;">{{ $page }}</a>
                    @endif
                @endforeach
                @if($faqs->hasMorePages())
                    <a href="{{ $faqs->nextPageUrl() }}" class="page-nav" style="min-width: 28px; height: 28px; font-size: 14px; color: #6B5B5D;">&rsaquo;</a>
                @else
                    <span class="page-nav disabled" style="min-width: 28px; height: 28px; font-size: 14px;">&rsaquo;</span>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
