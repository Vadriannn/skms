<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanya Staf (Pengajuan Pertanyaan) — SKMS UBAYA</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --skms-teal: #0D7F74;
            --skms-teal-hover: #0A665D;
            --skms-mint: #8ED8C3;
            --skms-mint-soft: #E6F4EF;
            --skms-mint-card: #E8F5F1;
            --skms-mint-border: #C7E6DC;
            --skms-forest: #16322F;
            --skms-text: #2C4642;
            --skms-muted: #647874;
            --skms-cloud: #F6F8F7;
            --skms-border: #E2E9E6;
        }

        * {
            box-sizing: border-box;
        }

        body.skms-student-body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--skms-cloud);
            color: var(--skms-text);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* ── TOP NAVBAR ────────────────────────────────────────────────── */
        .skms-navbar {
            background: #FFFFFF;
            border-bottom: 1px solid var(--skms-border);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .skms-navbar-inner {
            max-width: 1220px;
            margin: 0 auto;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .skms-logo-link {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .skms-logo-text {
            display: flex;
            flex-direction: column;
        }

        .skms-logo-title {
            font-size: 17px;
            font-weight: 800;
            color: var(--skms-forest);
            letter-spacing: -0.02em;
            line-height: 1.15;
        }

        .skms-logo-sub {
            font-size: 11px;
            font-weight: 500;
            color: var(--skms-muted);
            margin-top: 1px;
        }

        .skms-nav-right {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .skms-nav-link {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--skms-forest);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .skms-nav-link:hover,
        .skms-nav-link.active {
            color: var(--skms-teal);
        }

        .skms-nrp-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 7px 14px;
            background: var(--skms-mint-card);
            border: 1px solid var(--skms-mint-border);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: var(--skms-forest);
        }

        .skms-nrp-logout {
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            color: #DC2626;
            font-size: 11.5px;
            font-weight: 600;
            padding: 4px 9px;
            border-radius: 6px;
            cursor: pointer;
            font-family: inherit;
        }

        /* ── HERO HEADER ───────────────────────────────────────────────── */
        .skms-tanya-hero {
            background-color: var(--skms-mint-soft);
            border-bottom: 1px solid var(--skms-mint-border);
            padding: 32px 28px;
        }

        .skms-tanya-hero-inner {
            max-width: 1220px;
            margin: 0 auto;
        }

        .skms-tanya-kicker {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--skms-teal);
            margin-bottom: 8px;
        }

        .skms-tanya-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--skms-forest);
            letter-spacing: -0.025em;
            margin: 0 0 6px;
        }

        .skms-tanya-sub {
            font-size: 13.5px;
            color: #4D6762;
            margin: 0;
            max-width: 720px;
            line-height: 1.55;
        }

        /* ── MAIN CONTAINER ────────────────────────────────────────────── */
        .skms-tanya-main {
            max-width: 1220px;
            width: 100%;
            margin: 0 auto;
            padding: 28px 28px 64px;
            flex: 1;
        }

        .skms-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--skms-muted);
            margin-bottom: 20px;
        }

        .skms-breadcrumb a {
            color: var(--skms-muted);
            text-decoration: none;
        }

        .skms-breadcrumb a:hover {
            color: var(--skms-teal);
        }

        .skms-tanya-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 28px;
            align-items: start;
        }

        .skms-tanya-grid > * {
            min-width: 0;
        }

        /* ── FORM CARD ─────────────────────────────────────────────────── */
        .skms-form-card {
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            border-radius: 16px;
            padding: 28px 30px;
            margin-bottom: 24px;
        }

        .skms-form-card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 18px;
            margin-bottom: 22px;
            border-bottom: 1px solid #EEF3F1;
            flex-wrap: wrap;
        }

        .skms-form-card-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--skms-forest);
            margin: 0 0 4px;
        }

        .skms-form-card-sub {
            font-size: 13px;
            color: var(--skms-muted);
            margin: 0;
        }

        .skms-verified-nrp-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: var(--skms-mint-card);
            border: 1px solid var(--skms-mint-border);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            color: var(--skms-teal);
        }

        .skms-form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-bottom: 18px;
        }

        .skms-field-group {
            margin-bottom: 18px;
        }

        .skms-field-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: var(--skms-forest);
            margin-bottom: 7px;
        }

        .skms-field-label .req {
            color: #DC2626;
        }

        .skms-input,
        .skms-select,
        .skms-textarea {
            width: 100%;
            padding: 11px 14px;
            background: #FFFFFF;
            border: 1.5px solid var(--skms-border);
            border-radius: 10px;
            font-size: 13.5px;
            color: var(--skms-forest);
            font-family: inherit;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .skms-input:focus,
        .skms-select:focus,
        .skms-textarea:focus {
            border-color: var(--skms-teal);
            box-shadow: 0 0 0 3px rgba(13, 127, 116, 0.12);
        }

        .skms-input[readonly] {
            background: var(--skms-cloud);
            color: var(--skms-forest);
            font-weight: 700;
            cursor: not-allowed;
        }

        .skms-field-hint {
            font-size: 11.5px;
            color: var(--skms-muted);
            margin-top: 5px;
            line-height: 1.45;
        }

        .skms-field-error {
            font-size: 12px;
            color: #DC2626;
            font-weight: 600;
            margin-top: 5px;
        }

        .skms-form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding-top: 18px;
            margin-top: 8px;
            border-top: 1px solid #EEF3F1;
            flex-wrap: wrap;
        }

        .skms-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 18px;
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: var(--skms-text);
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .skms-btn-secondary:hover {
            background: var(--skms-cloud);
            border-color: #C7D7D2;
        }

        .skms-btn-submit-q {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 24px;
            background: var(--skms-teal);
            color: #FFFFFF;
            border: none;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.15s ease, transform 0.15s ease;
        }

        .skms-btn-submit-q:hover {
            background: var(--skms-teal-hover);
            transform: translateY(-1px);
        }

        /* ── MY QUESTIONS HISTORY CARD ─────────────────────────────────── */
        .skms-history-card {
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            border-radius: 16px;
            padding: 24px 28px;
        }

        .skms-history-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
        }

        .skms-history-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--skms-forest);
            margin: 0;
        }

        .skms-ticket-item {
            border: 1px solid var(--skms-border);
            border-radius: 12px;
            padding: 16px 18px;
            margin-bottom: 12px;
            background: #FFFFFF;
        }

        .skms-ticket-item:last-child {
            margin-bottom: 0;
        }

        .skms-ticket-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }

        .skms-ticket-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            font-size: 12px;
            color: var(--skms-muted);
        }

        .skms-ticket-id {
            font-weight: 700;
            color: var(--skms-forest);
            background: var(--skms-cloud);
            padding: 2px 8px;
            border-radius: 6px;
        }

        .skms-ticket-cat {
            padding: 2px 9px;
            border-radius: 999px;
            background: var(--skms-mint-card);
            color: var(--skms-teal);
            font-size: 11.5px;
            font-weight: 600;
        }

        .skms-ticket-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
        }

        .skms-ticket-status.pending {
            background: #FEF3C7;
            color: #B45309;
        }

        .skms-ticket-status.answered {
            background: var(--skms-mint-card);
            color: var(--skms-teal);
        }

        .skms-ticket-q {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--skms-forest);
            margin: 0 0 6px;
            line-height: 1.4;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .skms-ticket-detail {
            font-size: 13px;
            color: var(--skms-muted);
            margin: 0 0 8px;
            line-height: 1.55;
        }

        .skms-ticket-reply {
            margin-top: 12px;
            padding: 12px 14px;
            background: var(--skms-mint-soft);
            border: 1px solid var(--skms-mint-border);
            border-radius: 10px;
            font-size: 13px;
            color: var(--skms-text);
            line-height: 1.6;
        }

        /* ── RIGHT SIDEBAR ─────────────────────────────────────────────── */
        .skms-side-stack {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .skms-side-card {
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            border-radius: 16px;
            padding: 22px;
        }

        .skms-side-title {
            font-size: 15.5px;
            font-weight: 800;
            color: var(--skms-forest);
            margin: 0 0 6px;
        }

        .skms-side-sub {
            font-size: 12.5px;
            color: var(--skms-muted);
            margin: 0 0 16px;
            line-height: 1.5;
        }

        .skms-flow-step {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 14px;
        }

        .skms-flow-step:last-child {
            margin-bottom: 0;
        }

        .skms-flow-num {
            width: 26px;
            height: 26px;
            border-radius: 7px;
            background: var(--skms-mint-card);
            color: var(--skms-teal);
            font-size: 12px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .skms-flow-head {
            font-size: 13px;
            font-weight: 700;
            color: var(--skms-forest);
            margin-bottom: 2px;
        }

        .skms-flow-desc {
            font-size: 12px;
            color: var(--skms-muted);
            line-height: 1.5;
        }

        .skms-mint-tips-card {
            background: var(--skms-mint-soft);
            border: 1px solid var(--skms-mint-border);
            border-radius: 16px;
            padding: 22px;
        }

        /* ── FOOTER ────────────────────────────────────────────────────── */
        .skms-footer {
            background: #FFFFFF;
            border-top: 1px solid var(--skms-border);
            margin-top: auto;
        }

        .skms-footer-inner {
            max-width: 1220px;
            margin: 0 auto;
            padding: 20px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .skms-footer-brand {
            font-size: 13px;
            font-weight: 800;
            color: var(--skms-teal);
            margin-bottom: 3px;
        }

        .skms-footer-copy {
            font-size: 11.5px;
            color: var(--skms-muted);
        }

        .skms-footer-right {
            font-size: 12.5px;
            font-weight: 500;
            color: var(--skms-muted);
        }

        @media (max-width: 960px) {
            .skms-tanya-grid {
                grid-template-columns: 1fr;
            }
            .skms-form-row-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="skms-student-body">

    <!-- ─── TOP NAVBAR ─────────────────────────────────────────────────────── -->
    <header class="skms-navbar">
        <div class="skms-navbar-inner">
            <a href="{{ route('skms.beranda') }}" class="skms-logo-link">
                <svg width="34" height="34" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 7.5C4 6.11929 5.11929 5 6.5 5C10.5 5 14.5 6.5 17.5 9V14.5L21 17.5L17.5 20.5V29.5C14.5 27 10.5 25.5 6.5 25.5C5.11929 25.5 4 24.3807 4 23V7.5Z" fill="#0D7F74"/>
                    <path d="M32 7.5C32 6.11929 30.8807 5 29.5 5C25.5 5 21.5 6.5 18.5 9V14L22 17.5L18.5 21V29.5C21.5 27 25.5 25.5 29.5 25.5C30.8807 25.5 32 24.3807 32 23V7.5Z" fill="#8ED8C3"/>
                </svg>
                <div class="skms-logo-text">
                    <span class="skms-logo-title">SKMS UBAYA</span>
                    <span class="skms-logo-sub">Student Knowledge Management System</span>
                </div>
            </a>

            <div class="skms-nav-right">
                <a href="{{ route('skms.beranda') }}" class="skms-nav-link">Beranda</a>
                <a href="{{ route('skms.beranda', ['all' => 1]) }}" class="skms-nav-link">Informasi</a>
                <a href="{{ route('skms.tanya') }}" class="skms-nav-link active">Tanya Staf</a>

                <div class="skms-nrp-pill">
                    <span>NRP: {{ $studentNrp }}</span>
                    <form action="{{ route('skms.logout') }}" method="POST" style="margin: 0; display: inline;">
                        @csrf
                        <button type="submit" class="skms-nrp-logout">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- ─── HERO BANNER ────────────────────────────────────────────────────── -->
    <section class="skms-tanya-hero">
        <div class="skms-tanya-hero-inner">
            <div class="skms-tanya-kicker">LAYANAN PENGAJUAN PERTANYAAN MAHASISWA</div>
            <h1 class="skms-tanya-title">Tanya Staf Pengelola Pengetahuan</h1>
            <p class="skms-tanya-sub">
                Belum menemukan jawaban yang kamu butuhkan di daftar FAQ? Kirimkan pertanyaanmu menggunakan identitas NRP dan staf kami akan mengirimkan jawaban resminya melalui email.
            </p>
        </div>
    </section>

    <!-- ─── MAIN CONTENT ───────────────────────────────────────────────────── -->
    <main class="skms-tanya-main">
        <div class="skms-breadcrumb">
            <a href="{{ route('skms.beranda') }}">Beranda</a>
            <span>/</span>
            <a href="{{ route('skms.beranda', ['all' => 1]) }}">Pusat bantuan</a>
            <span>/</span>
            <span style="color: var(--skms-forest); font-weight: 600;">Tanya Staf</span>
        </div>

        @if(session('submitted_ticket'))
            @php $ticket = session('submitted_ticket'); @endphp
            <div style="max-width: 680px; margin: 18px auto 36px; background: #FFFFFF; border: 1px solid var(--skms-mint-border); border-radius: 18px; padding: 38px 32px; text-align: center; box-shadow: 0 12px 32px -12px rgba(13, 127, 116, 0.14);">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--skms-mint-soft); color: var(--skms-teal); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>

                <div style="display: inline-flex; align-items: center; gap: 8px; padding: 5px 14px; border-radius: 999px; background: var(--skms-mint-card); color: var(--skms-teal); font-size: 12px; font-weight: 700; margin-bottom: 14px;">
                    Tiket #{{ $ticket['id'] }} &bull; NRP: {{ $ticket['nrp'] }}
                </div>

                <h2 style="font-size: 24px; font-weight: 800; color: var(--skms-forest); margin: 0 0 10px; letter-spacing: -0.02em;">
                    Pertanyaan Anda Telah Diajukan!
                </h2>

                <p style="font-size: 14px; color: var(--skms-text); line-height: 1.65; margin: 0 auto 18px; max-width: 540px;">
                    Terima kasih telah menghubungi tim pengelola pengetahuan SKMS UBAYA. <strong>Mohon menunggu</strong>, karena staf kami akan meninjau pertanyaan Anda dan <strong>mengirimkan jawaban resminya melalui email kampus Anda</strong>:
                </p>

                <div style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; background: var(--skms-cloud); border: 1px solid var(--skms-border); border-radius: 10px; font-size: 14px; font-weight: 700; color: var(--skms-teal); margin-bottom: 22px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <span>{{ $ticket['email'] }}</span>
                </div>

                <div style="background: var(--skms-mint-soft); border: 1px solid var(--skms-mint-border); border-radius: 12px; padding: 14px 18px; text-align: left; margin-bottom: 24px;">
                    <div style="font-size: 11.5px; font-weight: 700; color: var(--skms-teal); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">
                        Pertanyaan yang Diajukan
                    </div>
                    <div style="font-size: 13.5px; font-weight: 600; color: var(--skms-forest); line-height: 1.5; overflow-wrap: anywhere; word-break: break-word;">
                        &ldquo;{{ $ticket['pertanyaan'] }}&rdquo;
                    </div>
                </div>

                <p style="font-size: 12.5px; color: var(--skms-muted); margin: 0 0 18px;">
                    Anda akan dialihkan otomatis kembali ke halaman Beranda dalam <strong id="redirectCountdown" style="color: var(--skms-teal);">5</strong> detik...
                </p>

                <a href="{{ route('skms.beranda') }}" class="skms-btn-submit-q" style="text-decoration: none;">
                    <span>Kembali ke Beranda Sekarang</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>

            <script>
                (function () {
                    let seconds = 5;
                    const el = document.getElementById('redirectCountdown');
                    const timer = setInterval(function () {
                        seconds--;
                        if (el && seconds >= 0) {
                            el.textContent = seconds;
                        }
                        if (seconds <= 0) {
                            clearInterval(timer);
                            window.location.href = "{{ route('skms.beranda') }}";
                        }
                    }, 1000);
                })();
            </script>
        @else
            @if(session('success'))
                <div style="margin-bottom: 22px; padding: 14px 18px; border-radius: 12px; background: #E6F4EF; border: 1px solid #8ED8C3; color: #0D7F74; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="skms-tanya-grid">
            <!-- ── KOLOM KIRI: FORMULIR PENGAJUAN & RIWAYAT TIKET NRP ─────── -->
            <div>
                <div class="skms-form-card">
                    <div class="skms-form-card-head">
                        <div>
                            <h2 class="skms-form-card-title">Formulir Pengajuan Pertanyaan Baru</h2>
                            <p class="skms-form-card-sub">Pastikan email penerima aktif agar Anda dapat menerima notifikasi jawaban dari staf.</p>
                        </div>
                        <span class="skms-verified-nrp-badge">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            Identitas NRP Terverifikasi
                        </span>
                    </div>

                    <form action="{{ route('skms.tanya.store') }}" method="POST">
                        @csrf

                        <div class="skms-form-row-2">
                            <div>
                                <label class="skms-field-label" for="nrpDisplay">
                                    NRP Mahasiswa <span class="req">*</span>
                                </label>
                                <input type="text"
                                       id="nrpDisplay"
                                       class="skms-input"
                                       value="{{ $studentNrp }}"
                                       readonly>
                                <div class="skms-field-hint">NRP 9 digit diambil otomatis dari sesi login mahasiswa Anda.</div>
                            </div>

                            <div>
                                <label class="skms-field-label" for="emailDisplay">
                                    Email Kampus Penerima Balasan
                                </label>
                                <input type="text"
                                       id="emailDisplay"
                                       class="skms-input"
                                       value="{{ $studentEmail }}"
                                       readonly>
                                <div class="skms-field-hint">
                                    Jawaban resmi dari staf akan dikirimkan otomatis ke email kampus Anda.
                                </div>
                            </div>
                        </div>

                        <div class="skms-field-group">
                            <label class="skms-field-label" for="pertanyaan">
                                Pertanyaan Anda <span class="req">*</span>
                            </label>
                            <textarea id="pertanyaan"
                                      name="pertanyaan"
                                      rows="4"
                                      class="skms-textarea"
                                      style="resize: vertical; line-height: 1.6;"
                                      maxlength="500"
                                      required
                                      autofocus
                                      placeholder="Tuliskan pertanyaan akademik atau kemahasiswaan yang belum Anda temukan jawabannya di daftar FAQ (maks. 500 karakter)...">{{ old('pertanyaan', $prefillQuestion) }}</textarea>
                            <div class="skms-field-hint">Jelaskan pertanyaan Anda dengan spesifik agar staf dapat menautkan atau menyusun jawaban yang tepat.</div>
                            @error('pertanyaan')
                                <div class="skms-field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="skms-form-actions">
                            <a href="{{ route('skms.beranda', ['all' => 1]) }}" class="skms-btn-secondary">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="19" y1="12" x2="5" y2="12"></line>
                                    <polyline points="12 19 5 12 12 5"></polyline>
                                </svg>
                                <span>Kembali ke Pencarian FAQ</span>
                            </a>

                            <button type="submit" class="skms-btn-submit-q">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                </svg>
                                <span>Kirim Pertanyaan ke Staf</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ── RIWAYAT TIKET PERTANYAAN MAHASISWA (NRP AKTIF) ──────── -->
                <div class="skms-history-card">
                    <div class="skms-history-head">
                        <h3 class="skms-history-title">Riwayat Pertanyaan Saya (NRP: {{ $studentNrp }})</h3>
                        <span style="font-size: 12px; font-weight: 600; color: var(--skms-muted);">
                            {{ $myQuestions->count() }} tiket diajukan
                        </span>
                    </div>

                    @forelse($myQuestions as $qItem)
                        <div class="skms-ticket-item">
                            <div class="skms-ticket-top">
                                <div class="skms-ticket-meta">
                                    <span class="skms-ticket-id">Tiket #{{ $qItem->id }}</span>
                                    <span>&bull; {{ $qItem->created_at->format('d M Y, H:i') }}</span>
                                    <span>&bull; Email: <strong>{{ $qItem->student_email }}</strong></span>
                                </div>

                                @if($qItem->status === 'answered')
                                    <span class="skms-ticket-status answered">✓ Sudah Dijawab</span>
                                @else
                                    <span class="skms-ticket-status pending">⏳ Menunggu Tanggapan Staf</span>
                                @endif
                            </div>

                            <h4 class="skms-ticket-q">{{ $qItem->pertanyaan }}</h4>

                            @if($qItem->status === 'answered' && ($qItem->note || $qItem->faq))
                                <div class="skms-ticket-reply">
                                    <div style="font-size: 11.5px; font-weight: 700; color: var(--skms-teal); margin-bottom: 4px;">
                                        Catatan / Jawaban Staf SKMS{{ $qItem->user ? ' (' . $qItem->user->name . ')' : '' }}:
                                    </div>
                                    @if($qItem->note)
                                        <div>{!! nl2br(e($qItem->note)) !!}</div>
                                    @endif
                                    @if($qItem->faq && $qItem->faq->status === 'published')
                                        <div style="margin-top: 8px;">
                                            <a href="{{ route('skms.faq.show', $qItem->faq->id) }}" style="font-size: 12.5px; font-weight: 700; color: var(--skms-teal); text-decoration: none;">
                                                Baca Panduan FAQ Terkait: &ldquo;{{ $qItem->faq->pertanyaan }}&rdquo; &rarr;
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div style="text-align: center; padding: 24px 16px; color: var(--skms-muted); font-size: 13px; background: var(--skms-cloud); border-radius: 12px;">
                            Anda belum pernah mengajukan tiket pertanyaan dengan NRP <strong>{{ $studentNrp }}</strong>.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- ── KOLOM KANAN: ALUR LAYANAN & TIPS ───────────────────────── -->
            <aside class="skms-side-stack">
                <div class="skms-side-card">
                    <h3 class="skms-side-title">Alur Layanan Tanya Staf</h3>
                    <p class="skms-side-sub">Bagaimana pertanyaan Anda ditindaklanjuti oleh tim SKMS UBAYA.</p>

                    <div class="skms-flow-step">
                        <span class="skms-flow-num">1</span>
                        <div>
                            <div class="skms-flow-head">Ajukan Pertanyaan dengan NRP</div>
                            <div class="skms-flow-desc">Kirimkan pertanyaan beserta alamat email aktif yang ingin menerima balasan.</div>
                        </div>
                    </div>

                    <div class="skms-flow-step">
                        <span class="skms-flow-num">2</span>
                        <div>
                            <div class="skms-flow-head">Ditinjau Staf Data Entry</div>
                            <div class="skms-flow-desc">Pertanyaan masuk ke antrean tiket staf untuk ditautkan ke panduan resmi atau dibuatkan draf FAQ baru.</div>
                        </div>
                    </div>

                    <div class="skms-flow-step">
                        <span class="skms-flow-num">3</span>
                        <div>
                            <div class="skms-flow-head">Jawaban Dikirim ke Email</div>
                            <div class="skms-flow-desc">Begitu staf merespons tiket Anda, jawaban resmi otomatis dikirimkan ke email kampus/Gmail Anda.</div>
                        </div>
                    </div>
                </div>

                <div class="skms-mint-tips-card">
                    <h3 class="skms-side-title" style="margin-bottom: 10px;">Tips Agar Cepat Terjawab</h3>
                    <ul style="margin: 0; padding-left: 18px; font-size: 12.5px; color: #4D6762; line-height: 1.65;">
                        <li style="margin-bottom: 6px;">Gunakan kalimat pertanyaan yang jelas dan spesifik.</li>
                        <li style="margin-bottom: 6px;">Cantumkan informasi fakultas atau semester pada kolom detail jika pertanyaan berkaitan dengan aturan akademik khusus.</li>
                        <li>Pastikan alamat email penerima balasan ditulis dengan benar.</li>
                    </ul>
                </div>
            </aside>
        </div>
        @endif
    </main>

    <!-- ─── FOOTER ─────────────────────────────────────────────────────────── -->
    <footer class="skms-footer">
        <div class="skms-footer-inner">
            <div>
                <div class="skms-footer-brand">SKMS UBAYA</div>
                <div class="skms-footer-copy">&copy; 2026 SKMS UBAYA &mdash; Student Knowledge Management System.</div>
            </div>
            <div class="skms-footer-right">Universitas Surabaya</div>
        </div>
    </footer>

</body>
</html>
