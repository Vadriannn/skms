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
            gap: 26px;
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

        .skms-btn-nrp {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            background-color: var(--skms-teal);
            color: #FFFFFF;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            transition: background-color 0.15s ease, transform 0.15s ease;
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

        /* ── MAIN CONTAINER (IMAGE 3) ──────────────────────────────────── */
        .skms-detail-main {
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
            margin-bottom: 14px;
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
            gap: 6px;
            font-size: 13px;
            font-weight: 700;
            color: var(--skms-teal);
            text-decoration: none;
            margin-bottom: 10px;
        }

        .skms-back-link:hover {
            color: var(--skms-teal-hover);
            text-decoration: underline;
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
            font-size: 14px;
            color: var(--skms-muted);
            margin: 0 0 26px;
        }

        /* 2-Column Detail Grid */
        .skms-detail-grid {
            display: grid;
            grid-template-columns: 1fr 330px;
            gap: 28px;
            align-items: start;
        }

        /* Left Content Card */
        .skms-answer-card {
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            border-radius: 16px;
            padding: 26px 30px;
        }

        .skms-answer-top {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            padding-bottom: 18px;
            margin-bottom: 22px;
            border-bottom: 1px solid #EEF3F1;
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
            margin-top: 22px;
            background: var(--skms-mint-soft);
            border: 1px solid var(--skms-mint-border);
            border-radius: 12px;
            padding: 14px 18px;
        }

        .skms-note-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--skms-teal);
            margin-bottom: 3px;
        }

        .skms-note-text {
            font-size: 12.5px;
            color: #4D6762;
            line-height: 1.55;
            margin: 0;
        }

        /* Helpful Feedback Row */
        .skms-feedback-bar {
            margin-top: 26px;
            padding-top: 20px;
            border-top: 1px solid #EEF3F1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .skms-feedback-q {
            font-size: 14px;
            font-weight: 700;
            color: var(--skms-forest);
        }

        .skms-feedback-btns {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .skms-btn-feedback {
            padding: 9px 22px;
            background: #FFFFFF;
            border: 1px solid var(--skms-border);
            border-radius: 9px;
            font-size: 13px;
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
            background: var(--skms-mint-soft);
            border-color: var(--skms-teal);
            color: var(--skms-teal);
        }

        .skms-btn-feedback.btn-no:hover,
        .skms-btn-feedback.btn-no.active {
            background: var(--skms-cloud);
            border-color: #B9C8C4;
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
            border-radius: 16px;
            padding: 22px;
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
            border-top: 1px solid #EEF3F1;
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
            border-radius: 16px;
            padding: 22px;
        }

        .skms-still-help-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--skms-forest);
            margin: 0 0 8px;
        }

        .skms-still-help-desc {
            font-size: 12.5px;
            color: #4D6762;
            line-height: 1.6;
            margin: 0 0 16px;
        }

        .skms-btn-full-teal {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 11px 16px;
            background: var(--skms-teal);
            color: #FFFFFF;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.15s ease;
        }

        .skms-btn-full-teal:hover {
            background: var(--skms-teal-hover);
            color: #FFFFFF;
        }

        .skms-side-caption {
            font-size: 11.5px;
            color: var(--skms-muted);
            padding-left: 2px;
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
            .skms-detail-grid {
                grid-template-columns: 1fr;
            }
            .skms-detail-h1 {
                font-size: 24px;
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
                <a href="{{ route('skms.beranda', ['all' => 1]) }}" class="skms-nav-link active">Informasi</a>

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
                        <span>Masuk Mahasiswa (NRP)</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </header>

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
                        {{ number_format($faq->views) }} views &bull; <span id="helpfulCountDisplay">{{ number_format($faq->helpful ?? 0) }}</span> Membantu
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

                <div class="skms-note-box">
                    <div class="skms-note-label">Catatan</div>
                    <p class="skms-note-text">
                        Informasi di atas telah divalidasi oleh tim pengelola pengetahuan SKMS. Ketentuan resmi mengikuti informasi terbaru dari program studi atau layanan akademik UBAYA.
                    </p>
                </div>

                <div class="skms-feedback-bar">
                    <div>
                        <span class="skms-feedback-q">Apakah jawaban ini membantu?</span>
                        <div id="feedbackNotice" style="font-size: 12px; color: var(--skms-muted); margin-top: 3px; display: none;"></div>
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
                    <h3 class="skms-still-help-title">Masih butuh bantuan?</h3>
                    @if($studentNrp)
                        <p class="skms-still-help-desc">
                            Anda sudah masuk dengan NRP <strong>{{ $studentNrp }}</strong>. Gunakan kolom pencarian atau hubungi layanan administrasi fakultas/direktorat terkait di UBAYA.
                        </p>
                        <a href="{{ route('skms.beranda', ['all' => 1]) }}" class="skms-btn-full-teal">
                            Cari Pertanyaan Lainnya
                        </a>
                    @else
                        <p class="skms-still-help-desc">
                            Masuk menggunakan NRP untuk mengakses layanan bantuan mahasiswa dan menyampaikan pertanyaan Anda.
                        </p>
                        <a href="{{ route('skms.login') }}" class="skms-btn-full-teal">
                            Masuk Mahasiswa (NRP)
                        </a>
                    @endif
                </div>

                <div class="skms-side-caption">
                    Gunakan pencarian untuk menemukan pertanyaan lainnya.
                </div>
            </aside>
        </div>
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

