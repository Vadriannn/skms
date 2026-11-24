<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tickets = \App\Models\QuestionSubmission::with('faq')->get();
$count = 0;
foreach ($tickets as $t) {
    if ($t->faq && $t->faq->status === 'published') {
        $t->update([
            'status' => 'answered',
            'note' => 'Pertanyaan Anda telah resmi dijawab dan sudah dipublikasikan ke dalam repositori FAQ.',
        ]);
        $count++;
    }
}

echo "Successfully updated {$count} tickets linked to published FAQs.\n";
