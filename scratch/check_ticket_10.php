<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$t = \App\Models\QuestionSubmission::with('faq')->find(10);
if ($t) {
    echo "Ticket 10:\n";
    echo "  Status: " . $t->status . "\n";
    echo "  Note: " . $t->note . "\n";
    echo "  FAQ ID: " . $t->faq_id . "\n";
    echo "  FAQ Status: " . ($t->faq ? $t->faq->status : 'none') . "\n";
    echo "  FAQ Question: " . ($t->faq ? $t->faq->pertanyaan : 'none') . "\n";
} else {
    echo "Ticket 10 not found!\n";
}
