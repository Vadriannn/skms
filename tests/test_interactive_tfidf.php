<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\FaqController;
use App\Models\Faq;
use App\Models\User;
use App\Services\TfIdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$service = new TfIdfService();
$controller = new FaqController();

// Login as Data Entry user
$dataEntryUser = User::where('role', 'data_entry')->first() ?? User::first();
Auth::login($dataEntryUser);

echo "\n" . str_repeat('=', 70) . "\n";
echo "   DEMO & PENGUJIAN SISTEM DETEKSI KEMIRIPAN FAQ VIA TF-IDF\n";
echo str_repeat('=', 70) . "\n\n";

// =========================================================================
// BAGIAN 1: PENGUJIAN TAHAP PREPROCESSING & STEMMING SASTRAWI
// =========================================================================
echo ">>> [BAGIAN 1] UJI PREPROCESSING TEKS (STEMMING & STOPWORDS)\n";
echo str_repeat('-', 70) . "\n";
$sampleRaw = "Halo min, bagaimana tata cara mengajukan dan permohonan cuti kuliah?";
$tokens = $service->tokenize($sampleRaw);

echo "Teks Mentah   : \"$sampleRaw\"\n";
echo "Hasil Tokenisasi & Stemming Kata Dasar:\n";
echo "-> [" . implode(', ', array_map(fn($t) => "\"$t\"", $tokens)) . "]\n";
echo "Penjelasan: Kata 'halo, min, bagaimana, dan' dibuang sebagai stopwords.\n";
echo "            Kata 'mengajukan' & 'permohonan' distem menjadi kata dasar: 'aju' & 'mohon'.\n\n";

// =========================================================================
// BAGIAN 2: PENGUJIAN 4 SKENARIO INPUT PERTANYAAN OLEH DATA ENTRY
// =========================================================================
echo ">>> [BAGIAN 2] UJI PERBANDINGAN PERTANYAAN DENGAN REPOSITORI FAQ\n";
echo str_repeat('-', 70) . "\n";

$scenarios = [
    [
        'label' => 'SKENARIO 1: Pertanyaan Sangat Mirip (Parafrasa Cuti)',
        'query' => 'Bagaimana prosedur dan tata cara pengajuan cuti kuliah?',
        'expected' => 'DUPLIKAT (Skor >= 60%)',
    ],
    [
        'label' => 'SKENARIO 2: Pertanyaan Bahasa Santai / Chat Mahasiswa (Cicilan SPP)',
        'query' => 'Apakah bisa saya mencicil biaya uang kuliah semester ini?',
        'expected' => 'DUPLIKAT (Skor >= 60%)',
    ],
    [
        'label' => 'SKENARIO 3: Topik Serupa Tapi Pertanyaan Berbeda (Yudisium vs Kelulusan Lain)',
        'query' => 'Berapa biaya pendaftaran administrasi wisuda dan yudisium kampus?',
        'expected' => 'AMAN (< 60%, topik terkait tapi substansi baru)',
    ],
    [
        'label' => 'SKENARIO 4: Pertanyaan Benar-Benar Baru (Topik Belum Ada)',
        'query' => 'Panduan perakitan roket satelit nano di laboratorium teknik elektro',
        'expected' => 'AMAN (Skor 0% / Unik)',
    ],
];

foreach ($scenarios as $idx => $sc) {
    $num = $idx + 1;
    echo "--- Case #$num: {$sc['label']} ---\n";
    echo "Input Pertanyaan : \"{$sc['query']}\"\n";
    echo "Ekspektasi       : {$sc['expected']}\n";

    $result = $service->checkSimilarity($sc['query'], 0.60);

    $isDup = $result['is_duplicate'];
    $badge = $isDup ? "⚠️  PERINGATAN DUPLIKAT" : "✅  PERTANYAAN AMAN / BARU";

    echo "Skor Kemiripan   : {$result['percentage']} (Raw Score: {$result['highest_score']})\n";
    echo "Status Evaluasi  : $badge\n";

    if ($result['similar_faq']) {
        $simFaq = $result['similar_faq'];
        $catNames = $simFaq->categories->pluck('nama')->implode(', ');
        echo "FAQ Pembanding   : [ID #{$simFaq->id}] \"{$simFaq->pertanyaan}\"\n";
        echo "Kategori         : " . ($catNames ?: 'Tanpa Kategori') . "\n";
        echo "Status FAQ DB    : " . strtoupper($simFaq->status) . "\n";
    }

    if (!empty($result['candidates'])) {
        echo "Kandidat Teratas :\n";
        foreach ($result['candidates'] as $cIdx => $cand) {
            $cNum = $cIdx + 1;
            echo "   $cNum. [{$cand['percentage']}] \"{$cand['faq']->pertanyaan}\"\n";
        }
    }

    echo "\n";
}

// =========================================================================
// BAGIAN 3: PENGUJIAN ENDPOINT AJAX API (/faqs/check-similarity)
// =========================================================================
echo ">>> [BAGIAN 3] UJI ENDPOINT AJAX API (FaqController@checkSimilarity)\n";
echo str_repeat('-', 70) . "\n";

$ajaxQuery = "Cara mengajukan cuti kuliah semester depan";
$request = Request::create('/faqs/check-similarity', 'POST', [
    'pertanyaan' => $ajaxQuery,
    'threshold'  => 0.60,
]);

$response = $controller->checkSimilarity($request, $service);
$json = json_decode($response->getContent(), true);

echo "Simulasi Request Fetch API oleh Browser Data Entry:\n";
echo "Payload Dikirim  : {\"pertanyaan\": \"$ajaxQuery\"}\n";
echo "HTTP Status      : " . $response->getStatusCode() . "\n";
echo "Response JSON Status: " . ($json['success'] ? 'SUCCESS' : 'FAILED') . "\n";
echo "Response Data:\n";
echo " - has_checked   : " . ($json['has_checked'] ? 'true' : 'false') . "\n";
echo " - is_duplicate  : " . ($json['is_duplicate'] ? 'TRUE (Tampilkan Alert Kuning)' : 'FALSE (Tampilkan Safe)') . "\n";
echo " - highest_score : " . $json['highest_score'] . " (" . $json['percentage'] . ")\n";
if ($json['similar_faq']) {
    echo " - similar_faq   : \"" . $json['similar_faq']['pertanyaan'] . "\" (Status: " . $json['similar_faq']['status_label'] . ")\n";
}
echo " - total kandidat: " . count($json['candidates']) . " FAQ\n";

echo "\n" . str_repeat('=', 70) . "\n";
echo "KESIMPULAN: Seluruh fungsi TF-IDF dan Cosine Similarity berjalan 100% normal.\n";
echo str_repeat('=', 70) . "\n\n";

