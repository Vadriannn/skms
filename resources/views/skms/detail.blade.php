<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $faq->pertanyaan }} — SKMS UBAYA</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --skms-teal: #8B0D18;
            --skms-teal-hover: #6B0810;
            --skms-maroon-deep: #4A070D;
            --skms-mint: #E5A032;
            --skms-gold-soft: #FEF6E6;
            --skms-gold-border: #F5DFB0;
            --skms-gold-text: #A8640C;
            --skms-mint-soft: #FDF2F3;
            --skms-mint-card: #FBE7E9;
            --skms-mint-border: #F1CFD3;
            --skms-forest: #1F1415;
            --skms-text: #3E2F30;
            --skms-muted: #6B5B5D;
            --skms-cloud: #FAF8F7;
            --skms-border: #ECE4E5;
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

        /* ── FLOATING TOP NAVBAR (SERAGAM DENGAN BERANDA) ──────────────── */
        .skms-navbar-shell {
            max-width: 1320px;
            width: 100%;
            margin: 16px auto 0;
            padding: 0 24px;
            position: sticky;
            top: 12px;
            z-index: 50;
        }

        .skms-navbar {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border: 1px solid var(--skms-border);
            border-radius: 18px;
            box-shadow: 0 8px 24px -10px rgba(74, 7, 13, 0.08);
        }

        .skms-navbar-inner {
            padding: 12px 22px;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 16px;
        }

        .skms-logo-link {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            justify-self: start;
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
            font-size: 11.5px;
            font-weight: 500;
            color: var(--skms-muted);
            margin-top: 1px;
        }

        .skms-nav-segment {
            display: inline-flex;
            align-items: center;
            background: #F6EFEF;
            padding: 4px;
            border-radius: 14px;
            gap: 4px;
            justify-self: center;
        }

        .skms-seg-link {
            padding: 10px 22px;
            border-radius: 11px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--skms-text);
            text-decoration: none;
            transition: all 0.18s ease;
            white-space: nowrap;
        }

        .skms-seg-link:hover {
            color: var(--skms-teal);
        }

        .skms-seg-link.active {
            background: var(--skms-teal);
            color: #FFFFFF;
            box-shadow: 0 4px 12px -2px rgba(139, 13, 24, 0.35);
        }

        .skms-nav-right {
            display: flex;
            align-items: center;
            gap: 12px;
            justify-self: end;
        }

        .skms-btn-nrp {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background-color: var(--skms-teal);
            color: #FFFFFF;
            font-size: 13.5px;
            font-weight: 700;
            border-radius: 12px;
            text-decoration: none;
            box-shadow: 0 6px 16px -6px rgba(139, 13, 24, 0.4);
            transition: background-color 0.15s ease, transform 0.15s ease;
            white-space: nowrap;
        }

        .skms-btn-nrp:hover {
            background-color: var(--skms-teal-hover);
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        .skms-nrp-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            background: var(--skms-mint-soft);
            border: 1px solid var(--skms-mint-border);
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            color: var(--skms-teal);
        }

        .skms-nrp-logout {
            background: #FFFFFF;
            border: 1px solid var(--skms-mint-border);
            color: var(--skms-teal);
            font-size: 12px;
            font-weight: 700;
            padding: 5px 10px;
            border-radius: 8px;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.15s ease;
        }

        .skms-nrp-logout:hover {
            background: var(--skms-teal);
            color: #FFFFFF;
        }

        /* ── MAIN CONTAINER ────────────────────────────────────────────── */
        .skms-detail-main {
            max-width: 1320px;
            width: 100%;
            margin: 0 auto;
            padding: 26px 24px 56px;
            flex: 1;
        }

        .skms-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
            color: var(--skms-muted);
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .skms-breadcrumb a {
            color: var(--skms-muted);
            text-decoration: none;
        }

        .skms-breadcrumb a:hover {
            color: var(--skms-teal);
        }

        .skms-back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--skms-teal);
            text-decoration: none;
            margin-bottom: 18px;
            transition: all 0.15s ease;
        }

        .skms-back-link:hover {
            background: var(--skms-mint-soft);
            border-color: var(--skms-mint-border);
            color: var(--skms-teal-hover);
        }

        .skms-detail-h1 {
            font-size: 32px;
            font-weight: 800;
            color: var(--skms-forest);
            letter-spacing: -0.025em;
            line-height: 1.22;
            margin: 0 0 8px;
        }

        .skms-detail-subtitle {
            font-size: 14.5px;
            color: var(--skms-muted);
            margin: 0 0 26px;
        }

        /* 2-Column Detail Grid */
        .skms-detail-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 28px;
            align-items: start;
        }

        /* Left Content Card */
        .skms-answer-card {
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            border-radius: 20px;
            padding: 28px 32px;
            box-shadow: 0 10px 26px -14px rgba(74, 7, 13, 0.08);
        }

        .skms-answer-top {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            padding-bottom: 18px;
            margin-bottom: 22px;
            border-bottom: 1px solid #F3ECEC;
        }

        .skms-cat-pill {
            display: inline-flex;
            align-items: center;
            padding: 5px 14px;
            background: var(--skms-mint-card);
            color: var(--skms-teal);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .skms-answer-stats {
            font-size: 12.5px;
            color: var(--skms-muted);
        }

        .skms-answer-h2 {
            font-size: 20px;
            font-weight: 800;
            color: var(--skms-forest);
            letter-spacing: -0.015em;
            margin: 0 0 12px;
            line-height: 1.35;
        }

        .skms-answer-body {
            font-size: 14.5px;
            color: var(--skms-text);
            line-height: 1.75;
        }

        .skms-answer-body p {
            margin: 0 0 14px;
        }

        /* Step-by-step items when answer contains numbered points */
        .skms-steps-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin: 18px 0 22px;
        }

        .skms-step-row {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .skms-step-num {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: var(--skms-mint-card);
            color: var(--skms-teal);
            font-size: 12.5px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .skms-step-text {
            font-size: 14px;
            color: var(--skms-text);
            line-height: 1.65;
        }

        .skms-note-box {
            margin-top: 24px;
            background: var(--skms-gold-soft);
            border: 1px solid var(--skms-gold-border);
            border-radius: 14px;
            padding: 15px 18px;
        }

        .skms-note-label {
            font-size: 12px;
            font-weight: 800;
            color: var(--skms-gold-text);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .skms-note-text {
            font-size: 13px;
            color: #6B5B5D;
            line-height: 1.55;
            margin: 0;
        }

        /* Helpful Feedback Row */
        .skms-feedback-bar {
            margin-top: 26px;
            padding-top: 20px;
            border-top: 1px solid #F3ECEC;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .skms-feedback-info {
            flex: 1;
            min-width: 0;
        }

        .skms-feedback-q {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--skms-forest);
        }

        .skms-feedback-btns {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .skms-btn-feedback {
            padding: 12px 24px;
            background: #FFFFFF;
            border: 1.5px solid var(--skms-border);
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--skms-forest);
            cursor: pointer;
            font-family: inherit;
            transition: all 0.15s ease;
        }

        .skms-btn-feedback.btn-yes {
            color: var(--skms-teal);
        }

        .skms-btn-feedback.btn-yes:hover,
        .skms-btn-feedback.btn-yes.active {
            background: var(--skms-teal);
            border-color: var(--skms-teal);
            color: #FFFFFF;
        }

        .skms-btn-feedback.btn-no:hover,
        .skms-btn-feedback.btn-no.active {
            background: var(--skms-cloud);
            border-color: #C9B9BB;
        }

        /* Right Sidebar Cards */
        .skms-side-stack {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .skms-related-card {
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 26px -14px rgba(74, 7, 13, 0.08);
        }

        .skms-related-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--skms-forest);
            margin: 0 0 4px;
        }

        .skms-related-sub {
            font-size: 12px;
            color: var(--skms-muted);
            margin: 0 0 14px;
        }

        .skms-related-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 0;
            border-top: 1px solid #F3ECEC;
            text-decoration: none;
        }

        .skms-related-item:first-of-type {
            border-top: none;
            padding-top: 4px;
        }

        .skms-related-item:last-of-type {
            padding-bottom: 2px;
        }

        .skms-related-q {
            font-size: 13px;
            font-weight: 700;
            color: var(--skms-forest);
            line-height: 1.4;
            margin-bottom: 3px;
            transition: color 0.15s ease;
        }

        .skms-related-item:hover .skms-related-q {
            color: var(--skms-teal);
        }

        .skms-related-cat {
            font-size: 11px;
            color: var(--skms-muted);
        }

        .skms-related-chevron {
            color: var(--skms-teal);
            flex-shrink: 0;
        }

        .skms-still-help-card {
            background: var(--skms-mint-soft);
            border: 1px solid var(--skms-mint-border);
            border-radius: 20px;
            padding: 24px;
        }

        .skms-still-help-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--skms-forest);
            margin: 0 0 8px;
        }

        .skms-still-help-desc {
            font-size: 13px;
            color: #6B5B5D;
            line-height: 1.6;
            margin: 0 0 18px;
        }

        .skms-btn-full-teal {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 13px 18px;
            background: var(--skms-teal);
            color: #FFFFFF;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 6px 16px -6px rgba(139, 13, 24, 0.35);
            transition: background 0.15s ease, transform 0.15s ease;
        }

        .skms-btn-full-teal:hover {
            background: var(--skms-teal-hover);
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        .skms-side-caption {
            font-size: 11.5px;
            color: var(--skms-muted);
            padding-left: 2px;
        }

        /* ── MINIMAL KIOSK FOOTER ──────────────────────────────────────── */
        .skms-footer {
            background: #FFFFFF;
            border-top: 1px solid var(--skms-border);
            margin-top: auto;
        }

        .skms-footer-inner {
            max-width: 1320px;
            margin: 0 auto;
            padding: 16px 28px;
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
        }

        @media (max-width: 960px) {
            .skms-navbar-inner {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
            }
            .skms-detail-grid {
                grid-template-columns: 1fr;
            }
            .skms-detail-h1 {
                font-size: 24px;
            }
            .skms-navbar-shell {
                margin-top: 10px;
                padding: 0 14px;
            }
        }

        @media (max-width: 640px) {
            .skms-feedback-bar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body class="skms-student-body">

    <!-- ─── FLOATING TOP NAVBAR ────────────────────────────────────────────── -->
    <div class="skms-navbar-shell">
        <header class="skms-navbar">
            <div class="skms-navbar-inner">
                <a href="{{ route('skms.beranda') }}" class="skms-logo-link">
                    <svg width="34" height="34" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 7.5C4 6.11929 5.11929 5 6.5 5C10.5 5 14.5 6.5 17.5 9V14.5L21 17.5L17.5 20.5V29.5C14.5 27 10.5 25.5 6.5 25.5C5.11929 25.5 4 24.3807 4 23V7.5Z" fill="#8B0D18"/>
                        <path d="M32 7.5C32 6.11929 30.8807 5 29.5 5C25.5 5 21.5 6.5 18.5 9V14L22 17.5L18.5 21V29.5C21.5 27 25.5 25.5 29.5 25.5C30.8807 25.5 32 24.3807 32 23V7.5Z" fill="#E5A032"/>
                    </svg>
                    <div class="skms-logo-text">
                        <span class="skms-logo-title">SKMS UBAYA</span>
                        <span class="skms-logo-sub">Pusat Pengetahuan Mahasiswa</span>
                    </div>
                </a>

                <nav class="skms-nav-segment" aria-label="Navigasi Portal Mahasiswa">
                    <a href="{{ route('skms.beranda') }}" class="skms-seg-link">Beranda</a>
                    <a href="{{ route('skms.beranda', ['all' => 1]) }}" class="skms-seg-link active">Semua Informasi</a>
                </nav>

                <div class="skms-nav-right">
                    @if($studentNrp)
                        <div class="skms-nrp-pill">
                            <span>NRP: {{ $studentNrp }}</span>
                            <form action="{{ route('skms.logout') }}" method="POST" style="margin: 0; display: inline;">
                                @csrf
                                <button type="submit" class="skms-nrp-logout">Keluar</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('skms.login') }}" class="skms-btn-nrp">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <polyline points="10 17 15 12 10 7"></polyline>
                                <line x1="15" y1="12" x2="3" y2="12"></line>
                            </svg>
                            <span>Log In</span>
                        </a>
                    @endif
                </div>
            </div>
        </header>
    </div>

    <!-- ─── DETAIL MAIN CONTENT (IMAGE 3) ──────────────────────────────────── -->
    <main class="skms-detail-main">
        <div class="skms-breadcrumb">
            <a href="{{ route('skms.beranda') }}">Beranda</a>
            <span>/</span>
            <a href="{{ route('skms.beranda', request('q') ? ['search' => request('q')] : ['all' => 1]) }}">Pencarian FAQ</a>
            <span>/</span>
            <span style="color: var(--skms-forest); font-weight: 600;">Detail jawaban</span>
        </div>

        <a href="{{ route('skms.beranda', request('q') ? ['search' => request('q')] : ['all' => 1]) }}" class="skms-back-link">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Kembali ke hasil pencarian</span>
        </a>

        <h1 class="skms-detail-h1">{{ $faq->pertanyaan }}</h1>
        <p class="skms-detail-subtitle">
            Informasi dan panduan resmi seputar {{ strtolower($faq->categories->pluck('nama')->join(', ') ?: 'layanan akademik & kemahasiswaan') }} Universitas Surabaya.
        </p>

        <div class="skms-detail-grid">
            <!-- ── KOLOM KIRI: KARTU JAWABAN LENGKAP ──────────────────────── -->
            <article class="skms-answer-card">
                <div class="skms-answer-top">
                    @forelse($faq->categories as $cat)
                        <span class="skms-cat-pill">{{ $cat->nama }}</span>
                    @empty
                        <span class="skms-cat-pill">Umum</span>
                    @endforelse

                    <span class="skms-answer-stats">
                        {{ number_format($faq->views) }} Dilihat &bull; <span id="helpfulCountDisplay">{{ number_format($faq->helpful ?? 0) }}</span> Membantu
                    </span>
                </div>

                <h2 class="skms-answer-h2">{{ $faq->pertanyaan }}</h2>

                @php
                    $lines = preg_split('/\r\n|\r|\n/', trim($faq->jawaban));
                    $numberedSteps = [];
                    $introLines = [];
                    foreach ($lines as $line) {
                        $trimmed = trim($line);
                        if ($trimmed === '') continue;
                        if (preg_match('/^(\d+)[\.\)]\s+(.+)$/', $trimmed, $m)) {
                            $numberedSteps[] = ['num' => $m[1], 'text' => $m[2]];
                        } else {
                            if (empty($numberedSteps)) {
                                $introLines[] = $trimmed;
                            } else {
                                $numberedSteps[count($numberedSteps) - 1]['text'] .= "\n" . $trimmed;
                            }
                        }
                    }
                @endphp

                <div class="skms-answer-body">
                    @if(count($numberedSteps) >= 2)
                        @foreach($introLines as $p)
                            <p>{{ $p }}</p>
                        @endforeach
                        <div class="skms-steps-list">
                            @foreach($numberedSteps as $step)
                                <div class="skms-step-row">
                                    <span class="skms-step-num">{{ $step['num'] }}</span>
                                    <div class="skms-step-text">{!! nl2br(e($step['text'])) !!}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {!! nl2br(e($faq->jawaban)) !!}
                    @endif
                </div>

                {{-- ── MEDIA VIDEO PANDUAN (Jika ada) ── --}}
                @if($faq->link_video)
                    <div class="skms-media-video-section" style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--skms-border);">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#C93535" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                            </svg>
                            <span style="font-size: 14px; font-weight: 700; color: var(--skms-forest);">Video Panduan / Tutorial</span>
                        </div>

                        @if($faq->youtube_embed_url)
                            <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 12px; background: #000; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                                <iframe src="{{ $faq->youtube_embed_url }}" 
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen></iframe>
                            </div>
                        @else
                            <div style="background: #FDFBF7; border: 1px solid #F0E8D8; border-radius: 10px; padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #FEF3F2; color: #DC2626; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                    </div>
                                    <div>
                                        <div style="font-size: 13px; font-weight: 700; color: var(--skms-forest);">Tonton Video Tutorial Panduan</div>
                                        <div style="font-size: 12px; color: var(--skms-muted); word-break: break-all;">{{ $faq->link_video }}</div>
                                    </div>
                                </div>
                                <a href="{{ $faq->link_video }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #DC2626; color: #FFFFFF; font-size: 12.5px; font-weight: 600; border-radius: 8px; text-decoration: none;">
                                    <span>Buka Video</span>
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- ── BERKAS LAMPIRAN PENDUKUNG (Jika ada) ── --}}
                @if($faq->file_lampiran)
                    <div class="skms-media-file-section" style="margin-top: 20px; padding-top: 18px; border-top: 1px solid var(--skms-border);">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B0D18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            <span style="font-size: 14px; font-weight: 700; color: var(--skms-forest);">Berkas Lampiran / Unduhan</span>
                        </div>

                        <div style="background: #FFFFFF; border: 1.5px solid #ECE4E5; border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: #FDF2F3; color: #8B0D18; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; text-transform: uppercase; flex-shrink: 0;">
                                    {{ $faq->file_extension ?: 'DOC' }}
                                </div>
                                <div style="min-width: 0;">
                                    <div style="font-size: 13.5px; font-weight: 700; color: var(--skms-forest); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $faq->file_nama_asli ?: 'Berkas Lampiran' }}">
                                        {{ $faq->file_nama_asli ?: basename($faq->file_lampiran) }}
                                    </div>
                                    <div style="font-size: 12px; color: var(--skms-muted); margin-top: 2px;">
                                        Dokumen panduan resmi penunjang FAQ
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('skms.faq.download', $faq->id) }}" style="display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; background: var(--skms-teal); color: #FFFFFF; font-size: 13px; font-weight: 600; border-radius: 8px; text-decoration: none; flex-shrink: 0;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                <span>Unduh Berkas</span>
                            </a>
                        </div>
                    </div>
                @endif

                <div class="skms-note-box">
                    <div class="skms-note-label">Catatan</div>
                    <p class="skms-note-text">
                        Informasi di atas telah divalidasi oleh tim pengelola pengetahuan SKMS. Ketentuan resmi mengikuti informasi terbaru dari program studi atau layanan akademik UBAYA.
                    </p>
                </div>

                <div class="skms-feedback-bar">
                    <div class="skms-feedback-info">
                        <span class="skms-feedback-q">Apakah jawaban ini membantu?</span>
                        <div id="feedbackNotice" style="font-size: 12.5px; color: var(--skms-muted); margin-top: 4px; line-height: 1.5; display: none;"></div>
                    </div>

                    <div class="skms-feedback-btns">
                        <button type="button"
                                id="btnHelpfulYes"
                                class="skms-btn-feedback btn-yes {{ $isHelpful ? 'active' : '' }}"
                                data-url="{{ route('skms.faq.helpful', $faq->id) }}">
                            {{ $isHelpful ? '✓ Ya, membantu' : 'Ya, membantu' }}
                        </button>
                        <button type="button"
                                id="btnHelpfulNo"
                                class="skms-btn-feedback btn-no">
                            Belum
                        </button>
                    </div>
                </div>
            </article>

            <!-- ── KOLOM KANAN: FAQ TERKAIT & BANTUAN ─────────────────────── -->
            <aside class="skms-side-stack">
                <div class="skms-related-card">
                    <h3 class="skms-related-title">FAQ terkait</h3>
                    <p class="skms-related-sub">Informasi lain yang mungkin Anda perlukan.</p>

                    @forelse($relatedFaqs as $rel)
                        <a href="{{ route('skms.faq.show', $rel->id) }}" class="skms-related-item">
                            <div>
                                <div class="skms-related-q">{{ $rel->pertanyaan }}</div>
                                <div class="skms-related-cat">{{ $rel->categories->first()?->nama ?? 'Umum' }}</div>
                            </div>
                            <svg class="skms-related-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    @empty
                        <div style="font-size: 12.5px; color: var(--skms-muted);">
                            Belum ada FAQ terkait lainnya.
                        </div>
                    @endforelse
                </div>

                <div class="skms-still-help-card">
                    <h3 class="skms-still-help-title">Belum menemukan yang Anda cari?</h3>
                    <p class="skms-still-help-desc">
                        Gunakan fitur pencarian untuk menemukan panduan resmi lainnya di repositori FAQ SKMS UBAYA.
                    </p>
                    <a href="{{ route('skms.beranda') }}" class="skms-btn-full-teal">
                        Cari Topik Lain di FAQ &rarr;
                    </a>
                </div>

                <div class="skms-side-caption">
                    Gunakan pencarian untuk menemukan pertanyaan lainnya.
                </div>
            </aside>
        </div>
    </main>

    <!-- ─── MINIMAL KIOSK FOOTER ───────────────────────────────────────────── -->
    <footer class="skms-footer">
        <div class="skms-footer-inner">
            <div class="skms-footer-brand">SKMS UBAYA &mdash; Universitas Surabaya</div>
        </div>
    </footer>

    <script>
        (function () {
            const btnYes = document.getElementById('btnHelpfulYes');
            const btnNo = document.getElementById('btnHelpfulNo');
            const countDisplay = document.getElementById('helpfulCountDisplay');
            const notice = document.getElementById('feedbackNotice');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (btnYes) {
                btnYes.addEventListener('click', async function () {
                    const url = this.dataset.url;
                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            countDisplay.textContent = data.helpful;
                            btnNo.classList.remove('active');
                            if (data.is_helpful) {
                                btnYes.classList.add('active');
                                btnYes.textContent = '✓ Ya, membantu';
                                notice.style.display = 'block';
                                notice.textContent = 'Terima kasih atas tanggapan Anda!';
                            } else {
                                btnYes.classList.remove('active');
                                btnYes.textContent = 'Ya, membantu';
                                notice.style.display = 'none';
                            }
                        }
                    } catch (err) {
                        console.error(err);
                    }
                });
            }

            if (btnNo) {
                btnNo.addEventListener('click', function () {
                    btnNo.classList.add('active');
                    notice.style.display = 'block';
                    notice.textContent = 'Terima kasih atas masukannya. Silakan coba kata kunci lain atau cek FAQ terkait di sebelah kanan.';
                });
            }
        })();
    </script>
</body>
</html>

