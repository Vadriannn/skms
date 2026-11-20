@extends('layouts.app', [
    'title' => 'Detail informasi #' . $faq->id . ' - SKMS'
])

@section('content')
<div>
    <!-- Back Link & Title Header (Sesuai Gambar 4) -->
    <div style="margin-bottom: 20px;">
        <a href="{{ request('from') === 'index' ? route('faqs.index') : route('faqs.submissions', ['status' => $faq->status]) }}" style="display: inline-flex; align-items: center; gap: 6px; color: #0D7F74; font-size: 13px; font-weight: 600; text-decoration: none; margin-bottom: 10px;">
            <span>&larr;</span>
            <span>{{ request('from') === 'index' ? 'Kembali ke daftar informasi' : 'Kembali ke pengajuan & draf' }}</span>
        </a>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
            <h1 class="skmk-page-main-title" style="margin: 0;">Detail informasi</h1>
            <span style="background: #F0F4F2; color: #3D524E; border: 1px solid #E2E9E6; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 9999px;">
                FAQ #{{ $faq->id }}
            </span>
        </div>
        <p class="skmk-page-subtitle">Tinjau konten, kemiripan, dan seluruh riwayat keputusannya.</p>
    </div>

    <div class="validator-detail-container">

        {{-- ── Panel Kiri: Peringatan Duplikasi & Kartu Konten FAQ ── --}}
        <div class="validator-detail-main">

            {{-- 1. Warm Amber Duplicate Warning Box (Sesuai Gambar 4) --}}
            @if($faq->latestDuplicateLog && !in_array($faq->status, ['published']))
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
                                <span style="font-size: 11px; font-weight: 700; color: #8FA19D; text-transform: uppercase; letter-spacing: 0.4px;">
                                    FAQ PEMBANDING #{{ $simFaq->id }}
                                </span>
                                <a href="{{ route('faqs.show', $simFaq->id) }}" style="font-size: 12.5px; font-weight: 600; color: #0D7F74; text-decoration: none;">
                                    Lihat pembanding &#8599;
                                </a>
                            </div>
                            <div style="font-weight: 700; color: #16322F; font-size: 14px; margin-bottom: 6px;">
                                &ldquo;{{ $simFaq->pertanyaan }}&rdquo;
                            </div>
                            <div style="font-size: 13px; color: #647874; line-height: 1.5;">
                                {{ Str::limit(strip_tags($simFaq->jawaban), 180) }}
                            </div>
                        </div>
                    @endif

                    <div style="font-size: 11.5px; color: #A66316; font-weight: 500;">
                        Metode TF-IDF + Cosine Similarity &bull; Ambang kemiripan &ge; 60%
                    </div>
                </div>
            @endif

            {{-- 2. Banner Catatan Revisi / Alasan Penolakan dari Validator --}}
            @if($faq->latestReview && $faq->latestReview->keputusan === 'revision' && $faq->latestReview->catatan)
                <div style="background-color: #FFFBEB; border: 1px solid #FDE68A; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px;">
                    <div style="font-weight: 700; color: #92400E; font-size: 13.5px; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Catatan revisi dari Validator (Revisi {{ $faq->reviews->where('keputusan', 'revision')->count() }}/2)</span>
                    </div>
                    <p style="margin: 0; color: #78350F; font-size: 13px; line-height: 1.55;">
                        &ldquo;{{ $faq->latestReview->catatan }}&rdquo;
                    </p>
                </div>
            @elseif($faq->status === 'rejected' && $faq->latestReview && $faq->latestReview->catatan)
                <div style="background-color: #FDF2F2; border: 1px solid #F5C6C6; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px;">
                    <div style="font-weight: 700; color: #C93535; font-size: 13.5px; margin-bottom: 4px;">
                        Alasan penolakan dari Validator (Permanen)
                    </div>
                    <p style="margin: 0; color: #991B1B; font-size: 13px; line-height: 1.55;">
                        &ldquo;{{ $faq->latestReview->catatan }}&rdquo;
                    </p>
                    <div style="margin-top: 6px; font-size: 12px; color: #B91C1C;">
                        Pengajuan FAQ ini telah ditolak secara permanen dan tidak dapat direvisi atau diajukan ulang.
                    </div>
                </div>
            @endif

            {{-- 3. Kartu Konten FAQ (Sesuai Gambar 4) --}}
            <div class="vd-detail-card">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px; flex-wrap: wrap;">
                    <div style="font-size: 12.5px; color: #647874;">
                        Diajukan oleh <strong style="color: #3D524E;">{{ $faq->user?->name ?? '-' }}</strong>
                        &bull; {{ $faq->created_at->format('d F Y, H:i') }}
                    </div>
                    <div>
                        @if($faq->status === 'draft')
                            <span class="status-pill status-draft">Draft</span>
                        @elseif($faq->status === 'pending')
                            <span class="status-pill status-pending">Menunggu</span>
                        @elseif($faq->status === 'published')
                            <span class="status-pill status-approved">Disetujui</span>
                        @elseif($faq->status === 'rejected')
                            <span class="status-pill status-rejected">Ditolak</span>
                        @elseif($faq->status === 'inactive')
                            <span class="status-pill" style="background-color: #F0F4F2; color: #4D6661; border: 1px solid #E2E9E6;">Nonaktif</span>
                        @endif
                    </div>
                </div>

                <h2 class="vd-detail-pertanyaan" style="font-size: 20px; margin-bottom: 12px; {{ $faq->status === 'inactive' ? 'color: #647874; text-decoration: line-through;' : '' }}">
                    {{ $faq->pertanyaan }}
                </h2>

                {{-- Kategori --}}
                @if($faq->categories->count() > 0)
                    <div class="vd-detail-categories" style="padding-bottom: 18px; border-bottom: 1px solid #EDF2F0; margin-bottom: 18px;">
                        @foreach($faq->categories as $cat)
                            <span class="category-pill">{{ $cat->nama }}</span>
                        @endforeach
                    </div>
                @endif

                {{-- Jawaban --}}
                <div class="vd-detail-jawaban-label">JAWABAN / PENJELASAN</div>
                <div class="vd-detail-jawaban-content">{!! nl2br(e($faq->jawaban)) !!}</div>
            </div>

        </div>

        {{-- ── Panel Kanan: Status/Tindakan & Riwayat Review (Sesuai Gambar 4) ── --}}
        <div class="validator-detail-actions">

            <div class="vd-action-card">
                <div style="margin-bottom: 12px;">
                    @if($faq->status === 'published')
                        <span style="background: #E8F5F1; color: #0D7F74; padding: 4px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">&bull; Disetujui</span>
                    @elseif($faq->status === 'inactive')
                        <span style="background: #F0F4F2; color: #4D6661; padding: 4px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">&bull; Nonaktif</span>
                    @elseif($faq->status === 'rejected')
                        <span style="background: #FDF2F2; color: #C93535; padding: 4px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">&bull; Perlu revisi</span>
                    @elseif($faq->status === 'pending')
                        <span style="background: #FFF8EB; color: #B4690E; padding: 4px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">&bull; Menunggu validasi</span>
                    @else
                        <span style="background: #FFF4EB; color: #C95B16; padding: 4px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">&bull; Draft</span>
                    @endif
                </div>

                @if($faq->status === 'published')
                    <div style="font-size: 16px; font-weight: 700; color: #16322F; margin-bottom: 6px;">FAQ telah terbit</div>
                    <p class="vd-action-card-desc">
                        Informasi yang sudah terbit tidak dapat diedit atau dihapus. Jika ada pembaruan, nonaktifkan FAQ ini lalu buat pengajuan baru.
                    </p>
                @elseif($faq->status === 'inactive')
                    <div style="font-size: 16px; font-weight: 700; color: #16322F; margin-bottom: 6px;">FAQ telah diproses</div>
                    <p class="vd-action-card-desc">
                        Informasi ini sudah berstatus nonaktif dan tidak tampil di halaman mahasiswa.
                    </p>
                @else
                    <div style="font-size: 16px; font-weight: 700; color: #16322F; margin-bottom: 6px;">Pengelolaan draf</div>
                    <p class="vd-action-card-desc">
                        Informasi ini belum terbit dan masih dapat diperbarui atau dihapus.
                    </p>
                @endif

                <div style="font-size: 12.5px; border-top: 1px solid #EDF2F0; padding-top: 10px; margin-bottom: 14px; display: flex; flex-direction: column; gap: 6px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #647874;">Dibuat oleh</span>
                        <strong style="color: #16322F;">{{ $faq->user?->name ?? '-' }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #647874;">Tanggal dibuat</span>
                        <strong style="color: #16322F;">{{ $faq->created_at->format('d M Y') }}</strong>
                    </div>
                    @if($faq->status === 'inactive' && $faq->deactivated_at)
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #C93535;">Dinonaktifkan oleh</span>
                            <strong style="color: #C93535;">{{ $faq->deactivatedByUser?->name ?? '-' }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #647874;">Dinonaktifkan</span>
                            <strong style="color: #16322F;">{{ $faq->deactivated_at->format('d M Y, H:i') }}</strong>
                        </div>
                    @endif
                </div>

                @if(Auth::check() && in_array(Auth::user()->role, ['data_entry', 'super_admin']))
                    @if($faq->status === 'published')
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <form action="{{ route('faqs.unpublish', $faq->id) }}" method="POST" onsubmit="return confirm('Nonaktifkan FAQ ini dan langsung buat pengajuan FAQ baru sebagai penggantinya?')">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="create_replacement" value="1">
                                <button type="submit" class="btn-modal-submit" style="width: 100%; justify-content: center; padding: 10px 16px;">
                                    Nonaktifkan &amp; buat baru
                                </button>
                            </form>
                            <form action="{{ route('faqs.unpublish', $faq->id) }}" method="POST" onsubmit="return confirm('Nonaktifkan informasi FAQ ini dari publik?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-secondary" style="width: 100%; justify-content: center;">
                                    Nonaktifkan saja
                                </button>
                            </form>
                        </div>
                    @elseif($faq->status === 'inactive')
                        <a href="{{ route('faqs.create', ['from_faq' => $faq->id]) }}" class="btn-modal-submit" style="width: 100%; justify-content: center; padding: 10px 16px; text-decoration: none;">
                            Buat FAQ baru dari ini
                        </a>
                    @elseif($faq->status === 'rejected')
                        {{-- DITOLAK: TIDAK BISA DIREVISI --}}
                        <div style="padding: 10px 14px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px; font-size: 12.5px; color: #DC2626; font-weight: 600; text-align: center; margin-bottom: 8px;">
                            Pengajuan ditolak (Tidak dapat direvisi)
                        </div>
                        <form action="{{ route('faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Hapus pengajuan yang ditolak ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-secondary" style="width: 100%; justify-content: center; color: #C93535; border-color: #F5C6C6;">
                                Hapus pengajuan
                            </button>
                        </form>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <a href="{{ route('faqs.edit', $faq->id) }}" class="btn-modal-submit" style="width: 100%; justify-content: center; padding: 10px 16px; text-decoration: none; {{ ($faq->latestReview && $faq->latestReview->keputusan === 'revision') ? 'background-color: #D97706;' : '' }}">
                                {{ ($faq->latestReview && $faq->latestReview->keputusan === 'revision') ? 'Buka halaman revisi' : 'Edit informasi' }}
                            </a>
                            <form action="{{ route('faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Hapus draf pengajuan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-secondary" style="width: 100%; justify-content: center; color: #C93535; border-color: #F5C6C6;">
                                    Hapus draf
                                </button>
                            </form>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Kartu Riwayat Review di Kolom Kanan (Persis Gambar 4) --}}
            @if($faq->reviews->count() > 0)
                <div class="vd-action-card">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
                        <span style="font-size: 15px; font-weight: 700; color: #16322F;">Riwayat review</span>
                        <span style="background: #E8F5F1; color: #0D7F74; font-size: 11.5px; font-weight: 700; padding: 2px 8px; border-radius: 9999px;">
                            {{ $faq->reviews->count() }}
                        </span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        @foreach($faq->reviews->sortByDesc('created_at') as $review)
                            <div style="padding-bottom: 14px; border-bottom: 1px solid #EDF2F0;">
                                <div style="margin-bottom: 6px;">
                                    @if($review->keputusan === 'approved')
                                        <span style="background: #E8F5F1; color: #0D7F74; padding: 3px 9px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">
                                            &#10003; Disetujui
                                        </span>
                                    @elseif($review->keputusan === 'revision')
                                        <span style="background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; padding: 3px 9px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">
                                            &#8635; Revisi
                                        </span>
                                    @else
                                        <span style="background: #FDF2F2; color: #C93535; padding: 3px 9px; border-radius: 9999px; font-size: 11.5px; font-weight: 700;">
                                            &#10005; Ditolak
                                        </span>
                                    @endif
                                </div>
                                <div style="font-size: 13px; font-weight: 600; color: #16322F;">
                                    oleh {{ $review->user?->name ?? 'Validator' }}
                                </div>
                                <div style="font-size: 11.5px; color: #8FA19D; margin-top: 2px;">
                                    {{ $review->created_at->format('d M Y, H:i') }}
                                </div>

                                @if($review->catatan)
                                    <div style="margin-top: 8px; padding: 10px 12px; background: #F6F8F7; border-radius: 8px; font-size: 12.5px; color: #3D524E;">
                                        <div style="font-size: 11px; color: #8FA19D; margin-bottom: 2px;">
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
@endsection
