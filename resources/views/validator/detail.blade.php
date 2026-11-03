@extends('layouts.app', [
    'title' => 'Detail FAQ - Validator - SKMS',
    'topbar_class' => 'seamless-topbar'
])

@section('topbar_left')
    <div class="topbar-breadcrumb">
        <a href="{{ route('validator.antrian') }}" class="topbar-back-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            Kembali ke Antrean
        </a>
        <span class="topbar-breadcrumb-sep">/</span>
        <h1 class="topbar-page-title" style="font-size: 15px;">Detail FAQ #{{ $faq->id }}</h1>
    </div>
@endsection

@section('content')
<div class="validator-detail-container">

    {{-- ── Panel Kiri: Konten FAQ ──────────────────────────────── --}}
    <div class="validator-detail-main">

        {{-- Banner Pemberitahuan Status Pengajuan: Editan vs Revisian --}}
        @if($faq->submission_type === 'edit')
            <div style="background-color: #EFF6FF; border: 1px solid #93C5FD; border-radius: 10px; padding: 14px 18px; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 12px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <div>
                    <div style="font-weight: 700; color: #1E40AF; font-size: 14px;">
                        Pemberitahuan: Validasi Pembaruan Data (Editan FAQ Terbit)
                    </div>
                    <p style="margin: 4px 0 0 0; color: #1E3A8A; font-size: 13px; line-height: 1.5;">
                        Informasi ini <strong>bukan revisian dari penolakan</strong>, melainkan FAQ yang <strong>sebelumnya sudah disetujui & terbit</strong>. Data Entry telah menyunting/memperbarui isi pertanyaan atau jawabannya, dan mengajukannya kembali untuk diverifikasi ulang sebelum tayang kembali ke publik.
                    </p>
                </div>
            </div>
        @elseif($faq->submission_type === 'revision')
            <div style="background-color: #FFFBEB; border: 1px solid #FCD34D; border-radius: 10px; padding: 14px 18px; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 12px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div>
                    <div style="font-weight: 700; color: #92400E; font-size: 14px;">
                        Pemberitahuan: Validasi Hasil Perbaikan (Revisian Ditolak)
                    </div>
                    <p style="margin: 4px 0 0 0; color: #78350F; font-size: 13px; line-height: 1.5;">
                        FAQ ini sebelumnya <strong>ditolak oleh Validator</strong>. Data Entry telah melakukan perbaikan sesuai catatan validator dan mengajukannya kembali untuk diperiksa.
                    </p>
                </div>
            </div>
        @endif

        {{-- Header FAQ --}}
        <div class="vd-detail-card">
            <div class="vd-detail-card-header">
                <div>
                    <div class="vd-detail-meta">
                        <span class="vd-detail-meta-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            {{ $faq->user?->name ?? 'Tidak diketahui' }}
                        </span>
                        <span class="vd-detail-meta-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            {{ $faq->created_at->format('d F Y, H:i') }}
                        </span>
                        <span class="vd-detail-meta-item">
                            @if($faq->status === 'pending')
                                <span class="status-pill status-pending">Menunggu Validasi</span>
                            @elseif($faq->status === 'published')
                                <span class="status-pill status-approved">Disetujui</span>
                            @elseif($faq->status === 'rejected')
                                <span class="status-pill status-rejected">Ditolak</span>
                            @else
                                <span class="status-pill status-draft">Draft</span>
                            @endif

                            @if($faq->status === 'pending')
                                @if($faq->submission_type === 'edit')
                                    <span class="status-pill" style="background-color: #DBEAFE; color: #1E40AF; border: 1px solid #BFDBFE;">Editan FAQ Terbit</span>
                                @elseif($faq->submission_type === 'revision')
                                    <span class="status-pill" style="background-color: #FEF3C7; color: #92400E; border: 1px solid #FDE68A;">Revisian Ditolak</span>
                                @endif
                            @endif
                        </span>
                    </div>
                    <h2 class="vd-detail-pertanyaan">{{ $faq->pertanyaan }}</h2>
                </div>
            </div>

            {{-- Kategori --}}
            @if($faq->categories->count() > 0)
                <div class="vd-detail-categories">
                    @foreach($faq->categories as $cat)
                        <span class="category-pill">{{ $cat->nama }}</span>
                    @endforeach
                </div>
            @endif

            {{-- Jawaban --}}
            <div class="vd-detail-jawaban-label">Jawaban / Penjelasan:</div>
            <div class="vd-detail-jawaban-content">{!! nl2br(e($faq->jawaban)) !!}</div>
        </div>

        {{-- Riwayat Review sebelumnya --}}
        @if($faq->reviews->count() > 0)
        <div class="vd-detail-card" style="margin-top: 16px;">
            <div class="vd-history-header">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                Riwayat Review ({{ $faq->reviews->count() }})
            </div>
            <div class="vd-review-history-list">
                @foreach($faq->reviews->sortByDesc('created_at') as $review)
                    <div class="vd-review-history-item {{ $review->keputusan === 'approved' ? 'approved' : 'rejected' }}">
                        <div class="vd-review-history-top">
                            <span class="vd-review-history-badge {{ $review->keputusan === 'approved' ? 'badge-approved' : 'badge-rejected' }}">
                                @if($review->keputusan === 'approved')
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    Disetujui
                                @else
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    Ditolak
                                @endif
                            </span>
                            <span class="vd-review-history-reviewer">oleh {{ $review->user?->name ?? '-' }}</span>
                            <span class="vd-review-history-date">{{ $review->created_at->format('d M Y H:i') }}</span>
                        </div>
                        @if($review->catatan)
                            <div class="vd-review-history-catatan">
                                <strong>Catatan:</strong> {{ $review->catatan }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- ── Panel Kanan: Form Keputusan ────────────────────────── --}}
    @if($faq->status === 'pending')
    <div class="validator-detail-actions">

        {{-- Form Setujui --}}
        <div class="vd-action-card approve-card">
            <div class="vd-action-card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                Setujui FAQ
            </div>
            <p class="vd-action-card-desc">FAQ akan dipublikasikan dan dapat dilihat oleh pengguna.</p>
            <form action="{{ route('validator.faq.approve', $faq->id) }}" method="POST" id="approveForm">
                @csrf
                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label" for="approve_catatan">Catatan (opsional)</label>
                    <textarea id="approve_catatan" name="catatan" rows="3" class="form-control vd-textarea" placeholder="Tambahkan catatan persetujuan jika diperlukan..."></textarea>
                </div>
                <button type="button" class="btn-vd-approve" onclick="confirmAction('approve', {{ $faq->id }})">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Setujui & Publikasikan
                </button>
            </form>
        </div>

        {{-- Form Tolak --}}
        <div class="vd-action-card reject-card">
            <div class="vd-action-card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                Tolak FAQ
            </div>
            <p class="vd-action-card-desc">FAQ akan dikembalikan ke pengaju dengan catatan perbaikan.</p>
            <form action="{{ route('validator.faq.reject', $faq->id) }}" method="POST" id="rejectForm">
                @csrf
                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label" for="reject_catatan">
                        Catatan Perbaikan <span class="required">*</span>
                    </label>
                    <textarea id="reject_catatan" name="catatan" rows="4" class="form-control vd-textarea" placeholder="Jelaskan alasan penolakan dan apa yang perlu diperbaiki..." required></textarea>
                    @error('catatan')
                        <div class="vd-field-error">{{ $message }}</div>
                    @enderror
                </div>
                <button type="button" class="btn-vd-reject" onclick="confirmAction('reject', {{ $faq->id }})">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Tolak & Kembalikan
                </button>
            </form>
        </div>

    </div>
    @else
    <div class="validator-detail-actions">
        <div class="vd-action-card" style="border-color: var(--border-color);">
            <div class="vd-action-card-title" style="color: var(--text-muted);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                FAQ Telah Diproses
            </div>
            <p class="vd-action-card-desc">
                FAQ ini sudah berstatus <strong>{{ ucfirst($faq->status) }}</strong> dan tidak dapat diubah lagi melalui panel ini.
            </p>
            <a href="{{ route('validator.antrian') }}" class="btn-secondary" style="margin-top: 8px;">
                Kembali ke Antrean
            </a>
        </div>
    </div>
    @endif

