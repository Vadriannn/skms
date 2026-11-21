<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$faq6 = \App\Models\Faq::with('reviews')->find(6);
echo "FAQ #6:\n";
echo "Pertanyaan: " . $faq6->pertanyaan . "\n";
echo "Jawaban: " . $faq6->jawaban . "\n";
echo "Status: " . $faq6->status . "\n";
echo "Reviews:\n";
foreach ($faq6->reviews as $r) {
    echo "  - Keputusan: {$r->keputusan}, Catatan: {$r->catatan}\n";
}
