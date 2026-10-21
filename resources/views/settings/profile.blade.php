@extends('layouts.app', ['title' => 'Pengaturan Akun - SKMS'])

@section('content')
    <div class="page-header">
        <h1 class="page-title">Pengaturan Akun</h1>
    </div>

    <div style="max-width: 680px;">
        <div class="section-card" style="padding: 28px;">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Username / Nama -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; color: var(--text-heading); margin-bottom: 8px;">
                        Username / Nama Lengkap <span style="color: var(--red-text);">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 11px 14px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
                    @error('name')
                        <div style="color: var(--red-text); font-size: 12px; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; color: var(--text-heading); margin-bottom: 8px;">
                        Alamat Email <span style="color: var(--red-text);">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required style="width: 100%; padding: 11px 14px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
                    @error('email')
                        <div style="color: var(--red-text); font-size: 12px; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Peran (Readonly) -->
                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="display: block; font-weight: 600; color: var(--text-heading); margin-bottom: 8px;">
                        Peran / Hak Akses
                    </label>
                    <input type="text" value="{{ ucfirst(str_replace('_', ' ', $user->role)) }}" disabled style="width: 100%; padding: 11px 14px; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-page); color: var(--text-muted); font-size: 14px;">
                </div>

                <hr style="border: none; border-top: 1px solid var(--border-subtle); margin: 24px 0;">

                <h3 style="font-size: 15px; font-weight: 600; color: var(--text-heading); margin-bottom: 6px;">
                    Ganti Password (Opsional)
                </h3>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 18px;">
                    Kosongkan jika Anda tidak ingin mengubah password saat ini.
                </p>

                <!-- Password Baru -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: var(--text-heading); margin-bottom: 8px;">
                        Password Baru
                    </label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" style="width: 100%; padding: 11px 14px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
                    @error('password')
                        <div style="color: var(--red-text); font-size: 12px; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="form-group" style="margin-bottom: 28px;">
                    <label style="display: block; font-weight: 500; font-size: 13px; color: var(--text-heading); margin-bottom: 8px;">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru" style="width: 100%; padding: 11px 14px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
                </div>

                <button type="submit" class="btn-primary" style="width: auto; padding: 11px 24px;">
                    Simpan Pengaturan
                </button>
            </form>
        </div>
    </div>
@endsection
