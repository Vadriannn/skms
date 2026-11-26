<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SKMS</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-wrapper" style="background-color: #FAF8F7;">

    <div class="login-card" style="border: 1px solid #ECE4E5;">
        <!-- Logo & Header (SKMK Dual-Tone Book Logo) -->
        <div class="login-logo">
            <svg width="52" height="52" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 14px;">
                <path d="M4 7.5C4 6.11929 5.11929 5 6.5 5C10.5 5 14.5 6.5 17.5 9V14.5L21 17.5L17.5 20.5V29.5C14.5 27 10.5 25.5 6.5 25.5C5.11929 25.5 4 24.3807 4 23V7.5Z" fill="#8B0D18"/>
                <path d="M32 7.5C32 6.11929 30.8807 5 29.5 5C25.5 5 21.5 6.5 18.5 9V14L22 17.5L18.5 21V29.5C21.5 27 25.5 25.5 29.5 25.5C30.8807 25.5 32 24.3807 32 23V7.5Z" fill="#E5A032"/>
            </svg>
            <h1 style="color: #1F1415; font-size: 24px;">SKMS</h1>
            <p style="color: #8B0D18; font-weight: 600; font-size: 13px; margin-bottom: 4px;">Student Knowledge Management System</p>
            <p style="font-size: 13px; color: #6B5B5D;">Masuk ke ruang kerja akun Anda</p>
        </div>

        <!-- Alert Notifikasi -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('login') }}" method="POST">
            @csrf

            <!-- Username / Email Field -->
            <div class="form-group">
                <div class="input-icon-wrapper">
                    <!-- User Icon -->
                    <svg class="left-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <input type="text" name="login" value="{{ old('login') }}" placeholder="Username / Email" required autofocus>
                </div>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <div class="input-icon-wrapper">
                    <!-- Lock Icon -->
                    <svg class="left-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input type="password" id="passwordInput" name="password" placeholder="Password" required>
                    <!-- Eye Toggle Button -->
                    <button type="button" class="toggle-password-btn" id="togglePasswordBtn" title="Lihat password">
                        <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary">
                Login
            </button>
        </form>

        <a href="javascript:void(0)" onclick="alert('Silakan hubungi Super Admin untuk bantuan reset kata sandi.')" class="forgot-password-link">
            Lupa password?
        </a>

        <div style="margin-top: 32px; padding-top: 18px; border-top: 1px dashed #E2E8F0; text-align: left; font-size: 12px; color: #94A3B8;">
            <p style="font-weight: 600; margin-bottom: 4px; color: #64748B;">Akun Demo:</p>
            <p>• Admin: <code>admin@gmail.com</code> / <code>admin</code></p>
            <p>• Data Entry: <code>dataentry@gmail.com</code> / <code>dataentry</code></p>
            <p>• Validator: <code>validator@gmail.com</code> / <code>validator</code></p>


        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('passwordInput');
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            if (isPassword) {
                // Eye Off Icon
                eyeIcon.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                `;
            } else {
                // Normal Eye Icon
                eyeIcon.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
            }
        });
    </script>
</body>
</html>
