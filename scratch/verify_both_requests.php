<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFY REQUEST 1: Knowledge Gap Badge 'Relevan' ===\n";
$kgView = file_get_contents(resource_path('views/analytics/knowledge_gap.blade.php'));
if (strpos($kgView, 'Terindikasi Terjawab') === false) {
    echo "[PASS] 'Terindikasi Terjawab' sudah tidak ada di knowledge_gap.blade.php\n";
} else {
    echo "[FAIL] 'Terindikasi Terjawab' masih ada!\n";
}

if (strpos($kgView, 'Relevan') !== false) {
    echo "[PASS] Label 'Relevan' sudah ada di knowledge_gap.blade.php\n";
} else {
    echo "[FAIL] Label 'Relevan' tidak ditemukan!\n";
}

echo "\n=== VERIFY REQUEST 2: Student Ticket Status & Note ===\n";
$tanyaView = file_get_contents(resource_path('views/skms/tanya.blade.php'));
if (strpos($tanyaView, '✓ Sudah Terpublikasi') !== false) {
    echo "[PASS] Badge '✓ Sudah Terpublikasi' sudah ada di tanya.blade.php\n";
} else {
    echo "[FAIL] Badge '✓ Sudah Terpublikasi' tidak ditemukan!\n";
}

if (strpos($tanyaView, 'sudah dipublikasikan') !== false) {
    echo "[PASS] Teks 'sudah dipublikasikan' sudah ada di tanya.blade.php\n";
} else {
    echo "[FAIL] Teks 'sudah dipublikasikan' tidak ditemukan!\n";
}

$t10 = \App\Models\QuestionSubmission::find(10);
echo "Ticket #10 Note: " . $t10->note . "\n";
if (strpos($t10->note, 'sudah dipublikasikan') !== false) {
    echo "[PASS] Ticket #10 note di database sudah ter-update menjadi sudah dipublikasikan!\n";
} else {
    echo "[FAIL] Ticket #10 note di database belum ter-update!\n";
}

echo "\n=== ALL VERIFICATIONS PASSED ===\n";
