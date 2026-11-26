<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Mahasiswa - SKMS UBAYA</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body.login-wrapper {
            background: radial-gradient(circle at top center, #FBE7E9 0%, #FAF8F7 48%, #F7F2F2 100%) !important;
        }

        .login-card.kiosk-card {
            max-width: 460px;
            border-radius: 22px;
            border: 1px solid #ECE4E5;
            box-shadow: 0 20px 44px -20px rgba(74, 7, 13, 0.22);
            padding: 36px 34px 30px;
            position: relative;
            overflow: hidden;
        }

        .login-card.kiosk-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #8B0D18 0%, #B81624 65%, #E5A032 100%);
        }

        .student-portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background-color: #FDF2F3;
            border: 1px solid #F1CFD3;
            color: #8B0D18;
            font-size: 12px;
            font-weight: 700;
            border-radius: 9999px;
            margin-bottom: 14px;
        }

        .kiosk-card .input-icon-wrapper input {
            height: 54px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.03em;
            border-radius: 12px;
        }

        .kiosk-card .btn-primary {
            padding: 15px 20px !important;
            border-radius: 12px !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            box-shadow: 0 8px 20px -8px rgba(139, 13, 24, 0.45);
        }

        .nrp-helper-text {
            font-size: 12.5px;
            color: #6B5B5D;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
            text-align: left;
        }

        .nrp-helper-text strong {
            color: #1F1415;
        }

        .alert-warning-box {
            background-color: #FEF2F2;
            border: 1px solid #FECACA;
            color: #DC2626;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 18px;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-warning-box svg {
            flex-shrink: 0;
            color: #DC2626;
        }

        .back-link-wrapper {
            margin-top: 20px;
            text-align: center;
        }

        .back-link-wrapper a {
            color: #6B5B5D;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            border-radius: 10px;
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }

        .back-link-wrapper a:hover {
            color: #8B0D18;
            background: #FDF2F3;
            border-color: #F1CFD3;
        }
    </style>
</head>
<body class="login-wrapper">

    <div class="login-card kiosk-card">
        <!-- Logo & Header -->
        <div class="login-logo">
            <div class="student-portal-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                </svg>
                Portal Mahasiswa
            </div>
            <!-- SKMK Dual-Tone Book Logo -->
            <svg width="52" height="52" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 12px;">
                <path d="M4 7.5C4 6.11929 5.11929 5 6.5 5C10.5 5 14.5 6.5 17.5 9V14.5L21 17.5L17.5 20.5V29.5C14.5 27 10.5 25.5 6.5 25.5C5.11929 25.5 4 24.3807 4 23V7.5Z" fill="#8B0D18"/>
                <path d="M32 7.5C32 6.11929 30.8807 5 29.5 5C25.5 5 21.5 6.5 18.5 9V14L22 17.5L18.5 21V29.5C21.5 27 25.5 25.5 29.5 25.5C30.8807 25.5 32 24.3807 32 23V7.5Z" fill="#E5A032"/>
            </svg>
            <h1 style="color: #1F1415;">SKMS UBAYA</h1>
            <p style="color: #6B5B5D;">Pusat Pengetahuan Mahasiswa &mdash; Masukkan 9 Digit NRP</p>
        </div>

        <!-- Alert Notifikasi Server -->
        @if(session('info'))
            <div class="alert" style="background-color: #EFF6FF; border: 1px solid #BFDBFE; color: #1D4ED8; margin-bottom: 18px;">
                {{ session('info') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-warning-box">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Alert Peringatan Client-Side (Instant Validation) -->
        <div id="clientErrorBox" class="alert-warning-box" style="display: none;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span id="clientErrorText">NRP tidak valid. NRP mahasiswa UBAYA harus terdiri dari 9 digit angka (contoh: 160421001).</span>
        </div>

        <!-- Form Masuk Mahasiswa (Hanya NRP) -->
        <form id="studentLoginForm" action="{{ route('skms.login.post') }}" method="POST">
            @csrf

            <!-- NRP Field -->
            <div class="form-group" style="margin-bottom: 24px;">
                <div class="input-icon-wrapper">
                    <!-- Student Card Icon -->
                    <svg class="left-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                        <line x1="7" y1="8" x2="13" y2="8"></line>
                        <line x1="7" y1="12" x2="17" y2="12"></line>
                        <line x1="7" y1="16" x2="11" y2="16"></line>
                    </svg>
                    <input type="text" 
                           id="nrpInput" 
                           name="nrp" 
                           value="{{ old('nrp') }}" 
                           placeholder="Masukkan 9 Digit NRP (contoh: 160421001)" 
                           maxlength="9" 
                           inputmode="numeric" 
                           required
                           autofocus
                           autocomplete="off">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary" style="padding: 13px;">
                <span>Masuk ke Halaman Beranda</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        </form>

        <div class="back-link-wrapper">
            <a href="{{ route('skms.beranda') }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>

    <script>
        const form = document.getElementById('studentLoginForm');
        const nrpInput = document.getElementById('nrpInput');
        const clientErrorBox = document.getElementById('clientErrorBox');
        const clientErrorText = document.getElementById('clientErrorText');

        // Otomatis bersihkan karakter non-angka saat mengetik NRP
        nrpInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            clientErrorBox.style.display = 'none';
        });

        // Validasi form saat disubmit
        form.addEventListener('submit', function(e) {
            const nrpVal = nrpInput.value.trim();

            // Validasi 9 digit angka
            if (!/^[0-9]{9}$/.test(nrpVal)) {
                e.preventDefault();
                clientErrorText.textContent = 'NRP tidak valid. NRP mahasiswa UBAYA harus terdiri dari 9 digit angka (contoh: 160421001).';
                clientErrorBox.style.display = 'flex';
                nrpInput.focus();
                return false;
            }
        });
    </script>
    @include('skms.partials.idle_redirect')
</body>
</html>
