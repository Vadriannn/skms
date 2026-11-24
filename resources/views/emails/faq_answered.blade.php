<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Jawaban FAQ — SKMS UBAYA</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #F4F7F6;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #2C4642;
            -webkit-text-size-adjust: none;
            text-size-adjust: none;
        }
        table {
            border-collapse: collapse;
        }
        .container {
            width: 100%;
            max-width: 620px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #E2E9E6;
        }
        .header {
            background: linear-gradient(135deg, #0D7F74 0%, #16322F 100%);
            padding: 28px 32px;
            text-align: left;
        }
        .header h1 {
            color: #FFFFFF;
            font-size: 20px;
            margin: 0 0 4px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .header p {
            color: #8ED8C3;
            font-size: 12px;
            margin: 0;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .content {
            padding: 32px;
        }
        .badge {
            display: inline-block;
            background-color: #E8F5F1;
            color: #0D7F74;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .greeting {
            font-size: 15px;
            font-weight: 700;
            color: #16322F;
            margin-bottom: 10px;
        }
        .intro-text {
            font-size: 14px;
            line-height: 1.6;
            color: #4D6762;
            margin-bottom: 24px;
        }
        .box-pertanyaan {
            background-color: #F8FAFA;
            border-left: 4px solid #8ED8C3;
            border-radius: 6px;
            padding: 16px 18px;
            margin-bottom: 24px;
        }
        .box-title {
            font-size: 11px;
            font-weight: 700;
            color: #647874;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 6px;
        }
        .box-pertanyaan p {
            font-size: 14px;
            font-weight: 600;
            color: #16322F;
            margin: 0;
            line-height: 1.5;
            font-style: italic;
        }
        .box-jawaban {
            background-color: #E6F4EF;
            border: 1px solid #C7E6DC;
            border-radius: 10px;
            padding: 20px 22px;
            margin-bottom: 24px;
        }
        .faq-title {
            font-size: 15px;
            font-weight: 800;
            color: #0D7F74;
            margin: 0 0 10px;
            line-height: 1.4;
        }
        .faq-categories {
            margin-bottom: 12px;
        }
        .cat-tag {
            display: inline-block;
            background: #FFFFFF;
            border: 1px solid #8ED8C3;
            color: #0D7F74;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
            margin-right: 6px;
            margin-bottom: 4px;
        }
        .faq-body {
            font-size: 13.5px;
            color: #2C4642;
            line-height: 1.65;
            margin-bottom: 14px;
        }
        .attachment-item {
            display: inline-block;
            background-color: #FFFFFF;
            border: 1px solid #C7E6DC;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 12px;
            color: #0D7F74;
            font-weight: 600;
            text-decoration: none;
            margin-top: 8px;
        }
        .video-item {
            display: inline-block;
            background-color: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 12px;
            color: #DC2626;
            font-weight: 600;
            text-decoration: none;
            margin-top: 8px;
            margin-left: 6px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 30px 0 10px;
        }
        .btn-portal {
            display: inline-block;
            background-color: #0D7F74;
            color: #FFFFFF !important;
            font-size: 14px;
            font-weight: 700;
            padding: 12px 26px;
            border-radius: 8px;
            text-decoration: none;
            letter-spacing: -0.01em;
        }
        .footer {
            background-color: #FAFCFB;
            border-top: 1px solid #EEF3F1;
            padding: 22px 32px;
            text-align: center;
            font-size: 11.5px;
            color: #7A908C;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div style="padding: 24px 12px;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center">
                    <div class="container">
                        <!-- Header -->
                        <div class="header">
                            <p>Student Knowledge Management System</p>
                            <h1>SKMS UBAYA</h1>
                        </div>

                        <!-- Body Content -->
                        <div class="content">
                            <span class="badge">
                                Tiket Pengajuan #{{ $submission->id }} &bull; NRP {{ $submission->nrp }} &bull; {{ $studentEmail }}
                            </span>

                            <div class="greeting">
                                Halo Ubayatizen (NRP: {{ $submission->nrp }}),
                            </div>

                            @if($scenario === 'new_faq')
                                <div class="intro-text">
                                    Pertanyaan yang Anda ajukan melalui portal SKMS telah selesai ditinjau oleh staf dan <strong>resmi disetujui serta dipublikasikan sebagai panduan FAQ baru</strong> di sistem kami.
                                </div>
                            @else
                                <div class="intro-text">
                                    Pertanyaan yang Anda ajukan telah berhasil <strong>ditanggapi oleh staf SKMS</strong>.
                                </div>
                            @endif

                            <!-- Box Pertanyaan Mahasiswa -->
                            <div class="box-pertanyaan">
                                <div class="box-title">Pertanyaan yang Anda Ajukan:</div>
                                <p>&ldquo;{{ $submission->pertanyaan }}&rdquo;</p>
                            </div>

                            <!-- Box Jawaban Resmi / FAQ -->
                            <div class="box-jawaban">
                                <div class="box-title" style="color: #0D7F74;">Jawaban Resmi SKMS UBAYA:</div>
                                <div class="faq-title">{{ $faq->pertanyaan }}</div>

                                @if($faq->categories && $faq->categories->count() > 0)
                                    <div class="faq-categories">
                                        @foreach($faq->categories as $category)
                                            <span class="cat-tag">{{ $category->nama }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="faq-body">
                                    {!! nl2br(e($faq->jawaban)) !!}
                                </div>

                                @if($faq->file_lampiran)
                                    <div style="margin-top: 10px;">
                                        <span style="font-size: 11px; font-weight: 700; color: #4D6762; text-transform: uppercase; display: block; margin-bottom: 4px;">File Lampiran:</span>
                                        <a href="{{ route('faqs.download-attachment', $faq->id) }}" class="attachment-item" target="_blank">
                                            📎 Unduh {{ $faq->file_nama_asli ?: 'File Lampiran' }}
                                        </a>
                                    </div>
                                @endif

                                @if($faq->link_video)
                                    <div style="margin-top: 10px;">
                                        <span style="font-size: 11px; font-weight: 700; color: #4D6762; text-transform: uppercase; display: block; margin-bottom: 4px;">Tautan Video Edukatif:</span>
                                        <a href="{{ $faq->link_video }}" class="video-item" target="_blank">
                                            ▶ Tonton Video Tutorial ({{ $faq->link_video }})
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Tombol & Link FAQ yang sudah dipublikasikan -->
                            <div class="btn-wrapper">
                                <a href="{{ route('skms.faq.show', $faq->id) }}" class="btn-portal" target="_blank">
                                    Buka Panduan FAQ di Portal SKMS &rarr;
                                </a>
                            </div>

                            <div style="text-align: center; margin-top: 14px; margin-bottom: 6px; font-size: 12px; color: #647874; line-height: 1.5;">
                                Atau buka tautan FAQ resmi yang telah dipublikasikan berikut:<br>
                                <a href="{{ route('skms.faq.show', $faq->id) }}" style="color: #0D7F74; font-weight: 600; text-decoration: underline; word-break: break-all;">
                                    {{ route('skms.faq.show', $faq->id) }}
                                </a>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="footer">
                            <p style="margin: 0 0 4px; font-weight: 600; color: #4D6762;">
                                Direktorat Sistem Informasi & Pengelolaan Pengetahuan Mahasiswa
                            </p>
                            <p style="margin: 0 0 8px;">
                                Universitas Surabaya (UBAYA) &bull; Kampus Tenggilis &bull; Surabaya, Jawa Timur
                            </p>
                            <p style="margin: 0; font-size: 10.5px; color: #9AB0AB;">
                                Email ini dibuat secara otomatis oleh sistem notifikasi SKMS UBAYA. Anda menerima email ini karena mengajukan pertanyaan tiket #{{ $submission->id }} dengan identitas NRP {{ $submission->nrp }}.
                            </p>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