</div>

{{-- Modal Konfirmasi --}}
<div id="confirmModal" class="modal-backdrop" style="display: none;">
    <div class="modal-dialog" style="max-width: 420px;">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="confirmModalTitle">Konfirmasi Keputusan</h2>
                <p class="modal-subtitle" id="confirmModalDesc"></p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-modal-cancel" onclick="closeConfirmModal()">Batal</button>
            <button type="button" id="confirmModalBtn" class="btn-modal-submit">Konfirmasi</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let pendingAction = null;
    let pendingFaqId  = null;

    function confirmAction(action, faqId) {
        pendingAction = action;
        pendingFaqId  = faqId;

        const modal     = document.getElementById('confirmModal');
        const title     = document.getElementById('confirmModalTitle');
        const desc      = document.getElementById('confirmModalDesc');
        const btn       = document.getElementById('confirmModalBtn');

        if (action === 'approve') {
            title.textContent = 'Setujui FAQ ini?';
            desc.textContent  = 'FAQ akan dipublikasikan dan dapat dilihat oleh semua pengguna sistem.';
            btn.textContent   = 'Ya, Setujui';
            btn.className     = 'btn-modal-submit btn-confirm-approve';
        } else {
            const catatan = document.getElementById('reject_catatan').value.trim();
            if (!catatan || catatan.length < 5) {
                alert('Catatan perbaikan wajib diisi (minimal 5 karakter) sebelum menolak.');
                document.getElementById('reject_catatan').focus();
                return;
            }
            title.textContent = 'Tolak FAQ ini?';
            desc.textContent  = 'FAQ akan dikembalikan ke pengaju dengan catatan perbaikan yang telah Anda tulis.';
            btn.textContent   = 'Ya, Tolak';
            btn.className     = 'btn-modal-submit btn-confirm-reject';
        }

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    document.getElementById('confirmModalBtn').addEventListener('click', function() {
        if (pendingAction === 'approve') {
            document.getElementById('approveForm').submit();
        } else if (pendingAction === 'reject') {
            document.getElementById('rejectForm').submit();
        }
    });

    function closeConfirmModal() {
        document.getElementById('confirmModal').style.display = 'none';
        document.body.style.overflow = '';
        pendingAction = null;
        pendingFaqId  = null;
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeConfirmModal();
    });

    document.getElementById('confirmModal').addEventListener('click', function(e) {
        if (e.target === this) closeConfirmModal();
    });
</script>
@endpush
