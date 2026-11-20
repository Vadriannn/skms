@extends('layouts.app', [
    'title' => 'Antrean validasi - SKMS'
])

@section('content')
@php
    $allPending = \App\Models\Faq::with('reviews')->where('status', 'pending')->get();
    $countAllPending = $allPending->count();
    $countNewPending = $allPending->filter(fn($f) => $f->submission_type === 'new')->count();
    $countUpdatePending = $countAllPending - $countNewPending;
    $tabType = request('type', 'all');
@endphp

<div class="validator-page-container">

    <!-- Page Intro Header (Sesuai Gambar 3) -->
    <div class="skmk-page-intro">
        <div>
            <h1 class="skmk-page-main-title">Antrean validasi</h1>
            <p class="skmk-page-subtitle">Tinjau informasi masuk sebelum diterbitkan untuk mahasiswa.</p>
        </div>
        <div>
            <span style="background: #FFF4EB; color: #B4690E; border: 1px solid #F5DFB0; font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 9999px;">
                {{ $countAllPending }} perlu ditinjau
            </span>
        </div>
    </div>

    <!-- Mint Callout Banner (Sesuai Gambar 3) -->
    <div class="skmk-hero-banner" style="padding: 18px 24px;">
        <div>
            <div style="font-size: 15px; font-weight: 700; color: #16322F; margin-bottom: 2px;">Mulai dari informasi yang paling membutuhkan Anda.</div>
            <div style="font-size: 13px; color: #4D6661;">Periksa indikasi duplikasi dan pembaruan konten sebelum mengambil keputusan.</div>
        </div>
        <div>
            <a href="{{ route('validator.semua_faq') }}" class="btn-secondary" style="font-size: 12.5px; font-weight: 600; color: #16322F; padding: 8px 14px;">
                <span>Riwayat validasi</span>
                <span>&#8599;</span>
            </a>
        </div>
    </div>

    <!-- White Card Container Wrapping Tabs, Filter & Table -->
    <div class="faq-table-container" style="padding: 0; overflow: visible;">

        <!-- Top Inline Tabs (Semua antrean, Pengajuan baru, Pembaruan informasi) -->
        <div style="padding: 16px 24px 0 24px; border-bottom: 1px solid #EDF2F0; display: flex; align-items: center; gap: 22px; flex-wrap: wrap;">
            <a href="{{ route('validator.antrian', array_merge(request()->except('type', 'page'), [])) }}"
               style="padding-bottom: 12px; text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; border-bottom: 2px solid {{ $tabType === 'all' ? '#0D7F74' : 'transparent' }}; color: {{ $tabType === 'all' ? '#0D7F74' : '#647874' }};">
                <span>Semua antrean</span>
                <span style="background: {{ $tabType === 'all' ? '#E8F5F1' : '#F0F4F2' }}; color: {{ $tabType === 'all' ? '#0D7F74' : '#647874' }}; font-size: 11.5px; padding: 1px 8px; border-radius: 9999px;">{{ $countAllPending }}</span>
            </a>
            <a href="{{ route('validator.antrian', array_merge(request()->except('page'), ['type' => 'new'])) }}"
               style="padding-bottom: 12px; text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; border-bottom: 2px solid {{ $tabType === 'new' ? '#0D7F74' : 'transparent' }}; color: {{ $tabType === 'new' ? '#0D7F74' : '#647874' }};">
                <span>Pengajuan baru</span>
                <span style="background: #F0F4F2; color: #647874; font-size: 11.5px; padding: 1px 8px; border-radius: 9999px;">{{ $countNewPending }}</span>
            </a>
            <a href="{{ route('validator.antrian', array_merge(request()->except('page'), ['type' => 'update'])) }}"
               style="padding-bottom: 12px; text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; border-bottom: 2px solid {{ $tabType === 'update' ? '#0D7F74' : 'transparent' }}; color: {{ $tabType === 'update' ? '#0D7F74' : '#647874' }};">
                <span>Pembaruan / revisi</span>
                <span style="background: #F0F4F2; color: #647874; font-size: 11.5px; padding: 1px 8px; border-radius: 9999px;">{{ $countUpdatePending }}</span>
            </a>
        </div>

        <!-- Filter Bar -->
        <div style="padding: 16px 24px;">
            <form method="GET" action="{{ route('validator.antrian') }}" id="antrianFilterForm" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <div class="faq-search-wrapper" style="flex: 1; min-width: 240px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8FA19D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                <div class="faq-select-wrapper" style="width: 185px;">
                    <select name="category_id" id="antrianCategorySelect" onchange="document.getElementById('antrianFilterForm').submit()" class="faq-category-dropdown">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (string)($categoryId ?? '') === (string)$category->id ? 'selected' : '' }}>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="chevron-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#647874" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                <button type="submit" class="btn-filter-search" style="padding: 10px 20px;">Cari</button>
            </form>
        </div>

        <!-- Tabel Antrean -->
        <table class="faq-custom-table">
            <thead>
                <tr>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #647874; padding: 12px 24px;">PERTANYAAN</th>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #647874; padding: 12px 20px;">KATEGORI</th>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #647874; padding: 12px 20px;">PENGAJU</th>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #647874; padding: 12px 20px;">PERHATIAN</th>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #647874; padding: 12px 24px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $filteredFaqs = $faqs->filter(function($item) use ($tabType) {
                        if ($tabType === 'new') return $item->submission_type === 'new';
                        if ($tabType === 'update') return $item->submission_type !== 'new';
                        return true;
                    });
                @endphp
                @forelse($filteredFaqs as $faq)
                    <tr>
                        <td class="col-pertanyaan" style="padding: 16px 24px;">
                            <a href="{{ route('validator.faq.show', $faq->id) }}" class="validator-faq-link" style="font-weight: 600; font-size: 14px; color: #16322F; display: block; margin-bottom: 3px;">
                                {{ Str::limit($faq->pertanyaan, 85) }}
                            </a>
                            <div style="font-size: 11.5px; color: #8FA19D;">
                                FAQ #{{ $faq->id }}
                                @if($faq->submission_type !== 'new')
                                    &bull; Pembaruan
                                @endif
                                &bull; {{ $faq->created_at->format('d M Y') }}
                            </div>
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
                        <td style="padding: 16px 20px; font-size: 13px; color: #3D524E;">
                            {{ $faq->user?->name ?? '-' }}
                        </td>
                        <td style="padding: 16px 20px;">
                            <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                @if($faq->latestDuplicateLog)
                                    @php
                                        $dupScoreVal = (float) $faq->latestDuplicateLog->score;
                                        $dupPct = $dupScoreVal <= 1 ? round($dupScoreVal * 100) : round($dupScoreVal);
                                        $simTitle = $faq->latestDuplicateLog->similarFaq?->pertanyaan ?? 'FAQ lain';
                                    @endphp
                                    <span style="background-color: #FFF4EB; color: #B4690E; border: 1px solid #F5DFB0; font-size: 11.5px; padding: 3px 10px; border-radius: 6px; font-weight: 600;" title="Data Entry menghiraukan peringatan duplikasi TF-IDF ({{ $dupPct }}% mirip dengan: {{ $simTitle }})">
                                        Mirip {{ $dupPct }}%
                                    </span>
                                @endif

                                @if($faq->submission_type === 'edit')
                                    <span style="background-color: #EEF3FA; color: #345995; border: 1px solid #C9D8EE; font-size: 11.5px; padding: 3px 10px; border-radius: 6px; font-weight: 600;">
                                        Pembaruan
                                    </span>
                                @elseif($faq->submission_type === 'revision')
                                    <span style="background-color: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; font-size: 11.5px; padding: 3px 10px; border-radius: 6px; font-weight: 600;">
                                        Revisi {{ $faq->reviews->where('keputusan', 'revision')->count() }}/2
                                    </span>
                                @elseif(!$faq->latestDuplicateLog)
                                    <span style="background-color: #F0F4F2; color: #4D6661; border: 1px solid #E2E9E6; font-size: 11.5px; padding: 3px 10px; border-radius: 6px; font-weight: 500;">
                                        Pengajuan baru
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td style="padding: 16px 24px; text-align: right; white-space: nowrap;">
                            <a href="{{ route('validator.faq.show', $faq->id) }}" class="skmk-link-action">
                                <span>Tinjau</span>
                                <span>&rarr;</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="validator-empty-row">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#8FA19D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <p>Tidak ada FAQ yang menunggu validasi.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer Summary & Pagination -->
        <div style="padding: 14px 24px; border-top: 1px solid #EDF2F0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; font-size: 12.5px; color: #8FA19D;">
            <div>
                @if($faqs->total() > 0)
                    Menampilkan {{ $faqs->firstItem() }}–{{ $faqs->lastItem() }} dari {{ $faqs->total() }} pengajuan
                @else
                    Menampilkan 0 pengajuan
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
