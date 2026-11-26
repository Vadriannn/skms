@extends('layouts.app', [
    'title' => 'Tiket Pertanyaan Mahasiswa - SKMS'
])

@section('content')
<div class="validator-page-container">

    <!-- Page Intro Header -->
    <div class="skmk-page-intro">
        <div>
            <h1 class="skmk-page-main-title">Tiket pertanyaan mahasiswa</h1>
            <p class="skmk-page-subtitle">Daftar antrean pertanyaan baru yang diajukan mahasiswa beridentitas NRP melalui portal Tanya Staf.</p>
        </div>
        <div>
            <span style="background: {{ $countPending > 0 ? '#FFF4EB' : '#FDF2F3' }}; color: {{ $countPending > 0 ? '#B4690E' : '#8B0D18' }}; border: 1px solid {{ $countPending > 0 ? '#F5DFB0' : '#F1CFD3' }}; font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 9999px;">
                {{ $countPending }} menunggu tanggapan
            </span>
        </div>
    </div>

    <!-- White Card Container Wrapping Tabs, Filter & Table -->
    <div class="faq-table-container" style="padding: 0; overflow: visible;">

        <!-- Top Inline Tabs (Semua tiket, Menunggu tanggapan, Sudah dijawab) -->
        <div style="padding: 16px 24px 0 24px; border-bottom: 1px solid #F3ECEC; display: flex; align-items: center; gap: 22px; flex-wrap: wrap;">
            <a href="{{ route('questions.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}"
               style="padding-bottom: 12px; text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; border-bottom: 2px solid {{ $status === 'all' ? '#8B0D18' : 'transparent' }}; color: {{ $status === 'all' ? '#8B0D18' : '#6B5B5D' }};">
                <span>Semua tiket</span>
                <span style="background: {{ $status === 'all' ? '#FDF2F3' : '#F6F0F0' }}; color: {{ $status === 'all' ? '#8B0D18' : '#6B5B5D' }}; font-size: 11.5px; padding: 1px 8px; border-radius: 9999px;">{{ $countAll }}</span>
            </a>

            <a href="{{ route('questions.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}"
               style="padding-bottom: 12px; text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; border-bottom: 2px solid {{ $status === 'pending' ? '#8B0D18' : 'transparent' }}; color: {{ $status === 'pending' ? '#8B0D18' : '#6B5B5D' }};">
                <span>Menunggu tanggapan</span>
                <span style="background: {{ $status === 'pending' ? '#FFF4EB' : '#F6F0F0' }}; color: {{ $status === 'pending' ? '#B4690E' : '#6B5B5D' }}; font-size: 11.5px; padding: 1px 8px; border-radius: 9999px;">{{ $countPending }}</span>
            </a>

            <a href="{{ route('questions.index', array_merge(request()->except('page'), ['status' => 'answered'])) }}"
               style="padding-bottom: 12px; text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; border-bottom: 2px solid {{ $status === 'answered' ? '#8B0D18' : 'transparent' }}; color: {{ $status === 'answered' ? '#8B0D18' : '#6B5B5D' }};">
                <span>Sudah dijawab</span>
                <span style="background: {{ $status === 'answered' ? '#FDF2F3' : '#F6F0F0' }}; color: {{ $status === 'answered' ? '#8B0D18' : '#6B5B5D' }}; font-size: 11.5px; padding: 1px 8px; border-radius: 9999px;">{{ $countAnswered }}</span>
            </a>
        </div>

        <!-- Filter & Search Bar -->
        <div style="padding: 16px 24px;">
            <form method="GET" action="{{ route('questions.index') }}" id="ticketFilterForm" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                @if($status !== 'all')
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif

                <div class="faq-search-wrapper" style="flex: 1; min-width: 240px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#948486" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari pertanyaan atau 9 digit NRP mahasiswa..."
                           class="faq-search-field"
                           autocomplete="off">
                </div>

                <div class="faq-select-wrapper" style="width: 155px;">
                    <select name="per_page" onchange="document.getElementById('ticketFilterForm').submit()" class="faq-category-dropdown">
                        @foreach([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ (int) $perPage === $size ? 'selected' : '' }}>
                                {{ $size }} per halaman
                            </option>
                        @endforeach
                    </select>
                    <svg class="chevron-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6B5B5D" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>

                <button type="submit" class="btn-filter-search" style="padding: 10px 20px;">Cari</button>

                @if($search !== '' || $status !== 'all')
                    <a href="{{ route('questions.index') }}" class="btn-secondary" style="padding: 9px 14px; font-size: 13px; text-decoration: none; color: #6B5B5D;">
                        Atur ulang
                    </a>
                @endif
            </form>
        </div>

        <!-- Tabel Antrean Tiket Pertanyaan Mahasiswa -->
        <table class="faq-custom-table">
            <thead>
                <tr>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 24px;">PERTANYAAN MAHASISWA</th>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 20px;">IDENTITAS (NRP &amp; EMAIL)</th>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 20px;">TINDAK LANJUT</th>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 20px;">STATUS</th>
                    <th style="font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #6B5B5D; padding: 12px 24px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td class="col-pertanyaan" style="padding: 16px 24px; max-width: 440px;">
                            <a href="{{ route('questions.show', $ticket->id) }}" class="validator-faq-link" style="font-weight: 600; font-size: 14px; color: #1F1415; display: block; margin-bottom: 4px; overflow-wrap: anywhere; word-break: break-word;">
                                {{ \Illuminate\Support\Str::limit($ticket->pertanyaan, 110) }}
                            </a>
                            <div style="font-size: 11.5px; color: #948486;">
                                Tiket #{{ $ticket->id }} &bull; Diajukan {{ $ticket->created_at->format('d M Y, H:i') }}
                            </div>
                        </td>

                        <td style="padding: 16px 20px;">
                            @if($ticket->nrp)
                                <div style="font-size: 13.5px; font-weight: 700; color: #1F1415; margin-bottom: 2px;">
                                    NRP {{ $ticket->nrp }}
                                </div>
                                <div style="font-size: 12px; color: #6B5B5D;">
                                    {{ $ticket->student_email }}
                                </div>
                            @else
                                <span style="display: inline-block; padding: 3px 8px; border-radius: 6px; background: #F1F5F4; color: #6B5B5D; font-size: 11.5px; font-weight: 600;">
                                    Anonim (Tanpa NRP)
                                </span>
                            @endif
                        </td>

                        <td style="padding: 16px 20px;">
                            @if($ticket->faq)
                                <a href="{{ route('faqs.show', $ticket->faq->id) }}" style="display: inline-flex; align-items: center; gap: 5px; background: #FDF2F3; color: #8B0D18; border: 1px solid #F1CFD3; font-size: 11.5px; font-weight: 600; padding: 3px 10px; border-radius: 6px; text-decoration: none;" title="{{ $ticket->faq->pertanyaan }}">
                                    <span>FAQ #{{ $ticket->faq->id }}</span>
                                    <span>&#8599;</span>
                                </a>
                            @elseif($ticket->user)
                                <span style="font-size: 12.5px; color: #3E2F30;">
                                    Ditangani oleh <strong>{{ $ticket->user->name }}</strong>
                                </span>
                            @else
                                <span style="font-size: 12px; color: #948486;">Belum ditautkan</span>
                            @endif
                        </td>

                        <td style="padding: 16px 20px;">
                            @if($ticket->status === 'answered')
                                <span class="skmk-dot-approved">Sudah dijawab</span>
                                @if($ticket->user)
                                    <div style="font-size: 11px; color: #948486; margin-top: 2px;">
                                        oleh {{ $ticket->user->name }}
                                    </div>
                                @endif
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 6px; background-color: #FFF4EB; color: #B4690E; border: 1px solid #F5DFB0; font-size: 11.5px; padding: 3px 10px; border-radius: 999px; font-weight: 600;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #D97706;"></span>
                                    Menunggu tanggapan
                                </span>
                            @endif
                        </td>

                        <td style="padding: 16px 24px; text-align: right; white-space: nowrap;">
                            <a href="{{ route('questions.show', $ticket->id) }}" class="skmk-link-action">
                                <span>{{ $ticket->status === 'pending' ? 'Tinjau' : 'Detail' }}</span>
                                <span>&rarr;</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="validator-empty-row">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#948486" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                <line x1="9" y1="9" x2="15" y2="9"></line>
                                <line x1="9" y1="13" x2="13" y2="13"></line>
                            </svg>
                            <p>Belum ada tiket pengajuan pertanyaan mahasiswa yang sesuai filter.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer Summary & Pagination -->
        <div style="padding: 14px 24px; border-top: 1px solid #F3ECEC; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; font-size: 12.5px; color: #948486;">
            <div>
                @if($tickets->total() > 0)
                    Menampilkan {{ $tickets->firstItem() }}–{{ $tickets->lastItem() }} dari {{ $tickets->total() }} tiket pertanyaan
                @else
                    Menampilkan 0 tiket pertanyaan
                @endif
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                @if($tickets->onFirstPage())
                    <span class="page-nav disabled" style="min-width: 28px; height: 28px; font-size: 14px;">&lsaquo;</span>
                @else
                    <a href="{{ $tickets->previousPageUrl() }}" class="page-nav" style="min-width: 28px; height: 28px; font-size: 14px; color: #6B5B5D;">&lsaquo;</a>
                @endif
                @foreach(range(1, max(1, $tickets->lastPage())) as $page)
                    @if($page == $tickets->currentPage())
                        <span class="page-number active" style="min-width: 28px; height: 28px; font-size: 12.5px;">{{ $page }}</span>
                    @else
                        <a href="{{ $tickets->url($page) }}" class="page-number" style="min-width: 28px; height: 28px; font-size: 12.5px;">{{ $page }}</a>
                    @endif
                @endforeach
                @if($tickets->hasMorePages())
                    <a href="{{ $tickets->nextPageUrl() }}" class="page-nav" style="min-width: 28px; height: 28px; font-size: 14px; color: #6B5B5D;">&rsaquo;</a>
                @else
                    <span class="page-nav disabled" style="min-width: 28px; height: 28px; font-size: 14px;">&rsaquo;</span>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
