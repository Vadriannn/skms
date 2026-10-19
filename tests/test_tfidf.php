<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$service = new \App\Services\TfIdfService();

$testQueries = [
    'Prosedur cara mengajukan cuti kuliah bagaimana?',
    'Berapa biaya dan jadwal bayar SPP kuliah semester ganjil?',
    'Cara mengurus revisi KRS yang terlambat?',
    'Jadwal libur semester dan kalender akademik?',
];

echo "========================================================\n";
echo "PENGUJIAN DETEKSI DUPLIKASI TF-IDF + COSINE SIMILARITY\n";
echo "========================================================\n\n";

foreach ($testQueries as $query) {
    $result = $service->checkSimilarity($query);
    echo "Pertanyaan Baru: \"$query\"\n";
    echo "Skor Kemiripan : " . $result['percentage'] . "\n";
    echo "Status Alert   : " . ($result['is_duplicate'] ? "⚠️ DUPLIKAT TERDETEKSI" : "✅ AMAN (Data Baru)") . "\n";
    if ($result['similar_faq']) {
        echo "FAQ Pembanding : \"" . $result['similar_faq']->pertanyaan . "\"\n";
    }
    echo "--------------------------------------------------------\n";
}
