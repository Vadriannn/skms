<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ticket = \App\Models\QuestionSubmission::find(13);
if ($ticket) {
    echo "Ticket #13:\n";
    echo "  Status: " . $ticket->status . "\n";
    echo "  NRP: " . $ticket->nrp . "\n";
    echo "  FAQ ID: " . $ticket->faq_id . "\n";
    echo "  Pertanyaan: " . $ticket->pertanyaan . "\n";
    if ($ticket->faq) {
        echo "FAQ #{$ticket->faq->id}:\n";
        echo "  Status: " . $ticket->faq->status . "\n";
        echo "  Pertanyaan: " . $ticket->faq->pertanyaan . "\n";
        echo "  Route URL: " . route('skms.faq.show', $ticket->faq->id) . "\n";
    } else {
        echo "  FAQ object is NULL!\n";
    }
} else {
    echo "Ticket #13 not found!\n";
    // List latest 5 tickets
    $latest = \App\Models\QuestionSubmission::latest()->take(5)->get();
    foreach ($latest as $l) {
        echo "Ticket #{$l->id} (status: {$l->status}, faq_id: {$l->faq_id}, q: {$l->pertanyaan})\n";
    }
}

echo "\nLatest published FAQs:\n";
foreach (\App\Models\Faq::latest()->take(5)->get() as $f) {
    echo "FAQ #{$f->id} [status: {$f->status}] q: {$f->pertanyaan}\n";
}
