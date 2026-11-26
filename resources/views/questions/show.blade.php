@extends('layouts.app', [
    'title' => 'Detail & Penanganan Tiket #' . $ticket->id . ' - SKMS'
])

@push('styles')
<style>
    /* ── AJAX MODAL PILIH FAQ LAMA ─────────────────────────────────── */
    .ajax-faq-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(31, 20, 21, 0.55);
        backdrop-filter: blur(3px);
        z-index: 999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .ajax-faq-modal-backdrop.is-open {
        display: flex;
    }

    .ajax-faq-modal-dialog {
        background: #FFFFFF;
        border: 1px solid #ECE4E5;
        border-radius: 16px;
        width: 100%;
        max-width: 820px;
        max-height: 86vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 24px 48px -12px rgba(31, 20, 21, 0.25);
        overflow: hidden;
    }

    .ajax-faq-modal-header {
        padding: 20px 24px 16px;
        border-bottom: 1px solid #F3ECEC;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
    }

    .ajax-faq-modal-search-bar {
        padding: 14px 24px;
        background: #FAF8F7;
        border-bottom: 1px solid #F3ECEC;
    }

    .ajax-faq-modal-body {
        padding: 16px 24px;
        overflow-y: auto;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .ajax-faq-item {
        border: 1.5px solid #ECE4E5;
        border-radius: 12px;
        padding: 14px 18px;
        background: #FFFFFF;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .ajax-faq-item:hover {
        border-color: #E5A032;
        background: #FAFDFC;
    }

    .ajax-faq-item.is-selected {
        border-color: #8B0D18;
        background: #FDF2F3;
        box-shadow: 0 0 0 3px rgba(139, 13, 24, 0.12);
    }

    .ajax-faq-modal-footer {
        padding: 14px 24px;
        border-top: 1px solid #F3ECEC;
        background: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .kbd-hint {
        display: inline-block;
        padding: 2px 6px;
        font-size: 11px;
        font-weight: 700;
        color: #1F1415;
        background: #F6F0F0;
        border: 1px solid #D5E0DC;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
@php
    $initialSelectedFaq = $publishedFaqs->firstWhere('id', (int) old('faq_id', $ticket->faq_id));
@endphp

<div class="validator-page-container">

    <!-- Back Link & Page Header -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('questions.index') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: #8B0D18; text-decoration: none; margin-bottom: 10px;">
            <span>&larr;</span>
            <span>Kembali ke antrean tiket pertanyaan</span>
        </a>
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <h1 class="skmk-page-main-title" style="margin: 0;">Penanganan tiket pertanyaan</h1>
            <span style="background: #F6F0F0; color: #6B5B5D; border: 1px solid #ECE4E5; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 6px;">
                Tiket #{{ $ticket->id }}
            </span>
        </div>
        <p class="skmk-page-subtitle" style="margin-top: 4px;">
            Kaitkan pertanyaan mahasiswa dengan FAQ lama yang relevan atau buat draf FAQ baru dari tiket ini.
        </p>
    </div>

    <!-- 2-Column Layout -->
    <div class="validator-detail-container">
        <!-- ── KOLOM KIRI: PERTANYAAN MAHASISWA & REKOMENDASI TF-IDF ─────── -->
        <div class="validator-detail-main">

            <!-- Kartu Pertanyaan Mahasiswa -->
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 24px 26px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding-bottom: 14px; margin-bottom: 16px; border-bottom: 1px solid #F3ECEC;">
                    <div style="font-size: 12.5px; color: #6B5B5D;">
                        Diajukan oleh <strong style="color: #1F1415;">{{ $ticket->nrp ? 'NRP ' . $ticket->nrp : 'Anonim (Tanpa NRP)' }}</strong>
                        &bull; {{ $ticket->created_at->format('d F Y, H:i') }}
                    </div>
                    <div>
                        @if($ticket->status === 'answered')
                            <span style="background: #FDF2F3; color: #8B0D18; border: 1px solid #F1CFD3; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 999px;">
                                &bull; Sudah dijawab
                            </span>
                        @else
                            <span style="background: #FFF4EB; color: #B4690E; border: 1px solid #F5DFB0; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 999px;">
                                Menunggu tanggapan
                            </span>
                        @endif
                    </div>
                </div>

                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #948486; margin-bottom: 6px;">
                    PERTANYAAN MAHASISWA
                </div>
                <div style="font-size: 18px; font-weight: 700; color: #1F1415; line-height: 1.45; margin-bottom: 18px; overflow-wrap: anywhere; word-break: break-word;">
                    {{ $ticket->pertanyaan }}
                </div>

                @if($ticket->nrp)
                    <div style="background: #FAF8F7; border: 1px solid #ECE4E5; border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; font-size: 12.5px;">
                        <div>
                            <span style="color: #6B5B5D;">Email Kampus Tujuan Notifikasi:</span>
                            <strong style="color: #8B0D18; margin-left: 4px;">{{ $ticket->student_email }}</strong>
                        </div>
                        <span style="color: #948486; font-size: 11.5px;">Otomatis dari 9 digit NRP &bull; Notifikasi Email Aktif</span>
                    </div>
                @else
                    <div style="background: #FAF8F7; border: 1px solid #ECE4E5; border-radius: 10px; padding: 12px 16px; font-size: 12.5px; color: #6B5B5D;">
                        <span style="font-weight: 700; color: #6B5B5D;">Pengajuan Tanpa NRP (Anonim):</span> Mahasiswa tidak mencantumkan NRP sehingga sistem tidak akan mengirimkan notifikasi email.
                    </div>
                @endif
            </div>

            @if($ticket->note || $ticket->faq)
                <!-- Kartu Riwayat Tanggapan Staf (Jika Sudah Pernah Ditangani) -->
                <div style="background: #FDF2F3; border: 1px solid #F1CFD3; border-radius: 14px; padding: 20px 24px; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; margin-bottom: 8px;">
                        <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #8B0D18;">
                            HASIL PENANGANAN TIKET
                        </span>
                        @if($ticket->user)
                            <span style="font-size: 12px; color: #6B5B5D;">
                                Ditangani oleh <strong>{{ $ticket->user->name }}</strong>
                            </span>
                        @endif
                    </div>

                    @if($ticket->note)
                        <div style="font-size: 13.5px; color: #1F1415; line-height: 1.6; margin-bottom: 12px; overflow-wrap: anywhere; word-break: break-word;">
                            {!! nl2br(e($ticket->note)) !!}
                        </div>
                    @endif

                    @if($ticket->faq)
                        <div style="background: #FFFFFF; border: 1px solid #F1CFD3; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                            <div>
                                <div style="font-size: 11px; font-weight: 700; color: #8B0D18; text-transform: uppercase;">
                                    FAQ Tertaut (#{{ $ticket->faq->id }} &bull; {{ ucfirst($ticket->faq->status) }})
                                </div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #1F1415; margin-top: 2px;">
                                    &ldquo;{{ $ticket->faq->pertanyaan }}&rdquo;
                                </div>
                            </div>
                            <a href="{{ route('faqs.show', $ticket->faq->id) }}" class="skmk-link-action" style="font-size: 12.5px;">
                                <span>Lihat Detail FAQ</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Kartu Rekomendasi Kemiripan TF-IDF -->
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 22px 26px;">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 6px; flex-wrap: wrap;">
                    <h3 style="font-size: 15px; font-weight: 700; color: #1F1415; margin: 0;">
                        Rekomendasi FAQ Serupa di Repositori (TF-IDF)
                    </h3>
                    <span style="font-size: 11.5px; font-weight: 600; color: #8B0D18; background: #FDF2F3; padding: 2px 10px; border-radius: 999px;">
                        {{ $similarCandidates->count() }} kandidat ditemukan
                    </span>
                </div>
                <p style="font-size: 12.5px; color: #6B5B5D; margin: 0 0 16px;">
                    Hasil deteksi kemiripan otomatis terhadap pertanyaan mahasiswa ini:
                </p>

                @forelse($similarCandidates as $cand)
                    @php
                        $candFaq = $cand['faq'];
                        $candPct = $cand['percentage'] ?? (round(($cand['score'] ?? 0) * 100, 1) . '%');
                        $candCats = $candFaq->categories->pluck('nama')->join(', ') ?: 'Umum';
                    @endphp
                    <div style="border: 1px solid #ECE4E5; border-radius: 11px; padding: 14px 16px; margin-bottom: 12px; background: #FAFBFB;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 8px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <span style="font-size: 12px; font-weight: 700; color: #8B0D18;">FAQ #{{ $candFaq->id }}</span>
                                <span style="font-size: 11px; padding: 2px 8px; border-radius: 999px; background: #FDF2F3; color: #8B0D18; font-weight: 600;">
                                    {{ $candCats }}
                                </span>
                                <span style="background: #FFF4EB; color: #B4690E; border: 1px solid #F5DFB0; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 6px;">
                                    {{ $candPct }} mirip
                                </span>
                            </div>

                            <button type="button"
                                    class="btn-quick-pick-tfidf"
                                    data-id="{{ $candFaq->id }}"
                                    data-question="{{ $candFaq->pertanyaan }}"
                                    data-answer="{{ $candFaq->jawaban }}"
                                    data-cats="{{ $candCats }}"
                                    style="padding: 5px 12px; background: #FDF2F3; color: #8B0D18; border: 1px solid #F1CFD3; border-radius: 7px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: inherit;">
                                Pilih FAQ Ini
                            </button>
                        </div>

                        <div style="font-size: 14px; font-weight: 700; color: #1F1415; margin-bottom: 6px;">
                            &ldquo;{{ $candFaq->pertanyaan }}&rdquo;
                        </div>
                        <div style="font-size: 12.5px; color: #6B5B5D; line-height: 1.55; background: #FFFFFF; border: 1px solid #E6ECEA; border-radius: 8px; padding: 10px 12px;">
                            {!! nl2br(e($candFaq->jawaban)) !!}
                        </div>
                    </div>
                @empty
                    <div style="background: #FAF8F7; border: 1px dashed #D5E0DC; border-radius: 10px; padding: 20px; text-align: center; font-size: 13px; color: #6B5B5D;">
                        Tidak ditemukan rekomendasi. Anda tetap dapat mencari manual lewat tombol <strong>Kaitkan dengan FAQ Lama</strong> atau <strong>Buat Draf FAQ Baru</strong> di sebelah kanan.
                    </div>
                @endforelse
            </div>

        </div>

        <!-- ── KOLOM KANAN: PANEL ALUR KERJA CERDAS PENANGANAN TIKET (SB-028) ── -->
        <aside class="validator-detail-actions">

            <!-- KARTU 1: KAITKAN DENGAN FAQ LAMA (POPUP AJAX) -->
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 22px; margin-bottom: 16px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                    <span style="width: 24px; height: 24px; border-radius: 6px; background: #FDF2F3; color: #8B0D18; font-size: 12px; font-weight: 800; display: inline-flex; align-items: center; justify-content: center;">1</span>
                    <h3 style="font-size: 15px; font-weight: 700; color: #1F1415; margin: 0;">
                        Kaitkan dengan FAQ Lama
                    </h3>
                </div>

                <!-- Tombol Pemicu Popup AJAX Daftar FAQ Lama -->
                <button type="button"
                        id="btnOpenAjaxFaqModal"
                        style="width: 100%; padding: 10px 14px; background: #FDF2F3; color: #8B0D18; border: 1.5px solid #E5A032; border-radius: 9px; font-size: 13px; font-weight: 700; cursor: pointer; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 14px; transition: all 0.15s ease;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <span id="btnOpenModalLabel">{{ $initialSelectedFaq ? 'Ganti Pilihan FAQ Lama...' : 'Kaitkan dengan FAQ Lama' }}</span>
                </button>

                <form action="{{ route('questions.link_faq', $ticket->id) }}" method="POST" id="linkFaqForm">
                    @csrf
                    <input type="hidden" name="faq_id" id="selectedFaqIdInput" value="{{ $initialSelectedFaq?->id ?? '' }}">

                    <!-- Kotak Preview FAQ yang Dipilih dari Modal AJAX -->
                    <div id="selectedFaqCardBox" style="display: {{ $initialSelectedFaq ? 'block' : 'none' }}; background: #FFF7F7; border: 1.5px solid #8B0D18; border-radius: 10px; padding: 12px 14px; margin-bottom: 14px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 4px;">
                            <span id="selectedFaqBadgeText" style="font-size: 11px; font-weight: 800; color: #8B0D18; text-transform: uppercase;">
                                {{ $initialSelectedFaq ? 'FAQ #' . $initialSelectedFaq->id . ' • ' . ($initialSelectedFaq->categories->pluck('nama')->join(', ') ?: 'Umum') : '' }}
                            </span>
                            <span style="font-size: 11px; font-weight: 700; color: #8B0D18;">✓ Terpilih</span>
                        </div>
                        <div id="selectedFaqQuestionText" style="font-size: 13.5px; font-weight: 700; color: #1F1415; line-height: 1.4; margin-bottom: 6px;">
                            {{ $initialSelectedFaq?->pertanyaan ?? '' }}
                        </div>
                        <div id="selectedFaqAnswerText" style="font-size: 12px; color: #6B5B5D; line-height: 1.5; max-height: 115px; overflow-y: auto; border-top: 1px dashed #F1CFD3; padding-top: 6px; white-space: pre-line;">{{ $initialSelectedFaq?->jawaban ?? '' }}</div>
                    </div>

                    @error('faq_id')
                        <div style="color: #DC2626; font-size: 12px; margin-bottom: 10px;">{{ $message }}</div>
                    @enderror

                    <!-- Tombol Submit (Muncul begitu FAQ dipilih) -->
                    <div id="linkFaqSubmitArea" style="display: {{ $initialSelectedFaq ? 'block' : 'none' }};">
                        <button type="submit"
                                id="submitLinkFaqBtn"
                                class="btn-filter-search"
                                style="width: 100%; padding: 11px 16px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                            </svg>
                            <span>{{ $ticket->status === 'answered' ? 'Perbarui Tautan FAQ' : 'Simpan & Selesaikan Tiket' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- KARTU 2: BUAT DRAF FAQ BARU DARI TIKET -->
            <div style="background: #FDF2F3; border: 1px solid #F1CFD3; border-radius: 14px; padding: 22px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                    <span style="width: 24px; height: 24px; border-radius: 6px; background: #8B0D18; color: #FFFFFF; font-size: 12px; font-weight: 800; display: inline-flex; align-items: center; justify-content: center;">2</span>
                    <h3 style="font-size: 15px; font-weight: 700; color: #1F1415; margin: 0;">
                        Buat Draf FAQ Baru
                    </h3>
                </div>

                <a href="{{ route('faqs.create', ['from_ticket' => $ticket->id]) }}"
                   style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 11px 16px; background: #8B0D18; color: #FFFFFF; border-radius: 9px; font-size: 13px; font-weight: 700; text-decoration: none;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Buat Draf FAQ Baru dari Tiket Ini</span>
                </a>
            </div>

        </aside>
    </div>

</div>

<!-- ─── MODAL AJAX PENCARIAN & PEMILIHAN FAQ LAMA ─────────────────────────── -->
<div class="ajax-faq-modal-backdrop" id="ajaxFaqModalBackdrop">
    <div class="ajax-faq-modal-dialog" role="dialog" aria-modal="true">
        <div class="ajax-faq-modal-header">
            <div>
                <h3 style="font-size: 17px; font-weight: 800; color: #1F1415; margin: 0 0 4px;">
                    Pilih FAQ Lama untuk Dikaitkan ke Tiket #{{ $ticket->id }}
                </h3>
            </div>
            <button type="button" id="btnCloseAjaxFaqModal" style="background: #FAF8F7; border: 1px solid #ECE4E5; width: 32px; height: 32px; border-radius: 8px; font-size: 18px; color: #6B5B5D; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                &times;
            </button>
        </div>

        <div class="ajax-faq-modal-search-bar">
            <div class="faq-search-wrapper" style="background: #FFFFFF; width: 100%;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8B0D18" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text"
                       id="ajaxFaqSearchInput"
                       class="faq-search-field"
                       placeholder="Cari pertanyaan, isi jawaban lengkap, kategori, atau nomor #ID FAQ..."
                       autocomplete="off">
                <span id="ajaxFaqLoadingText" style="font-size: 12px; color: #8B0D18; font-weight: 600; display: none; padding-right: 10px;">
                    Memuat...
                </span>
            </div>
        </div>

        <div class="ajax-faq-modal-body" id="ajaxFaqResultsList">
            <!-- Diisi secara dinamis melalui AJAX -->
        </div>

        <div class="ajax-faq-modal-footer">
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px;">
                <button type="button" id="btnCancelAjaxFaqModal" class="btn-secondary" style="padding: 8px 16px; font-size: 12.5px;">
                    Batal
                </button>
                <button type="button" id="btnConfirmAjaxFaqSelection" class="btn-filter-search" style="padding: 9px 18px; font-size: 12.5px;" disabled>
                    Pilih FAQ
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const searchUrl = "{{ route('questions.search_faqs') }}";
    const backdrop = document.getElementById('ajaxFaqModalBackdrop');
    const btnOpen = document.getElementById('btnOpenAjaxFaqModal');
    const btnOpenLabel = document.getElementById('btnOpenModalLabel');
    const btnClose = document.getElementById('btnCloseAjaxFaqModal');
    const btnCancel = document.getElementById('btnCancelAjaxFaqModal');
    const btnConfirm = document.getElementById('btnConfirmAjaxFaqSelection');
    const searchInput = document.getElementById('ajaxFaqSearchInput');
    const loadingText = document.getElementById('ajaxFaqLoadingText');
    const resultsList = document.getElementById('ajaxFaqResultsList');

    const hiddenIdInput = document.getElementById('selectedFaqIdInput');
    const selectedBox = document.getElementById('selectedFaqCardBox');
    const selectedBadge = document.getElementById('selectedFaqBadgeText');
    const selectedQuestion = document.getElementById('selectedFaqQuestionText');
    const selectedAnswer = document.getElementById('selectedFaqAnswerText');
    const submitArea = document.getElementById('linkFaqSubmitArea');

    let fetchedFaqs = [];
    let activeIndex = -1;
    let searchTimer = null;

    function escapeHtml(str) {
        if (!str) return '';
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    function applyChosenFaq(faq) {
        if (!faq) return;
        const cats = Array.isArray(faq.categories) && faq.categories.length > 0
            ? faq.categories.join(', ')
            : (faq.cats || 'Umum');

        hiddenIdInput.value = faq.id;
        selectedBadge.textContent = `FAQ #${faq.id} • ${cats}`;
        selectedQuestion.textContent = faq.pertanyaan;
        selectedAnswer.textContent = faq.jawaban;

        selectedBox.style.display = 'block';
        submitArea.style.display = 'block';
        btnOpenLabel.textContent = 'Ganti Pilihan FAQ Lama...';
        closeModal();
    }

    function highlightItem(idx) {
        const items = Array.from(resultsList.querySelectorAll('.ajax-faq-item'));
        if (items.length === 0) {
            activeIndex = -1;
            btnConfirm.disabled = true;
            return;
        }

        if (idx < 0) idx = 0;
        if (idx >= items.length) idx = items.length - 1;
        activeIndex = idx;

        items.forEach((el, i) => {
            if (i === activeIndex) {
                el.classList.add('is-selected');
                el.scrollIntoView({ block: 'nearest' });
            } else {
                el.classList.remove('is-selected');
            }
        });

        btnConfirm.disabled = activeIndex < 0;
    }

    function renderFaqItems(faqs) {
        fetchedFaqs = faqs || [];
        resultsList.innerHTML = '';

        if (fetchedFaqs.length === 0) {
            activeIndex = -1;
            btnConfirm.disabled = true;
            resultsList.innerHTML = `
                <div style="padding: 36px 20px; text-align: center; color: #6B5B5D; font-size: 13.5px;">
                    Tidak ditemukan FAQ terbit yang cocok dengan kata kunci pencarian Anda.
                </div>
            `;
            return;
        }

        const currentSelectedId = parseInt(hiddenIdInput.value || '0', 10);
        let initialIdx = 0;

        fetchedFaqs.forEach((faq, idx) => {
            if (faq.id === currentSelectedId) {
                initialIdx = idx;
            }
            const catsHtml = (faq.categories && faq.categories.length > 0)
                ? faq.categories.map(c => `<span class="category-pill">${escapeHtml(c)}</span>`).join(' ')
                : `<span class="category-pill">Umum</span>`;

            const item = document.createElement('div');
            item.className = 'ajax-faq-item';
            item.dataset.index = idx;
            item.innerHTML = `
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <span style="font-size: 12px; font-weight: 800; color: #8B0D18; background: #FDF2F3; padding: 2px 8px; border-radius: 6px;">
                            FAQ #${faq.id}
                        </span>
                        ${catsHtml}
                    </div>
                </div>
                <div style="font-size: 14.5px; font-weight: 700; color: #1F1415; margin-bottom: 8px; line-height: 1.4;">
                    ${escapeHtml(faq.pertanyaan)}
                </div>
                <div style="background: #FAF8F7; border: 1px solid #ECE4E5; border-radius: 8px; padding: 10px 12px; font-size: 12.5px; color: #3E2F30; line-height: 1.6; max-height: 130px; overflow-y: auto; white-space: pre-line;">${escapeHtml(faq.jawaban)}</div>
            `;

            item.addEventListener('click', function () {
                highlightItem(idx);
            });

            item.addEventListener('dblclick', function () {
                highlightItem(idx);
                applyChosenFaq(fetchedFaqs[idx]);
            });

            resultsList.appendChild(item);
        });

        highlightItem(initialIdx);
    }

    async function fetchFaqsAjax(queryText) {
        loadingText.style.display = 'inline';
        try {
            const url = new URL(searchUrl, window.location.origin);
            if (queryText) url.searchParams.set('q', queryText);
            const res = await fetch(url.toString(), {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success) {
                renderFaqItems(data.faqs);
            }
        } catch (err) {
            console.error(err);
        } finally {
            loadingText.style.display = 'none';
        }
    }

    function openModal() {
        backdrop.classList.add('is-open');
        searchInput.focus();
        fetchFaqsAjax(searchInput.value.trim());
    }

    function closeModal() {
        backdrop.classList.remove('is-open');
    }

    if (btnOpen) btnOpen.addEventListener('click', openModal);
    if (btnClose) btnClose.addEventListener('click', closeModal);
    if (btnCancel) btnCancel.addEventListener('click', closeModal);

    if (btnConfirm) {
        btnConfirm.addEventListener('click', function () {
            if (activeIndex >= 0 && fetchedFaqs[activeIndex]) {
                applyChosenFaq(fetchedFaqs[activeIndex]);
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            if (searchTimer) clearTimeout(searchTimer);
            const val = this.value.trim();
            searchTimer = setTimeout(() => {
                fetchFaqsAjax(val);
            }, 220);
        });
    }

    // Navigasi Keyboard (Panah Atas/Bawah, Enter untuk Pilih, Escape untuk Tutup)
    document.addEventListener('keydown', function (e) {
        if (!backdrop.classList.contains('is-open')) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            highlightItem(activeIndex + 1);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            highlightItem(activeIndex - 1);
        } else if (e.key === 'Enter') {
            if (activeIndex >= 0 && fetchedFaqs[activeIndex]) {
                e.preventDefault();
                applyChosenFaq(fetchedFaqs[activeIndex]);
            }
        } else if (e.key === 'Escape') {
            e.preventDefault();
            closeModal();
        }
    });

    backdrop.addEventListener('click', function (e) {
        if (e.target === backdrop) closeModal();
    });

    // Tombol cepat dari kartu rekomendasi TF-IDF di kolom kiri
    document.querySelectorAll('.btn-quick-pick-tfidf').forEach(btn => {
        btn.addEventListener('click', function () {
            applyChosenFaq({
                id: this.dataset.id,
                pertanyaan: this.dataset.question,
                jawaban: this.dataset.answer,
                cats: this.dataset.cats
            });
        });
    });
})();
</script>
@endpush
