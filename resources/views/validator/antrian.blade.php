@extends('layouts.app', [
    'title' => 'Daftar Menunggu Validasi - SKMS',
    'topbar_class' => 'seamless-topbar'
])

@section('topbar_left')
    <h1 class="topbar-page-title">Daftar Menunggu Validasi</h1>
@endsection

@section('content')
<div class="validator-page-container">

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('validator.antrian') }}" id="antrianFilterForm" class="faq-filter-bar">
        <div class="faq-search-wrapper">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
        <div class="faq-select-wrapper">
            <select name="category_id" id="antrianCategorySelect" onchange="document.getElementById('antrianFilterForm').submit()" class="faq-category-dropdown">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (string)($categoryId ?? '') === (string)$category->id ? 'selected' : '' }}>
                        {{ $category->nama }}
                    </option>
                @endforeach
            </select>
            <svg class="chevron-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>
        <button type="submit" class="btn-filter-search">Cari</button>
    </form>

    {{-- Info jumlah --}}
    <div class="validator-queue-info">
        <span>Total antrean: <strong>{{ $faqs->total() }}</strong> FAQ menunggu validasi</span>
    </div>

    {{-- Tabel Antrean --}}
    <div class="faq-table-container">
        <table class="faq-custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Pertanyaan</th>
                    <th>Kategori</th>
                    <th>Pengaju</th>
                    <th>Tanggal</th>
                    <th style="text-align: center; width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $index => $faq)
                    <tr>
                        <td class="col-no">{{ $faqs->firstItem() + $index }}</td>
                        <td class="col-pertanyaan">
                            <a href="{{ route('validator.faq.show', $faq->id) }}" class="validator-faq-link">
                                {{ Str::limit($faq->pertanyaan, 80) }}
                            </a>
                        </td>
                        <td class="col-kategori">
                            <div class="category-pills-container">
                                @forelse($faq->categories as $cat)
                                    <span class="category-pill">{{ $cat->nama }}</span>
                                @empty
                                    <span class="category-pill-empty">-</span>
                                @endforelse
                            </div>
                        </td>
                        <td style="font-size: 13px; color: var(--text-muted);">
                            {{ $faq->user?->name ?? '-' }}
                        </td>
                        <td style="font-size: 12px; color: var(--text-subtle); white-space: nowrap;">
                            {{ $faq->created_at->format('d M Y') }}
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('validator.faq.show', $faq->id) }}" class="btn-validator-review">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                Tinjau
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="validator-empty-row">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <p>Tidak ada FAQ yang menunggu validasi.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($faqs->hasPages())
        <div class="faq-pagination-bar">
            @if($faqs->onFirstPage())
                <span class="page-nav prev-btn disabled">&lsaquo;</span>
            @else
                <a href="{{ $faqs->previousPageUrl() }}" class="page-nav prev-btn">&lsaquo;</a>
            @endif
            @foreach(range(1, $faqs->lastPage()) as $page)
                @if($page == $faqs->currentPage())
                    <span class="page-number active">{{ $page }}</span>
                @else
                    <a href="{{ $faqs->url($page) }}" class="page-number">{{ $page }}</a>
                @endif
            @endforeach
            @if($faqs->hasMorePages())
                <a href="{{ $faqs->nextPageUrl() }}" class="page-nav next-btn">&rsaquo;</a>
            @else
                <span class="page-nav next-btn disabled">&rsaquo;</span>
            @endif
        </div>
    @endif

</div>
@endsection
