@extends('layouts.app', [
    'title' => 'Detail Informasi FAQ #' . $faq->id . ' - SKMS',
    'topbar_class' => 'seamless-topbar'
])

@section('topbar_left')
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="{{ request('from') === 'index' ? route('faqs.index') : route('faqs.submissions', ['status' => $faq->status]) }}"
           style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; background: #FFFFFF; border: 1px solid var(--border-color); color: var(--text-body); text-decoration: none;"
           title="Kembali">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </a>
        <h1 class="topbar-page-title" style="margin: 0;">Detail Informasi FAQ #{{ $faq->id }}</h1>
    </div>
@endsection

@section('content')
<div class="validator-detail-container">

    {{-- ── Panel Kiri: Konten Utama FAQ, Peringatan Duplikasi & Riwayat Validasi ── --}}
    <div class="validator-detail-main">

        {{-- 1. Banner Peringatan Duplikasi TF-IDF (Jika terdeteksi >= 60%) --}}
        @if($faq->latestDuplicateLog && !in_array($faq->status, ['published']))
            @php
                $dupLog = $faq->latestDuplicateLog;
                $simFaq = $dupLog->similarFaq;
                $dupScoreVal = (float) $dupLog->score;
                $dupPct = $dupScoreVal <= 1 ? round($dupScoreVal * 100) : round($dupScoreVal);
            @endphp
            <div style="background-color: #FEF2F2; border: 1px solid #FCA5A5; border-left: 5px solid #EF4444; border-radius: 10px; padding: 14px 18px; margin-bottom: 16px;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 8px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #991B1B; font-size: 14px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        <span>Peringatan Duplikasi TF-IDF Diabaikan (Kemiripan: {{ $dupPct }}%)</span>
                    </div>
                    <span style="background-color: #FEE2E2; color: #B91C1C; border: 1px solid #FCA5A5; padding: 2px 9px; border-radius: 9999px; font-size: 11.5px; font-weight: 700; white-space: nowrap;">
                        &ge; 60% Threshold
                    </span>
                </div>
                <p style="margin: 0 0 10px 0; color: #7F1D1D; font-size: 13px; line-height: 1.5;">
                    Pertanyaan ini terdeteksi memiliki kemiripan tinggi dengan FAQ yang sudah terdaftar di repositori saat diajukan oleh Data Entry:
                </p>

                @if($simFaq)
                    <div style="background: #FFFFFF; border: 1px solid #FECACA; border-radius: 8px; padding: 12px 14px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 6px; padding-bottom: 6px; border-bottom: 1px solid #F1F5F9;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase;">
                                FAQ Pembanding #{{ $simFaq->id }}
                            </span>
                            <a href="{{ route('faqs.show', $simFaq->id) }}" style="font-size: 12px; font-weight: 600; color: #4F46E5; text-decoration: none;">
                                Lihat FAQ Pembanding &rarr;
                            </a>
                        </div>
                        <div style="font-weight: 700; color: #0F172A; font-size: 13.5px; margin-bottom: 6px;">
                            &ldquo;{{ $simFaq->pertanyaan }}&rdquo;
                        </div>
                        <div style="font-size: 12.5px; color: #334155; background: #F8FAFC; border-left: 3px solid #94A3B8; padding: 6px 10px; border-radius: 0 4px 4px 0;">
                            {{ Str::limit(strip_tags($simFaq->jawaban), 180) }}
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- 2. Banner Alasan Penolakan dari Validator (Jika status rejected) --}}
        @if($faq->status === 'rejected' && $faq->latestReview && $faq->latestReview->catatan)
            <div style="background-color: #FEF2F2; border: 1px solid #FECACA; border-radius: 10px; padding: 16px 18px; margin-bottom: 16px;">
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #991B1B; font-size: 14px; margin-bottom: 4px;">
                            Catatan Perbaikan dari Validator:
                        </div>
                        <div style="color: #B91C1C; font-size: 14px; line-height: 1.5;">
                            &ldquo;{{ $faq->latestReview->catatan }}&rdquo;
                        </div>
                        <div style="margin-top: 8px; font-size: 12px; color: #7F1D1D; opacity: 0.85;">
                            Diverifikasi oleh: <strong>{{ $faq->latestReview->user?->name ?? 'Validator' }}</strong> &bull; {{ $faq->latestReview->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- 3. Kartu Konten Pertanyaan & Jawaban FAQ --}}
        <div class="vd-detail-card">
            <div class="vd-detail-card-header">
                <div class="vd-detail-meta">
                    <span class="vd-detail-meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        Dibuat oleh: <strong>{{ $faq->user?->name ?? '-' }}</strong>
                    </span>
                    <span class="vd-detail-meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        {{ $faq->created_at->format('d M Y, H:i') }}
                    </span>
                    <span class="vd-detail-meta-item">
                        @if($faq->status === 'draft')
                            <span class="status-pill status-draft">Draft</span>
                        @elseif($faq->status === 'pending')
                            <span class="status-pill status-pending">Menunggu Validasi</span>
                        @elseif($faq->status === 'published')
                            <span class="status-pill status-approved">Disetujui / Publik</span>
                        @elseif($faq->status === 'rejected')
                            <span class="status-pill status-rejected">Perlu Revisi (Ditolak)</span>
                        @elseif($faq->status === 'inactive')
                            <span class="status-pill" style="background-color: #F1F5F9; color: #475569; border: 1px solid #CBD5E1;">Nonaktif</span>
                        @endif
                    </span>
                </div>

                <h2 class="vd-detail-pertanyaan" style="{{ $faq->status === 'inactive' ? 'color: #64748B; text-decoration: line-through;' : '' }}">
                    {{ $faq->pertanyaan }}
                </h2>
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

        {{-- 4. Riwayat Validasi / Review --}}
        @if($faq->reviews->count() > 0)
            <div class="vd-detail-card" style="margin-top: 16px;">
                <div class="vd-history-header">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    Riwayat Validasi ({{ $faq->reviews->count() }})
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
                                <span class="vd-review-history-reviewer">oleh <strong>{{ $review->user?->name ?? 'Validator' }}</strong></span>
                                <span class="vd-review-history-date">{{ $review->created_at->format('d M Y H:i') }}</span>
                            </div>
                            @if($review->catatan)
                                <div class="vd-review-history-catatan">
                                    <strong>Catatan:</strong> &ldquo;{{ $review->catatan }}&rdquo;
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    {{-- ── Panel Kanan: Jejak Audit & Tombol Aksi ── --}}
    <div class="validator-detail-actions">

        {{-- Kartu Informasi Jejak Audit (Pembuat & Penonaktifan) --}}
        <div class="vd-action-card">
            <div class="vd-action-card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366F1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                Informasi &amp; Jejak Audit
            </div>

            <div style="font-size: 13px; color: var(--text-body); display: flex; flex-direction: column; gap: 10px; margin-top: 12px;">
                <div style="padding-bottom: 10px; border-bottom: 1px solid #F1F5F9;">
                    <div style="font-size: 11.5px; color: var(--text-muted);">Dibuat Oleh</div>
                    <div style="font-weight: 600; color: var(--text-heading);">{{ $faq->user?->name ?? '-' }}</div>
                    <div style="font-size: 12px; color: var(--text-subtle);">{{ $faq->created_at->format('d M Y, H:i') }}</div>
                </div>

                <div style="padding-bottom: 10px; border-bottom: 1px solid #F1F5F9;">
                    <div style="font-size: 11.5px; color: var(--text-muted);">Terakhir Diperbarui</div>
                    <div style="font-weight: 600; color: var(--text-heading);">{{ $faq->updated_at->format('d M Y, H:i') }}</div>
                    <div style="font-size: 12px; color: var(--text-subtle);">{{ $faq->updated_at->diffForHumans() }}</div>
                </div>

                @if($faq->status === 'inactive' && $faq->deactivated_at)
                    <div style="padding: 10px 12px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px;">
                        <div style="font-size: 11.5px; color: #991B1B; font-weight: 700;">Dinonaktifkan Oleh</div>
                        <div style="font-weight: 700; color: #DC2626;">{{ $faq->deactivatedByUser?->name ?? '-' }}</div>
                        <div style="font-size: 12px; color: #B91C1C;">Pada {{ $faq->deactivated_at->format('d M Y, H:i') }}</div>
                    </div>
                @endif

                <div>
                    <div style="font-size: 11.5px; color: var(--text-muted);">Statistik Publik</div>
                    <div style="font-weight: 600; color: var(--text-heading);">
                        {{ $faq->views ?? 0 }}x dibaca &bull; {{ $faq->helpful ?? 0 }} terbantu
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu Tindakan Sesuai Status FAQ --}}
        <div class="vd-action-card">
            <div class="vd-action-card-title">Tindakan</div>

            @if($faq->status === 'published')
                <p class="vd-action-card-desc">
                    FAQ yang sudah dipublikasikan tidak dapat diedit atau dihapus. Jika terdapat perubahan informasi, nonaktifkan FAQ ini lalu buat pengajuan FAQ baru.
                </p>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <form action="{{ route('faqs.unpublish', $faq->id) }}" method="POST" onsubmit="return confirm('Nonaktifkan FAQ ini dan langsung buat pengajuan FAQ baru sebagai penggantinya?')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="create_replacement" value="1">
                        <button type="submit" class="btn-modal-submit" style="width: 100%; justify-content: center; padding: 10px 16px;">
                            Nonaktifkan &amp; Buat FAQ Baru
                        </button>
                    </form>

                    <form action="{{ route('faqs.unpublish', $faq->id) }}" method="POST" onsubmit="return confirm('Nonaktifkan informasi FAQ ini dari publik?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-secondary" style="width: 100%; justify-content: center; color: #D97706; border-color: #FDE68A; background: #FFFBEB;">
                            Nonaktifkan FAQ Saja
                        </button>
                    </form>
                </div>
            @elseif($faq->status === 'inactive')
                <p class="vd-action-card-desc">
                    FAQ ini sudah dinonaktifkan dan tidak tampil di halaman pencarian mahasiswa. Anda dapat membuat FAQ baru dengan menyalin isi dari FAQ ini.
                </p>
                <a href="{{ route('faqs.create', ['from_faq' => $faq->id]) }}" class="btn-modal-submit" style="width: 100%; justify-content: center; padding: 10px 16px; text-decoration: none;">
                    Buat FAQ Baru dari Ini
                </a>
            @else
                <p class="vd-action-card-desc">
                    FAQ ini belum dipublikasikan dan masih dapat disunting atau dihapus.
                </p>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ route('faqs.edit', $faq->id) }}" class="btn-modal-submit" style="width: 100%; justify-content: center; padding: 10px 16px; text-decoration: none;">
                        {{ $faq->status === 'rejected' ? 'Buka Halaman Revisi' : 'Edit Informasi FAQ' }}
                    </a>

                    <form action="{{ route('faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus draf pengajuan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-secondary" style="width: 100%; justify-content: center; color: #DC2626; border-color: #FECACA; background: #FEF2F2;">
                            Hapus Draf
                        </button>
                    </form>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection

