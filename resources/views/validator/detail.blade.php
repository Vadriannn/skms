@extends('layouts.app', [
    'title' => 'Detail informasi #' . $faq->id . ' - SKMS'
])

@section('content')
<div>
    <!-- Back Link & Title Header (Sesuai Gambar 4) -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('validator.antrian') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #8B0D18; font-size: 13px; font-weight: 600; text-decoration: none; margin-bottom: 10px;">
            <span>&larr;</span>
            <span>Kembali ke antrean</span>
        </a>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
            <h1 class="skmk-page-main-title" style="margin: 0;">Detail informasi</h1>
            <span style="background: #F6F0F0; color: #3E2F30; border: 1px solid #ECE4E5; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 9999px;">
                FAQ #{{ $faq->id }}
            </span>
        </div>
        <p class="skmk-page-subtitle">Tinjau konten, kemiripan, dan seluruh riwayat keputusannya.</p>
    </div>

    <div class="validator-detail-container">

        {{-- ── Panel Kiri: Peringatan Duplikasi, Banner Pembaruan, & Kartu Konten FAQ ── --}}
        <div class="validator-detail-main">

            {{-- 1. Warm Amber Duplicate Warning Box (Sesuai Gambar 4) --}}
            @if($faq->latestDuplicateLog)
                @php
                    $dupLog = $faq->latestDuplicateLog;
                    $simFaq = $dupLog->similarFaq;
                    $dupScoreVal = (float) $dupLog->score;
                    $dupPct = $dupScoreVal <= 1 ? round($dupScoreVal * 100) : round($dupScoreVal);
                @endphp
                <div style="background-color: #FFF6E8; border: 1px solid #F2D6A7; border-radius: 14px; padding: 18px 22px; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #9A5509; font-size: 14.5px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9A5509" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            <span>Indikasi duplikasi pertanyaan</span>
                        </div>
                        <span style="background-color: #F8E5C2; color: #9A5509; padding: 3px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">
                            {{ $dupPct }}% mirip
                        </span>
                    </div>
                    <p style="margin: 0 0 14px 0; color: #8A4D0B; font-size: 13px; line-height: 1.55;">
                        Pengajuan dilanjutkan meskipun sistem mendeteksi kemiripan tinggi. Bandingkan dengan informasi terbit sebelum mengambil keputusan.
                    </p>

                    @if($simFaq)
                        <div style="background: #FFFFFF; border: 1px solid #F3DEC0; border-radius: 10px; padding: 14px 16px; margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 6px;">
                                <span style="font-size: 11px; font-weight: 700; color: #948486; text-transform: uppercase; letter-spacing: 0.4px;">
                                    FAQ PEMBANDING #{{ $simFaq->id }}
                                </span>
                                <a href="{{ route('validator.faq.show', $simFaq->id) }}" style="font-size: 12.5px; font-weight: 600; color: #8B0D18; text-decoration: none;">
                                    Lihat pembanding &#8599;
                                </a>
                            </div>
                            <div style="font-weight: 700; color: #1F1415; font-size: 14px; margin-bottom: 6px;">
                                &ldquo;{{ $simFaq->pertanyaan }}&rdquo;
                            </div>
                            <div style="font-size: 13px; color: #6B5B5D; line-height: 1.5;">
                                {{ Str::limit(strip_tags($simFaq->jawaban), 180) }}
                            </div>
                        </div>
                    @endif

                    <div style="font-size: 11.5px; color: #A66316; font-weight: 500;">
                        Metode TF-IDF + Cosine Similarity &bull; Ambang kemiripan &ge; 60%
                    </div>
                </div>
            @endif

            {{-- 2. Soft Blue Update/Revision Context Banner (Sesuai Gambar 4) --}}
            @if($faq->submission_type === 'edit')
                <div style="background-color: #EEF3FA; border: 1px solid #D3E0F2; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #2D4E85; font-size: 13.5px; margin-bottom: 4px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        <span>Pembaruan informasi yang sudah terbit</span>
                    </div>
                    <p style="margin: 0; color: #3D5E94; font-size: 12.5px; line-height: 1.55;">
                        Ini bukan revisi dari penolakan. FAQ sebelumnya sudah disetujui dan terbit. Perubahan pertanyaan atau jawaban diajukan kembali untuk diverifikasi sebelum tayang ke publik.
                    </p>
                </div>
            @elseif($faq->submission_type === 'revision')
                <div style="background-color: #EEF3FA; border: 1px solid #D3E0F2; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #2D4E85; font-size: 13.5px; margin-bottom: 4px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span>Hasil perbaikan dari catatan penolakan</span>
                    </div>
                    <p style="margin: 0; color: #3D5E94; font-size: 12.5px; line-height: 1.55;">
                        FAQ ini sebelumnya ditolak oleh Validator. Data Entry telah melakukan perbaikan sesuai catatan validator dan mengajukannya kembali untuk diperiksa.
                    </p>
                </div>
            @endif

            {{-- 3. Kartu Konten FAQ (Sesuai Gambar 4) --}}
            <div class="vd-detail-card">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px; flex-wrap: wrap;">
                    <div style="font-size: 12.5px; color: #6B5B5D;">
                        Diajukan oleh <strong style="color: #3E2F30;">{{ $faq->user?->name ?? 'Tidak diketahui' }}</strong>
                        &bull; {{ $faq->created_at->format('d F Y, H:i') }}
                    </div>
                    <div>
                        @if($faq->status === 'pending')
                            <span class="status-pill status-pending">Menunggu</span>
                        @elseif($faq->status === 'published')
                            <span class="status-pill status-approved">Disetujui</span>
                        @elseif($faq->status === 'rejected')
                            <span class="status-pill status-rejected">Ditolak</span>
                        @elseif($faq->status === 'inactive')
                            <span class="status-pill" style="background-color: #F6F0F0; color: #6B5B5D; border: 1px solid #ECE4E5;">Nonaktif</span>
                        @else
                            <span class="status-pill status-draft">Draft</span>
                        @endif
                    </div>
                </div>

                <h2 class="vd-detail-pertanyaan" style="font-size: 20px; margin-bottom: 12px;">{{ $faq->pertanyaan }}</h2>

                {{-- Kategori --}}
                @if($faq->categories->count() > 0)
                    <div class="vd-detail-categories" style="padding-bottom: 18px; border-bottom: 1px solid #F3ECEC; margin-bottom: 18px;">
                        @foreach($faq->categories as $cat)
                            <span class="category-pill">{{ $cat->nama }}</span>
                        @endforeach
                    </div>
                @endif

                {{-- Jawaban --}}
                <div class="vd-detail-jawaban-label">JAWABAN / PENJELASAN</div>
                <div class="vd-detail-jawaban-content">{!! nl2br(e($faq->jawaban)) !!}</div>

                {{-- Media Video (Jika ada) --}}
                @if($faq->link_video)
                    <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #F3ECEC;">
                        <div style="font-size: 11.5px; font-weight: 700; color: #948486; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                            VIDEO PANDUAN / TUTORIAL
                        </div>
                        @if($faq->youtube_embed_url)
                            <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 10px; background: #000;">
                                <iframe src="{{ $faq->youtube_embed_url }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allowfullscreen></iframe>
                            </div>
                        @else
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                                <span style="font-size: 13px; color: #1F1415; word-break: break-all;">{{ $faq->link_video }}</span>
                                <a href="{{ $faq->link_video }}" target="_blank" rel="noopener noreferrer" style="font-size: 12.5px; font-weight: 600; color: #DC2626; text-decoration: none; white-space: nowrap;">
                                    Buka Video &#8599;
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Berkas Lampiran (Jika ada) --}}
                @if($faq->file_lampiran)
                    <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #F3ECEC;">
                        <div style="font-size: 11.5px; font-weight: 700; color: #948486; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                            BERKAS LAMPIRAN
                        </div>
                        <div style="background: #FFFFFF; border: 1px solid #F1CFD3; border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
                                <span style="width: 32px; height: 32px; border-radius: 6px; background: #FDF2F3; color: #8B0D18; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; text-transform: uppercase; flex-shrink: 0;">
                                    {{ $faq->file_extension ?: 'FILE' }}
                                </span>
                                <span style="font-size: 13px; font-weight: 600; color: #1F1415; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $faq->file_nama_asli ?: basename($faq->file_lampiran) }}">
                                    {{ $faq->file_nama_asli ?: basename($faq->file_lampiran) }}
                                </span>
                            </div>
                            <a href="{{ route('faqs.download', $faq->id) }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: #8B0D18; color: #FFFFFF; font-size: 12px; font-weight: 600; border-radius: 6px; text-decoration: none; white-space: nowrap;">
                                Unduh
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>

        {{-- ── Panel Kanan: Form Keputusan / Status & Riwayat Review (Sesuai Gambar 4) ── --}}
        <div class="validator-detail-actions">

            @php
                $revisionCount = $faq->reviews->where('keputusan', 'revision')->count();
            @endphp

            @if($faq->status === 'pending')
                {{-- Form Setujui --}}
                <div class="vd-action-card approve-card" style="border-top-color: #8B0D18;">
                    <div class="vd-action-card-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B0D18" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        Setujui FAQ
                    </div>
                    <p class="vd-action-card-desc">FAQ akan dipublikasikan dan dapat dilihat oleh mahasiswa.</p>
                    <form action="{{ route('validator.faq.approve', $faq->id) }}" method="POST" id="approveForm">
                        @csrf
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label class="form-label" for="approve_catatan">Catatan (opsional)</label>
                            <textarea id="approve_catatan" name="catatan" rows="2" class="form-control vd-textarea" placeholder="Tambahkan catatan persetujuan..."></textarea>
                        </div>
                        <button type="button" class="btn-vd-approve" style="background: #8B0D18;" onclick="confirmAction('approve', {{ $faq->id }})">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Setujui &amp; Publikasikan
                        </button>
                    </form>
                </div>

                {{-- Form Ajukan Revisi (Maksimal 2 kali) --}}
                @if($revisionCount < 2)
                    <div class="vd-action-card revision-card" style="border-top: 3px solid #D97706;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <div class="vd-action-card-title" style="color: #B45309; margin-bottom: 0;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Ajukan Revisi ke Data Entry
                            </div>
                            <span style="background: #FEF3C7; color: #92400E; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 9999px;">
                                Revisi {{ $revisionCount + 1 }}/2
                            </span>
                        </div>
                        <p class="vd-action-card-desc">FAQ akan dikembalikan ke Data Entry untuk diperbaiki (muncul sebagai pending) sesuai deskripsi yang Anda berikan.</p>
                        <form action="{{ route('validator.faq.revision', $faq->id) }}" method="POST" id="revisionForm">
                            @csrf
                            <div class="form-group" style="margin-bottom: 14px;">
                                <label class="form-label" for="revision_catatan">
                                    Deskripsi Revisi <span class="required" style="color: #DC2626;">*</span>
                                </label>
                                <textarea id="revision_catatan" name="catatan" rows="3" class="form-control vd-textarea" placeholder="Jelaskan secara detail bagian yang perlu diperbaiki oleh Data Entry..." required></textarea>
                                @error('catatan')
                                    <div class="vd-field-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="button" class="btn-vd-approve" style="background: #D97706;" onclick="confirmAction('revision', {{ $faq->id }})">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Ajukan Revisi ({{ $revisionCount + 1 }}/2)
                            </button>
                        </form>
                    </div>
                @else
                    {{-- Batas revisi (2x) telah tercapai --}}
                    <div class="vd-action-card" style="background: #FFFBEB; border: 1px solid #FDE68A; border-top: 3px solid #D97706;">
                        <div style="display: flex; align-items: center; gap: 8px; color: #92400E; font-weight: 700; font-size: 13.5px; margin-bottom: 6px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            Batas Revisi Tercapai (2/2)
                        </div>
                        <p style="margin: 0; font-size: 12.5px; color: #78350F; line-height: 1.55;">
                            FAQ ini sudah pernah diajukan revisi sebanyak <strong>2 kali</strong>. Kesempatan revisi telah habis, silakan putuskan untuk <strong>menyetujui</strong> atau <strong>menolak</strong> pengajuan FAQ ini.
                        </p>
                    </div>
                @endif

                {{-- Form Tolak (Permanen) --}}
                <div class="vd-action-card reject-card">
                    <div class="vd-action-card-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#C93535" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                        Tolak FAQ (Permanen)
                    </div>
                    <p class="vd-action-card-desc">FAQ akan ditolak. Data Entry <strong>tidak dapat merevisi atau mengajukan ulang</strong> pengajuan ini.</p>
                    <form action="{{ route('validator.faq.reject', $faq->id) }}" method="POST" id="rejectForm">
                        @csrf
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label class="form-label" for="reject_catatan">
                                Alasan Penolakan <span class="required">*</span>
                            </label>
                            <textarea id="reject_catatan" name="catatan" rows="3" class="form-control vd-textarea" placeholder="Jelaskan alasan penolakan pengajuan FAQ ini..." required></textarea>
                            @error('catatan')
                                <div class="vd-field-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="button" class="btn-vd-reject" onclick="confirmAction('reject', {{ $faq->id }})">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Tolak FAQ
                        </button>
                    </form>
                </div>
            @else
                {{-- Kartu Status FAQ Telah Diproses (Persis Gambar 4) --}}
                <div class="vd-action-card">
                    <div style="margin-bottom: 12px;">
                        @if($faq->status === 'published')
                            <span style="background: #FDF2F3; color: #8B0D18; padding: 4px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">&bull; Disetujui</span>
                        @elseif($faq->status === 'inactive')
                            <span style="background: #F6F0F0; color: #6B5B5D; padding: 4px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">&bull; Nonaktif</span>
                        @elseif($faq->status === 'rejected')
                            <span style="background: #FDF2F2; color: #C93535; padding: 4px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">&bull; Ditolak (Tidak dapat direvisi)</span>
                        @endif
                    </div>

                    <div style="font-size: 16px; font-weight: 700; color: #1F1415; margin-bottom: 6px;">FAQ telah diproses</div>
                    <p class="vd-action-card-desc">
                        Informasi ini sudah berstatus <strong>{{ $faq->status === 'inactive' ? 'nonaktif' : ($faq->status === 'published' ? 'disetujui' : 'ditolak') }}</strong>. Keputusan validasi tidak dapat diubah melalui panel ini.
                    </p>

                    @if($faq->status === 'inactive' && $faq->deactivated_at)
                        <div style="display: flex; justify-content: space-between; font-size: 12.5px; padding: 10px 0; border-top: 1px solid #F3ECEC; margin-bottom: 10px;">
                            <span style="color: #6B5B5D;">Dinonaktifkan</span>
                            <strong style="color: #1F1415;">{{ $faq->deactivated_at->format('d M Y') }}</strong>
                        </div>
                    @endif

                    <a href="{{ route('validator.antrian') }}" class="btn-secondary" style="font-size: 13px; font-weight: 600; color: #1F1415;">
                        Kembali ke antrean
                    </a>
                </div>
            @endif

            {{-- Kartu Riwayat Review di Kolom Kanan (Persis Gambar 4) --}}
            @if($faq->reviews->count() > 0)
                <div class="vd-action-card">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
                        <span style="font-size: 15px; font-weight: 700; color: #1F1415;">Riwayat review</span>
                        <span style="background: #FDF2F3; color: #8B0D18; font-size: 11.5px; font-weight: 700; padding: 2px 8px; border-radius: 9999px;">
                            {{ $faq->reviews->count() }}
                        </span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        @foreach($faq->reviews->sortByDesc('created_at') as $review)
                            <div style="padding-bottom: 14px; border-bottom: 1px solid #F3ECEC;">
                                <div style="margin-bottom: 6px;">
                                    @if($review->keputusan === 'approved')
                                        <span style="background: #FDF2F3; color: #8B0D18; padding: 3px 9px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">
                                            &#10003; Disetujui
                                        </span>
                                    @elseif($review->keputusan === 'revision')
                                        <span style="background: #FEF3C7; color: #92400E; padding: 3px 9px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">
                                            &#8635; Revisi
                                        </span>
                                    @else
                                        <span style="background: #FDF2F2; color: #C93535; padding: 3px 9px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">
                                            &#10005; Ditolak
                                        </span>
                                    @endif
                                </div>
                                <div style="font-size: 13px; font-weight: 600; color: #1F1415;">
                                    oleh {{ $review->user?->name ?? 'Validator' }}
                                </div>
                                <div style="font-size: 11.5px; color: #948486; margin-top: 2px;">
                                    {{ $review->created_at->format('d M Y, H:i') }}
                                </div>

                                @if($review->catatan)
                                    <div style="margin-top: 8px; padding: 10px 12px; background: #FAF8F7; border-radius: 8px; font-size: 12.5px; color: #3E2F30;">
                                        <div style="font-size: 11px; color: #948486; margin-bottom: 2px;">
                                            {{ $review->keputusan === 'revision' ? 'Deskripsi Revisi' : 'Catatan' }}
                                        </div>
                                        <div>{{ $review->catatan }}</div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>
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

        const modal = document.getElementById('confirmModal');
        const title = document.getElementById('confirmModalTitle');
        const desc  = document.getElementById('confirmModalDesc');
        const btn   = document.getElementById('confirmModalBtn');

        if (action === 'approve') {
            title.textContent = 'Setujui FAQ ini?';
            desc.textContent  = 'FAQ akan dipublikasikan dan dapat dilihat oleh semua pengguna sistem.';
            btn.textContent   = 'Ya, Setujui';
            btn.className     = 'btn-modal-submit';
            btn.style.backgroundColor = '#8B0D18';
        } else if (action === 'revision') {
            const catatan = document.getElementById('revision_catatan') ? document.getElementById('revision_catatan').value.trim() : '';
            if (!catatan || catatan.length < 5) {
                alert('Deskripsi revisi wajib diisi (minimal 5 karakter) sebelum mengajukan revisi.');
                if (document.getElementById('revision_catatan')) document.getElementById('revision_catatan').focus();
                return;
            }
            title.textContent = 'Ajukan Revisi ke Data Entry?';
            desc.textContent  = 'FAQ akan dikembalikan ke Data Entry dengan catatan revisi yang Anda tulis agar dapat diperbaiki.';
            btn.textContent   = 'Ya, Ajukan Revisi';
            btn.className     = 'btn-modal-submit';
            btn.style.backgroundColor = '#D97706';
        } else {
            const catatan = document.getElementById('reject_catatan').value.trim();
            if (!catatan || catatan.length < 5) {
                alert('Alasan penolakan wajib diisi (minimal 5 karakter) sebelum menolak.');
                document.getElementById('reject_catatan').focus();
                return;
            }
            title.textContent = 'Tolak Pengajuan FAQ?';
            desc.textContent  = 'FAQ akan ditolak secara permanen dan Data Entry TIDAK DAPAT merevisi atau mengajukan ulang FAQ ini.';
            btn.textContent   = 'Ya, Tolak FAQ';
            btn.className     = 'btn-modal-submit btn-confirm-reject';
            btn.style.backgroundColor = '#DC2626';
        }

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    document.getElementById('confirmModalBtn').addEventListener('click', function() {
        if (pendingAction === 'approve') {
            document.getElementById('approveForm').submit();
        } else if (pendingAction === 'revision') {
            document.getElementById('revisionForm').submit();
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
