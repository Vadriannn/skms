<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\FaqController;
use App\Models\User;
use App\Models\Faq;
use App\Models\DuplicateLog;
use App\Services\TfIdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$user = User::first();
Auth::login($user);

$controller = new FaqController();
$service = new TfIdfService();

echo "--- TEST 1: checkSimilarity Endpoint (Duplicate Query) ---\n";
$req1 = Request::create('/faqs/check-similarity', 'POST', [
    'pertanyaan' => 'Bagaimana prosedur dan cara pengajuan cuti kuliah?'
]);
$res1 = $controller->checkSimilarity($req1, $service);
$data1 = json_decode($res1->getContent(), true);

echo "Status: " . ($data1['success'] ? 'OK' : 'FAIL') . "\n";
echo "Is Duplicate: " . ($data1['is_duplicate'] ? 'YES' : 'NO') . "\n";
echo "Highest Score: " . $data1['percentage'] . "\n";
if ($data1['similar_faq']) {
    echo "Similar FAQ: " . $data1['similar_faq']['pertanyaan'] . "\n";
}
echo "Candidates found: " . count($data1['candidates']) . "\n\n";

echo "--- TEST 2: checkSimilarity Endpoint (Unique Query) ---\n";
$req2 = Request::create('/faqs/check-similarity', 'POST', [
    'pertanyaan' => 'Tata cara pembuatan robotika bawah air di lab elektro'
]);
$res2 = $controller->checkSimilarity($req2, $service);
$data2 = json_decode($res2->getContent(), true);

echo "Status: " . ($data2['success'] ? 'OK' : 'FAIL') . "\n";
echo "Is Duplicate: " . ($data2['is_duplicate'] ? 'YES' : 'NO') . "\n";
echo "Highest Score: " . $data2['percentage'] . "\n\n";

echo "--- TEST 3: FaqController@store with Duplicate Tracking (Threshold >= 60%) ---\n";
$initialLogsCount = DuplicateLog::count();
$cat = \App\Models\Category::first();

// 3A. Test Simpan Pertanyaan Duplikat (Skor >= 60%) -> Harus Masuk DuplicateLog
$req3A = Request::create('/faqs', 'POST', [
    'pertanyaan' => 'Bagaimana prosedur dan cara pengajuan cuti kuliah?',
    'jawaban' => 'Langkah pengajuan cuti kuliah dilakukan dengan mengisi form cuti.',
    'category_ids' => [$cat->id],
    'action_type' => 'draft'
]);

$res3A = $controller->store($req3A, $service);
$afterDupCount = DuplicateLog::count();
echo "Simpan Duplikat (>= 60%):\n";
echo " - Log Awal: $initialLogsCount, Log Baru: $afterDupCount\n";
if ($afterDupCount > $initialLogsCount) {
    $latestLog = DuplicateLog::latest('id')->first();
    echo " - [OK] Berhasil tercatat di duplicate_logs! FAQ #{$latestLog->faq_id} mirip dengan #{$latestLog->similar_faq_id} (Skor: {$latestLog->score})\n";
    $testFaq = Faq::find($latestLog->faq_id);
    if ($testFaq) $testFaq->delete();
}

// 3B. Test Simpan Pertanyaan Unik/Aman (Skor < 60%) -> TIDAK Boleh Masuk DuplicateLog
$beforeUniqueCount = DuplicateLog::count();
$req3B = Request::create('/faqs', 'POST', [
    'pertanyaan' => 'Panduan riset fabrikasi semikonduktor canggih di lab mikroelektronika',
    'jawaban' => 'Prosedur riset laboratorium semikonduktor...',
    'category_ids' => [$cat->id],
    'action_type' => 'draft'
]);

$res3B = $controller->store($req3B, $service);
$afterUniqueCount = DuplicateLog::count();
echo "Simpan Pertanyaan Unik (< 60%):\n";
echo " - Log Sebelum: $beforeUniqueCount, Log Sesudah: $afterUniqueCount\n";
echo " - Status: " . ($afterUniqueCount === $beforeUniqueCount ? "[OK] Benar! Tidak masuk duplicate_logs karena skor < 60%" : "[FAIL] Masuk log padahal bukan duplikat") . "\n";

// Clean up
$uniqueFaq = Faq::where('pertanyaan', 'like', '%semikonduktor%')->first();
if ($uniqueFaq) $uniqueFaq->delete();
