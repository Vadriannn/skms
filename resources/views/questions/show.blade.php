@extends('layouts.app', [
    'title' => 'Detail Tiket Pertanyaan #' . $ticket->id . ' - SKMS'
])

@section('content')
<div class="validator-page-container">

    <!-- Back Link & Page Header -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('questions.index') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: #0D7F74; text-decoration: none; margin-bottom: 10px;">
            <span>&larr;</span>
            <span>Kembali ke antrean tiket pertanyaan</span>
        </a>
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <h1 class="skmk-page-main-title" style="margin: 0;">Detail tiket pertanyaan</h1>
            <span style="background: #F0F4F2; color: #4D6661; border: 1px solid #E2E9E6; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 6px;">
                Tiket #{{ $ticket->id }}
            </span>
        </div>
        <p class="skmk-page-subtitle" style="margin-top: 4px;">
            Tinjau pertanyaan mahasiswa beserta rekomendasi kemiripan FAQ dari repositori SKMS.
        </p>
    </div>

    <!-- 2-Column Layout -->
    <div class="validator-detail-container">
        <!-- KOLOM KIRI: PERTANYAAN MAHASISWA & KANDIDAT KEMIRIPAN TF-IDF -->
        <div class="validator-detail-main">

            <!-- Kartu Pertanyaan Mahasiswa -->
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 24px 26px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding-bottom: 14px; margin-bottom: 16px; border-bottom: 1px solid #EDF2F0;">
                    <div style="font-size: 12.5px; color: #647874;">
                        Diajukan oleh <strong style="color: #16322F;">NRP {{ $ticket->nrp }}</strong>
                        &bull; {{ $ticket->created_at->format('d F Y, H:i') }}
                    </div>
                    <div>
                        @if($ticket->status === 'answered')
                            <span style="background: #E8F5F1; color: #0D7F74; border: 1px solid #BCE3D8; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 999px;">
                                &bull; Sudah dijawab
                            </span>
                        @else
                            <span style="background: #FFF4EB; color: #B4690E; border: 1px solid #F5DFB0; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 999px;">
                                Menunggu tanggapan
                            </span>
                        @endif
                    </div>
                </div>

                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #8FA19D; margin-bottom: 6px;">
                    PERTANYAAN MAHASISWA
                </div>
                <div style="font-size: 18px; font-weight: 700; color: #16322F; line-height: 1.45; margin-bottom: 18px; overflow-wrap: anywhere; word-break: break-word;">
                    {{ $ticket->pertanyaan }}
                </div>

                <div style="background: #F6F8F7; border: 1px solid #E2E9E6; border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; font-size: 12.5px;">
                    <div>
                        <span style="color: #647874;">Email Kampus Tujuan Balasan:</span>
                        <strong style="color: #0D7F74; margin-left: 4px;">{{ $ticket->student_email }}</strong>
                    </div>
                    <span style="color: #8FA19D; font-size: 11.5px;">Otomatis dari 9 digit NRP</span>
                </div>
            </div>

            <!-- Kartu Analisis Kemiripan TF-IDF dengan Repositori FAQ -->
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 22px 26px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 6px; flex-wrap: wrap;">
                    <h3 style="font-size: 15px; font-weight: 700; color: #16322F; margin: 0;">
                        Deteksi Kemiripan FAQ di Repositori (TF-IDF)
                    </h3>
                    <span style="font-size: 11.5px; font-weight: 600; color: #0D7F74; background: #E8F5F1; padding: 2px 10px; border-radius: 999px;">
                        {{ $similarCandidates->count() }} kandidat ditemukan
                    </span>
                </div>
                <p style="font-size: 12.5px; color: #647874; margin: 0 0 16px;">
                    Berikut adalah FAQ di repositori yang memiliki kemiripan kata kunci dengan pertanyaan mahasiswa ini:
                </p>

                @forelse($similarCandidates as $cand)
                    @php
                        $candFaq = $cand['faq'];
                        $candPct = $cand['percentage'] ?? (round(($cand['score'] ?? 0) * 100, 1) . '%');
                    @endphp
                    <div style="border: 1px solid #E2E9E6; border-radius: 10px; padding: 14px 16px; margin-bottom: 10px; background: #FAFBFB;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <span style="font-size: 11.5px; font-weight: 700; color: #0D7F74;">FAQ #{{ $candFaq->id }}</span>
                                <span style="font-size: 11px; padding: 2px 8px; border-radius: 999px; background: #E8F5F1; color: #0D7F74; font-weight: 600;">
                                    {{ ucfirst($candFaq->status) }}
                                </span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="background: #FFF4EB; color: #B4690E; border: 1px solid #F5DFB0; font-size: 11.5px; font-weight: 700; padding: 2px 9px; border-radius: 6px;">
                                    {{ $candPct }} mirip
                                </span>
                                <a href="{{ route('faqs.show', $candFaq->id) }}" class="skmk-link-action" style="font-size: 12px;">
                                    <span>Lihat FAQ</span>
                                    <span>&#8599;</span>
                                </a>
                            </div>
                        </div>
                        <div style="font-size: 13.5px; font-weight: 700; color: #16322F; margin-bottom: 4px;">
                            &ldquo;{{ $candFaq->pertanyaan }}&rdquo;
                        </div>
                        <div style="font-size: 12.5px; color: #647874; line-height: 1.5;">
                            {{ \Illuminate\Support\Str::limit(strip_tags($candFaq->jawaban), 140) }}
                        </div>
                    </div>
                @empty
                    <div style="background: #F6F8F7; border: 1px dashed #D5E0DC; border-radius: 10px; padding: 18px; text-align: center; font-size: 13px; color: #647874;">
                        Tidak ditemukan FAQ yang mirip di repositori. Pertanyaan ini merupakan topik baru.
                    </div>
                @endforelse
            </div>

            @if($ticket->note || $ticket->faq)
                <!-- Kartu Riwayat Tanggapan Staf -->
                <div style="background: #E8F5F1; border: 1px solid #BCE3D8; border-radius: 14px; padding: 20px 24px;">
                    <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #0D7F74; margin-bottom: 8px;">
                        CATATAN / TANGGAPAN STAF
                    </div>
                    @if($ticket->note)
                        <div style="font-size: 13.5px; color: #16322F; line-height: 1.6; margin-bottom: 10px;">
                            {!! nl2br(e($ticket->note)) !!}
                        </div>
                    @endif
                    @if($ticket->faq)
                        <div style="font-size: 12.5px; color: #3D524E;">
                            Ditautkan ke: <a href="{{ route('faqs.show', $ticket->faq->id) }}" style="font-weight: 700; color: #0D7F74; text-decoration: none;">FAQ #{{ $ticket->faq->id }} &mdash; {{ $ticket->faq->pertanyaan }} &rarr;</a>
                        </div>
                    @endif
                </div>
            @endif

        </div>

        <!-- KOLOM KANAN: RINGKASAN TIKET -->
        <aside class="validator-detail-actions">
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 22px;">
                <div style="margin-bottom: 12px;">
                    @if($ticket->status === 'answered')
                        <span style="display: inline-flex; align-items: center; gap: 6px; background: #E8F5F1; color: #0D7F74; font-size: 12px; font-weight: 700; padding: 4px 11px; border-radius: 999px;">
                            &bull; Sudah dijawab
                        </span>
                    @else
                        <span style="display: inline-flex; align-items: center; gap: 6px; background: #FFF4EB; color: #B4690E; font-size: 12px; font-weight: 700; padding: 4px 11px; border-radius: 999px;">
                            &bull; Menunggu tanggapan
                        </span>
                    @endif
                </div>

                <h3 style="font-size: 16px; font-weight: 700; color: #16322F; margin: 0 0 6px;">
                    Informasi Tiket #{{ $ticket->id }}
                </h3>
                <p style="font-size: 12.5px; color: #647874; line-height: 1.55; margin: 0 0 16px;">
                    Tiket ini dikirim oleh mahasiswa melalui halaman Tanya Staf beridentitas NRP.
                </p>

                <div style="border-top: 1px solid #EDF2F0; padding-top: 12px; font-size: 12.5px; display: flex; flex-direction: column; gap: 8px; margin-bottom: 18px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #647874;">NRP Pengaju</span>
                        <strong style="color: #16322F;">{{ $ticket->nrp }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #647874;">Tanggal Masuk</span>
                        <strong style="color: #16322F;">{{ $ticket->created_at->format('d M Y') }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #647874;">Penangan</span>
                        <strong style="color: #16322F;">{{ $ticket->user?->name ?? '—' }}</strong>
                    </div>
                </div>

                <a href="{{ route('questions.index') }}" class="btn-secondary" style="display: flex; align-items: center; justify-content: center; width: 100%; padding: 10px 14px; font-size: 13px; font-weight: 600; text-decoration: none; color: #16322F;">
                    Kembali ke daftar tiket
                </a>
            </div>
        </aside>
    </div>

</div>
@endsection
