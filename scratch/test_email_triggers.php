<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Mail\FaqAnsweredMail;
use App\Models\Faq;
use App\Models\QuestionSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ValidatorController;
use App\Http\Controllers\QuestionSubmissionController;

echo "=== START VERIFICATION TEST FOR EMAIL NOTIFICATIONS & NOTE REMOVAL ===\n";

// -------------------------------------------------------------
// CHECK 0: Form View checks (Catatan balasan dihapus)
// -------------------------------------------------------------
$faqCreateView = file_get_contents(resource_path('views/faqs/create.blade.php'));
if (strpos($faqCreateView, 'ticket_note') === false) {
    echo "[PASS] ticket_note berhasil dihapus dari resources/views/faqs/create.blade.php\n";
} else {
    echo "[FAIL] ticket_note masih ada di resources/views/faqs/create.blade.php\n";
}

$questionShowView = file_get_contents(resource_path('views/questions/show.blade.php'));
if (strpos($questionShowView, 'ticketNoteInput') === false) {
    echo "[PASS] ticketNoteInput berhasil dihapus dari resources/views/questions/show.blade.php\n";
} else {
    echo "[FAIL] ticketNoteInput masih ada di resources/views/questions/show.blade.php\n";
}

// Set user auth
$validatorUser = User::where('role', 'validator')->first() ?? User::first();
Auth::login($validatorUser);

Mail::fake();

// -------------------------------------------------------------
// TEST 1: Skenario 1 - FAQ Baru Disetujui Validator
// -------------------------------------------------------------
echo "\n--- Test 1: Skenario 1 (FAQ Baru Disetujui Validator) ---\n";

$sub1 = QuestionSubmission::create([
    'nrp' => '161124011',
    'pertanyaan' => 'Bagaimana cara mengajukan beasiswa prestasi semester ini?',
    'status' => 'pending',
]);

$faq1 = Faq::create([
    'user_id' => $validatorUser->id,
    'pertanyaan' => 'Bagaimana prosedur dan syarat beasiswa prestasi?',
    'jawaban' => 'Silakan unduh formulir dan serahkan ke bagian kemahasiswaan sebelum tanggal 30.',
    'status' => 'pending',
]);

$sub1->update(['faq_id' => $faq1->id]);

$validatorController = new ValidatorController();
$request = new Request(['catatan' => 'Informasi sudah lengkap dan jelas.']);

$validatorController->approve($request, $faq1);

$sub1->refresh();
$faq1->refresh();

if ($faq1->status === 'published') {
    echo "[PASS] Faq 1 status changed to 'published'\n";
} else {
    echo "[FAIL] Faq 1 status is {$faq1->status}\n";
}

if ($sub1->status === 'answered') {
    echo "[PASS] Submission 1 status changed to 'answered'\n";
} else {
    echo "[FAIL] Submission 1 status is {$sub1->status}\n";
}

Mail::assertSent(FaqAnsweredMail::class, function ($mail) use ($sub1, $faq1) {
    return $mail->hasTo('s161124011@student.ubaya.ac.id') &&
           $mail->scenario === 'new_faq' &&
           $mail->submission->id === $sub1->id &&
           $mail->faq->id === $faq1->id;
});
echo "[PASS] Mail::assertSent passed for Skenario 1 to s161124011@student.ubaya.ac.id\n";


// -------------------------------------------------------------
// TEST 2: Skenario 2 - Menautkan FAQ yang Sudah Ada
// -------------------------------------------------------------
echo "\n--- Test 2: Skenario 2 (Tautkan FAQ yang Sudah Ada) ---\n";

$faq2 = Faq::create([
    'user_id' => $validatorUser->id,
    'pertanyaan' => 'Panduan pengajuan cuti kuliah mahasiswa',
    'jawaban' => 'Pengajuan cuti dapat dilakukan melalui SIM Ubaya.',
    'status' => 'published',
]);

$sub2 = QuestionSubmission::create([
    'nrp' => '161124022',
    'pertanyaan' => 'Gimana alur cuti semester ganjil?',
    'status' => 'pending',
]);

$dataEntryUser = User::where('role', 'data_entry')->first() ?? $validatorUser;
Auth::login($dataEntryUser);

$submissionController = new QuestionSubmissionController();
$linkRequest = new Request([
    'faq_id' => $faq2->id,
]);

$submissionController->linkFaq($linkRequest, $sub2);

$sub2->refresh();

if ($sub2->status === 'answered' && $sub2->faq_id === $faq2->id) {
    echo "[PASS] Submission 2 status 'answered' and linked to FAQ #{$faq2->id}\n";
} else {
    echo "[FAIL] Submission 2 status: {$sub2->status}, faq_id: {$sub2->faq_id}\n";
}

Mail::assertSent(FaqAnsweredMail::class, function ($mail) use ($sub2, $faq2) {
    return $mail->hasTo('s161124022@student.ubaya.ac.id') &&
           $mail->scenario === 'existing_faq' &&
           $mail->submission->id === $sub2->id &&
           $mail->faq->id === $faq2->id;
});
echo "[PASS] Mail::assertSent passed for Skenario 2 to s161124022@student.ubaya.ac.id\n";


// -------------------------------------------------------------
// TEST 3: Blade Email Template Rendering Test
// -------------------------------------------------------------
echo "\n--- Test 3: Blade Email Template Rendering & Link published FAQ ---\n";

$mailableNew = new FaqAnsweredMail($sub1, $faq1, 'new_faq');
$htmlNew = $mailableNew->render();

$publishedLink1 = route('skms.faq.show', $faq1->id);

if (str_contains($htmlNew, 'Catatan Tambahan dari Staf')) {
    echo "[FAIL] Email masih memuat Catatan Tambahan dari Staf!\n";
} else {
    echo "[PASS] Email tidak memuat catatan balasan!\n";
}

if (str_contains($htmlNew, $publishedLink1)) {
    echo "[PASS] Email memuat tautan langsung ke FAQ yang dipublikasikan: {$publishedLink1}\n";
} else {
    echo "[FAIL] Email tidak memuat link FAQ dipublikasikan {$publishedLink1}\n";
}

// Cleanup test records
$sub1->delete();
$sub2->delete();
$faq1->delete();
$faq2->delete();

echo "\n=== ALL VERIFICATIONS PASSED SUCCESSFULLY! ===\n";
