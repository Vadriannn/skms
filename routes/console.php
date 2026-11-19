<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:test {recipient? : Alamat email penerima pengujian}', function () {
    $recipient = $this->argument('recipient') ?: config('mail.from.address');

    if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
        $this->error("Alamat email penerima [{$recipient}] tidak valid.");
        return 1;
    }

    $username = config('mail.mailers.smtp.username');
    $password = config('mail.mailers.smtp.password');

    if (empty($username) || $username === 'your_email@gmail.com' || empty($password) || $password === 'your_google_app_password') {
        $this->warn("PERINGATAN: MAIL_USERNAME atau MAIL_PASSWORD di file .env masih berupa placeholder!");
        $this->warn("Silakan masukkan alamat Gmail dan Google App Password (16 karakter) Anda di file .env terlebih dahulu.");
        return 1;
    }

    $this->info("Mengirim email uji coba ke: {$recipient}...");
    $this->line("Host: " . config('mail.mailers.smtp.host') . ":" . config('mail.mailers.smtp.port'));
    $this->line("Pengirim: " . config('mail.from.address'));

    try {
        \Illuminate\Support\Facades\Mail::to($recipient)->send(new \App\Mail\TestSmtpMail($recipient));
        $this->info("✓ BERHASIL! Email uji coba berhasil dikirim ke: {$recipient}");
        return 0;
    } catch (\Throwable $e) {
        $this->error("✗ GAGAL: Terjadi kesalahan saat mengirim email.");
        $this->error($e->getMessage());
        return 1;
    }
})->purpose('Kirim email uji coba untuk mengetes koneksi SMTP Gmail');
