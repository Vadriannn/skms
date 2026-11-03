@extends('layouts.app', [
    'title' => 'Informasi Disetujui - SKMS',
    'topbar_class' => 'seamless-topbar'
])

@section('topbar_left')
    <h1 class="topbar-page-title">Informasi Disetujui</h1>
@endsection

@section('content')
<div class="validator-page-container">

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('validator.disetujui') }}" id="disetujuiFilterForm" class="faq-filter-bar">
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
        <button type="submit" class="btn-filter-search">Cari</button>
    </form>

    <div class="validator-queue-info">
        <span>Total disetujui: <strong>{{ $faqs->total() }}</strong> FAQ dipublikasikan</span>
    </div>

    <div class="faq-table-container">
        <table class="faq-custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Pertanyaan</th>
                    <th>Kategori</th>
                    <th>Pengaju</th>
                    <th>Disetujui oleh</th>
                    <th>Tanggal</th>
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
                        <td style="font-size: 13px; color: var(--emerald-text);">
                            @php
                                $lastApproval = $faq->reviews->first();
                            @endphp
                            {{ $lastApproval?->user?->name ?? '-' }}
                        </td>
                        <td style="font-size: 12px; color: var(--text-subtle); white-space: nowrap;">
                            {{ $faq->updated_at->format('d M Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="validator-empty-row">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <polyline points="9 15 11 17 15 13"></polyline>
                            </svg>
                            <p>Belum ada FAQ yang disetujui.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

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
