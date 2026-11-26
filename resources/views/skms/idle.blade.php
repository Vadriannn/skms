<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKMS UBAYA — Sentuh Layar untuk Memulai</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * {
            box-sizing: border-box;
            user-select: none;
            -webkit-user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: #3B050A;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .idle-screen {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 46px 64px 36px;
            cursor: pointer;
            text-decoration: none;
            color: #FFFFFF;
            overflow: hidden;
            transition: opacity 0.24s ease;
        }

        .idle-screen.is-leaving {
            opacity: 0;
        }

        /* Full-Bleed Campus Photo with Subtle Ken Burns Motion */
        .idle-bg-photo {
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('images/ubaya-campus.jpg') }}');
            background-size: cover;
            background-position: center center;
            transform: scale(1.02);
            animation: idleKenBurns 24s ease-in-out infinite alternate;
            z-index: 1;
        }

        @keyframes idleKenBurns {
            0% { transform: scale(1.01) translate3d(0, 0, 0); }
            100% { transform: scale(1.07) translate3d(-1.2%, -0.8%, 0); }
        }

        /* Directional UBAYA Crimson Scrim */
        .idle-scrim {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(96deg,
                    rgba(56, 4, 10, 0.96) 0%,
                    rgba(96, 8, 17, 0.90) 36%,
                    rgba(139, 13, 24, 0.56) 66%,
                    rgba(48, 5, 10, 0.30) 100%
                ),
                linear-gradient(180deg,
                    rgba(40, 3, 7, 0.24) 0%,
                    rgba(40, 3, 7, 0.04) 45%,
                    rgba(34, 3, 6, 0.62) 100%
                );
            z-index: 2;
        }

        /* Subtle Top-Right Decorative Dot Grid */
        .idle-dots {
            position: absolute;
            top: 48px;
            right: 58px;
            width: 112px;
            height: 76px;
            background-image: radial-gradient(rgba(255, 255, 255, 0.28) 1.5px, transparent 1.5px);
            background-size: 16px 16px;
            z-index: 3;
            pointer-events: none;
        }

        /* Top Brand Bar */
        .idle-header {
            position: relative;
            z-index: 4;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .idle-logo-box {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 14px 30px -10px rgba(0, 0, 0, 0.35);
            flex-shrink: 0;
        }

        .idle-brand-text {
            display: flex;
            flex-direction: column;
        }

        .idle-brand-title {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #FFFFFF;
            line-height: 1.15;
        }

        .idle-brand-sub {
            font-size: 13px;
            font-weight: 500;
            color: rgba(255, 240, 242, 0.78);
            margin-top: 2px;
        }

        /* Main Hero Typography & CTA */
        .idle-center {
            position: relative;
            z-index: 4;
            max-width: 680px;
            margin: auto 0;
            padding: 18px 0;
        }

        .idle-kicker {
            font-size: 12.5px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #F4C467;
            margin-bottom: 18px;
        }

        .idle-headline {
            font-size: clamp(40px, 5.2vw, 66px);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: #FFFFFF;
            margin: 0 0 22px;
        }

        .idle-headline-gold {
            display: block;
            color: #F5B84B;
        }

        .idle-desc {
            font-size: clamp(16px, 1.55vw, 20px);
            font-weight: 400;
            line-height: 1.6;
            color: rgba(255, 244, 245, 0.88);
            max-width: 520px;
            margin: 0 0 40px;
        }

        /* Tactile White CTA Button */
        .idle-cta {
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            min-width: 390px;
            padding: 15px 26px 15px 16px;
            background: #FFFFFF;
            color: #5E0A12;
            border-radius: 20px;
            box-shadow: 0 22px 46px -14px rgba(24, 2, 5, 0.55);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
        }

        .idle-screen:hover .idle-cta {
            transform: translateY(-2px);
            box-shadow: 0 26px 52px -14px rgba(24, 2, 5, 0.65);
        }

        .idle-screen:active .idle-cta {
            transform: scale(0.985);
        }

        .idle-cta-left {
            display: inline-flex;
            align-items: center;
            gap: 16px;
        }

        .idle-touch-badge {
            position: relative;
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #FDF2F3;
            color: #8B0D18;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .idle-touch-badge::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 14px;
            border: 2px solid rgba(139, 13, 24, 0.35);
            animation: idlePulseRing 2.4s cubic-bezier(0.22, 1, 0.36, 1) infinite;
        }

        @keyframes idlePulseRing {
            0% { transform: scale(1); opacity: 0.75; }
            75% { transform: scale(1.26); opacity: 0; }
            100% { transform: scale(1.26); opacity: 0; }
        }

        .idle-cta-label {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: #4A070D;
        }

        .idle-cta-arrow {
            color: #8B0D18;
            flex-shrink: 0;
            animation: idleArrowNudge 2.4s ease-in-out infinite;
        }

        @keyframes idleArrowNudge {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(4px); }
        }

        /* Bottom Minimal Footer */
        .idle-footer {
            position: relative;
            z-index: 4;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            font-size: 12.5px;
        }

        .idle-footer-left {
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 245, 245, 0.85);
        }

        .idle-footer-right {
            font-weight: 500;
            color: rgba(255, 245, 245, 0.76);
        }

        @media (max-width: 768px) {
            .idle-screen {
                padding: 28px 24px 24px;
            }
            .idle-dots {
                display: none;
            }
            .idle-cta {
                min-width: 0;
                width: 100%;
                padding: 13px 20px 13px 14px;
            }
            .idle-cta-label {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('skms.beranda') }}" id="idleScreen" class="idle-screen" aria-label="Sentuh layar untuk memulai SKMS UBAYA">
        <div class="idle-bg-photo" aria-hidden="true"></div>
        <div class="idle-scrim" aria-hidden="true"></div>
        <div class="idle-dots" aria-hidden="true"></div>

        <!-- Top Brand -->
        <header class="idle-header">
            <div class="idle-logo-box">
                <svg width="32" height="32" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 7.5C4 6.11929 5.11929 5 6.5 5C10.5 5 14.5 6.5 17.5 9V14.5L21 17.5L17.5 20.5V29.5C14.5 27 10.5 25.5 6.5 25.5C5.11929 25.5 4 24.3807 4 23V7.5Z" fill="#8B0D18"/>
                    <path d="M32 7.5C32 6.11929 30.8807 5 29.5 5C25.5 5 21.5 6.5 18.5 9V14L22 17.5L18.5 21V29.5C21.5 27 25.5 25.5 29.5 25.5C30.8807 25.5 32 24.3807 32 23V7.5Z" fill="#E5A032"/>
                </svg>
            </div>
            <div class="idle-brand-text">
                <span class="idle-brand-title">SKMS UBAYA</span>
                <span class="idle-brand-sub">Pusat Pengetahuan Mahasiswa</span>
            </div>
        </header>

        <!-- Main Editorial Message & CTA -->
        <main class="idle-center">
            <div class="idle-kicker">Selamat Datang di SKMS UBAYA</div>
            <h1 class="idle-headline">
                Informasi kuliah,
                <span class="idle-headline-gold">satu sentuhan saja.</span>
            </h1>
            <p class="idle-desc">
                Panduan resmi akademik, keuangan, dan kemahasiswaan Universitas Surabaya.
            </p>

            <div class="idle-cta">
                <div class="idle-cta-left">
                    <span class="idle-touch-badge" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 11V6a2 2 0 0 0-2-2a2 2 0 0 0-2 2"></path>
                            <path d="M14 10V4a2 2 0 0 0-2-2a2 2 0 0 0-2 2v2"></path>
                            <path d="M10 10.5V6a2 2 0 0 0-2-2a2 2 0 0 0-2 2v8"></path>
                            <path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"></path>
                        </svg>
                    </span>
                    <span class="idle-cta-label">Sentuh layar untuk memulai</span>
                </div>
                <svg class="idle-cta-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </div>
        </main>

        <!-- Minimal Footer -->
        <footer class="idle-footer">
            <span class="idle-footer-left">Universitas Surabaya</span>
            <span class="idle-footer-right">Layanan mandiri mahasiswa</span>
        </footer>
    </a>

    <script>
        (function () {
            const screen = document.getElementById('idleScreen');
            const targetUrl = @json(route('skms.beranda'));
            let navigating = false;

            function wakeUp(e) {
                if (navigating) return;
                if (e) e.preventDefault();
                navigating = true;
                if (screen) screen.classList.add('is-leaving');
                setTimeout(function () {
                    window.location.href = targetUrl;
                }, 200);
            }

            if (screen) {
                screen.addEventListener('click', wakeUp);
            }

            document.addEventListener('keydown', function () {
                wakeUp();
            });
        })();
    </script>
</body>
</html>
