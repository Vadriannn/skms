<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$faq6 = \App\Models\Faq::find(6);
if ($faq6) {
    $faq6->update(['status' => 'published']);
    echo "FAQ #6 status successfully updated to 'published'!\n";
} else {
    echo "FAQ #6 not found!\n";
}
