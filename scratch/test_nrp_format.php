<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sub = \App\Models\QuestionSubmission::find(13);
echo "Ticket #13 Student Email: " . $sub->student_email . "\n";

if ($sub->student_email === 's161124025@student.ubaya.ac.id') {
    echo "[PASS] Format email student UBAYA dengan awalan 's' berhasil diverifikasi!\n";
} else {
    echo "[FAIL] Format email student salah: {$sub->student_email}\n";
}
