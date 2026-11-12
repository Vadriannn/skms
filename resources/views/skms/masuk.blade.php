<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Portal Mahasiswa - SKMS UBAYA</title>
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
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
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
            <p>Masukkan Nama &amp; NRP Anda untuk Personalisasi Informasi &amp; FAQ</p>
        </div>

        <!-- Alert Notifikasi Server -->
        @if(session('info'))
            <div class="alert" style="background-color: #EFF6FF; border: 1px solid #BFDBFE; color: #1D4ED8;">
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

        <!-- Form Masuk Mahasiswa -->
        <form id="studentLoginForm" action="{{ route('skms.login.post') }}" method="POST">
            @csrf

            <!-- Nama Lengkap Field -->
            <div class="form-group">
                <div class="input-icon-wrapper">
                    <!-- User Icon -->
                    <svg class="left-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <input type="text" 
                           id="namaInput" 
                           name="nama" 
                           value="{{ old('nama') }}" 
                           placeholder="Nama Lengkap Mahasiswa" 
                           required 
                           autofocus
                           autocomplete="name">
                </div>
            </div>

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
                           placeholder="NRP (9 Digit Angka, misal: 160421001)" 
                           maxlength="9" 
                           inputmode="numeric" 
                           required
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
    </div>

    <script>
        const form = document.getElementById('studentLoginForm');
        const nrpInput = document.getElementById('nrpInput');
        const namaInput = document.getElementById('namaInput');
        const clientErrorBox = document.getElementById('clientErrorBox');
        const clientErrorText = document.getElementById('clientErrorText');

        // Otomatis bersihkan karakter non-angka saat mengetik NRP
        nrpInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            clientErrorBox.style.display = 'none';
        });

        // Validasi form saat disubmit
        form.addEventListener('submit', function(e) {
            const namaVal = namaInput.value.trim();
            const nrpVal = nrpInput.value.trim();

            if (!namaVal) {
                e.preventDefault();
                clientErrorText.textContent = 'Nama lengkap wajib diisi.';
                clientErrorBox.style.display = 'flex';
                namaInput.focus();
                return false;
            }

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

