<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sub = \App\Models\QuestionSubmission::first() ?? new \App\Models\QuestionSubmission(['id' => 13, 'nrp' => '161124025', 'pertanyaan' => 'Tes']);
$faq = \App\Models\Faq::where('status', 'published')->first();

$mail = new \App\Mail\FaqAnsweredMail($sub, $faq, 'existing_faq');
$html = $mail->render();

if (strpos($html, 'Halo Ubayatizen (NRP: 161124025)') !== false) {
    echo "[PASS] 'Halo Ubayatizen (NRP: 161124025)' berhasil muncul di email!\n";
} else {
    echo "[FAIL] 'Halo Ubayatizen' tidak ditemukan!\n";
}

if (strpos($html, 'Halo Mahasiswa UBAYA') === false) {
    echo "[PASS] 'Halo Mahasiswa UBAYA' sudah tidak ada!\n";
} else {
    echo "[FAIL] 'Halo Mahasiswa UBAYA' masih ada!\n";
}

if (strpos($html, 'dengan menautkan ke panduan FAQ resmi yang sudah terbit di repositori pengetahuan UBAYA') === false) {
    echo "[PASS] Kalimat setelah 'ditanggapi oleh staf SKMS' berhasil dihapus!\n";
} else {
    echo "[FAIL] Kalimat tambahan masih ada!\n";
}

if (strpos($html, 'Pertanyaan yang Anda ajukan telah berhasil <strong>ditanggapi oleh staf SKMS</strong>.') !== false) {
    echo "[PASS] Kalimat penutup ditanggapi oleh staf SKMS tepat dan rapi!\n";
} else {
    echo "[FAIL] Teks ditanggapi staf SKMS tidak sesuai format!\n";
}
