<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Mahasiswa - SKMS UBAYA</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .student-portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background-color: var(--primary-light, #EEF2FF);
            color: var(--primary, #6366F1);
            font-size: 12px;
            font-weight: 600;
            border-radius: 9999px;
            margin-bottom: 12px;
        }

        .nrp-helper-text {
            font-size: 12px;
            color: #64748B;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            text-align: left;
        }

        .nrp-helper-text strong {
            color: #1E293B;
        }

        .alert-warning-box {
            background-color: #FEF2F2;
            border: 1px solid #FECACA;
            color: #DC2626;
            padding: 12px 16px;
            border-radius: var(--radius-md, 10px);
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
            margin-top: 18px;
            text-align: center;
        }

        .back-link-wrapper a {
            color: #64748B;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.15s ease;
        }

        .back-link-wrapper a:hover {
            color: #6366F1;
        }
    </style>
</head>
<body class="login-wrapper">

    <div class="login-card">
        <!-- Logo & Header -->
        <div class="login-logo">
            <div class="student-portal-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                </svg>
                Portal Mahasiswa UBAYA
            </div>
            <!-- Open Book SVG -->
            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#6366F1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                <line x1="12" y1="6" x2="12" y2="18"></line>
            </svg>
            <h1>SKMS UBAYA</h1>
            <p>Masukkan 9 Digit NRP Anda untuk Masuk ke Portal Mahasiswa</p>
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
                <div class="nrp-helper-text">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span>NRP mahasiswa UBAYA wajib terdiri dari <strong>9 digit angka</strong>.</span>
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
</body>
</html>
