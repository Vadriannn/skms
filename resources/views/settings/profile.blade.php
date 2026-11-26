@extends('layouts.app', ['title' => 'Pengaturan akun - SKMS'])

@section('content')
@php
    $roleLabels = [
        'super_admin' => 'Super Admin',
        'validator'   => 'Validator',
        'data_entry'  => 'Data Entry',
    ];
    $roleLabel = $roleLabels[$user->role] ?? ucfirst(str_replace('_', ' ', $user->role));
@endphp

<div>
    <!-- Page Intro (Sesuai Gambar 6) -->
    <div style="margin-bottom: 18px;">
        <h1 class="skmk-page-main-title">Pengaturan akun</h1>
        <p class="skmk-page-subtitle">Kelola profil dan keamanan akun Anda dengan mudah.</p>
    </div>

    <!-- Top Tabs (Profil & keamanan / Akun role) -->
    <div style="display: flex; align-items: center; gap: 24px; border-bottom: 1px solid #ECE4E5; margin-bottom: 24px;">
        <span style="padding-bottom: 10px; border-bottom: 2px solid #8B0D18; color: #8B0D18; font-weight: 600; font-size: 13.5px;">
            Profil &amp; keamanan
        </span>
        <span style="padding-bottom: 10px; color: #6B5B5D; font-size: 13.5px; font-weight: 500;">
            Akun {{ strtolower($roleLabel) }}
        </span>
    </div>

    <!-- 2-Column Layout (Left: Forms, Right: Security Tips & Help) -->
    <div class="validator-detail-container">

        <!-- Left Column: Form Cards -->
        <div class="validator-detail-main">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Card 1: Informasi Profil -->
                <div class="vd-detail-card" style="margin-bottom: 18px;">
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; padding-bottom: 16px; border-bottom: 1px solid #F3ECEC; margin-bottom: 18px;">
                        <div>
                            <h2 style="font-size: 16px; font-weight: 700; color: #1F1415; margin-bottom: 3px;">Informasi profil</h2>
                            <p style="font-size: 12.5px; color: #6B5B5D;">Identitas yang digunakan dalam aktivitas {{ strtolower($roleLabel) }}.</p>
                        </div>
                        <span style="background: #FDF2F3; color: #8B0D18; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 6px;">
                            {{ $roleLabel }}
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label class="form-label" style="font-size: 12.5px;">Nama lengkap <span class="required">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-control">
                            @error('name')
                                <div style="color: var(--red-text); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label" style="font-size: 12.5px;">Alamat email <span class="required">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-control">
                            @error('email')
                                <div style="color: var(--red-text); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label" style="font-size: 12.5px;">Peran / hak akses</label>
                        <input type="text" value="{{ $roleLabel }}" disabled class="form-control" style="background: #F6F0F0; color: #6B5B5D;">
                        <div style="font-size: 11.5px; color: #948486; margin-top: 6px;">Peran akun dikelola oleh administrator.</div>
                    </div>
                </div>

                <!-- Card 2: Ubah Kata Sandi -->
                <div class="vd-detail-card" style="margin-bottom: 18px;">
                    <div style="padding-bottom: 14px; border-bottom: 1px solid #F3ECEC; margin-bottom: 18px;">
                        <h2 style="font-size: 16px; font-weight: 700; color: #1F1415; margin-bottom: 3px;">Ubah kata sandi</h2>
                        <p style="font-size: 12.5px; color: #6B5B5D;">Opsional. Biarkan kosong jika Anda tidak ingin mengubah kata sandi saat ini.</p>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label class="form-label" style="font-size: 12.5px;">Kata sandi baru</label>
                            <input type="password" name="password" placeholder="Minimal 6 karakter" class="form-control">
                            @error('password')
                                <div style="color: var(--red-text); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label" style="font-size: 12.5px;">Konfirmasi kata sandi baru</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi baru" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Bottom Action Row -->
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                    <span style="font-size: 12.5px; color: #948486;">Periksa kembali sebelum menyimpan perubahan.</span>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <a href="{{ url()->previous() }}" class="btn-secondary" style="padding: 9px 18px; font-size: 13px; font-weight: 600;">
                            Batal
                        </a>
                        <button type="submit" class="btn-modal-submit" style="padding: 10px 20px; font-size: 13.5px;">
                            Simpan perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right Column: Security Tips & Help (Persis Gambar 6) -->
        <div class="validator-detail-actions">
            <div class="vd-action-card">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #FDF2F3; color: #8B0D18; display: flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div style="font-size: 15px; font-weight: 700; color: #1F1415; margin-bottom: 6px;">Jaga akun tetap aman</div>
                <p style="font-size: 12.5px; color: #6B5B5D; line-height: 1.55; padding-bottom: 14px; border-bottom: 1px solid #F3ECEC; margin-bottom: 14px;">
                    Gunakan kata sandi yang unik dan jangan bagikan akses akun Anda kepada orang lain.
                </p>
                <div style="font-size: 12.5px; font-weight: 700; color: #1F1415; margin-bottom: 8px;">Tips kata sandi</div>
                <ul style="font-size: 12.5px; color: #6B5B5D; padding-left: 16px; line-height: 1.7;">
                    <li>Gunakan minimal 6 karakter.</li>
                    <li>Gabungkan huruf, angka, dan simbol.</li>
                    <li>Hindari nama atau tanggal lahir.</li>
                </ul>
            </div>

            <div style="padding: 4px 8px;">
                <div style="font-size: 13.5px; font-weight: 700; color: #1F1415; margin-bottom: 4px;">Butuh bantuan?</div>
                <p style="font-size: 12.5px; color: #6B5B5D; line-height: 1.5; margin-bottom: 8px;">
                    Hubungi administrator kampus untuk perubahan peran atau kendala akses.
                </p>
                <a href="javascript:void(0)" onclick="alert('Silakan hubungi Super Admin SKMS untuk bantuan perubahan peran atau akses akun.')" class="skmk-link-action" style="font-size: 12.5px;">
                    <span>Hubungi administrator</span>
                    <span>&#8599;</span>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
