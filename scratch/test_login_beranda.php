<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

echo "Testing Student Login & Masuk View...\n";

// 1. Test View Render
$view = view('skms.masuk')->render();
if (strpos($view, 'Masuk ke Halaman Beranda') !== false) {
    echo "[PASS] Tombol submit berbunyi: 'Masuk ke Halaman Beranda'\n";
} else {
    echo "[FAIL] Teks tombol tidak sesuai!\n";
}

if (strpos($view, 'name="redirect"') === false) {
    echo "[PASS] Tidak ada input hidden redirect\n";
} else {
    echo "[FAIL] Input redirect masih ada!\n";
}

// 2. Test Controller processLogin
$request = \Illuminate\Http\Request::create('/skms/masuk', 'POST', [
    'nrp' => '161124025',
]);
$controller = new \App\Http\Controllers\SkmsController();
$redirectResponse = $controller->processLogin($request);

$targetUrl = $redirectResponse->getTargetUrl();
echo "Redirect Target: " . $targetUrl . "\n";

if (strpos($targetUrl, route('skms.beranda')) !== false) {
    echo "[PASS] Berhasil redirect langsung ke Halaman Beranda saat tekan Masuk NRP!\n";
} else {
    echo "[FAIL] Redirect tidak mengarah ke Beranda!\n";
}

if (session('student_nrp') === '161124025') {
    echo "[PASS] Session student_nrp tersimpan: " . session('student_nrp') . "\n";
} else {
    echo "[FAIL] Session student_nrp tidak tersimpan!\n";
}

echo "All checks complete.\n";
