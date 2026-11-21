<?php

$ch = curl_init('http://localhost/skms/faq/6');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$res = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "Final HTTP Code: " . $httpCode . "\n";
echo "Final URL: " . $effectiveUrl . "\n";
if (strpos($res, 'bagaimana mengajukan izin kuliah') !== false) {
    echo "[PASS] Berhasil memuat halaman detail FAQ #6 dengan sempurna!\n";
} else {
    echo "[FAIL] Konten FAQ #6 tidak ditemukan!\n";
}
