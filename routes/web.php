<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\KnowledgeGapController;
use App\Http\Controllers\QuestionSubmissionController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SkmsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidatorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('skms.beranda');
});

// ─── HALAMAN PUBLIK SKMS (Portal Mahasiswa & Beranda FAQ) ───────────────────
Route::get('/skms/masuk', [SkmsController::class, 'showLoginForm'])->name('skms.login');
Route::post('/skms/masuk', [SkmsController::class, 'processLogin'])->name('skms.login.post');
Route::post('/skms/keluar', [SkmsController::class, 'logoutStudent'])->name('skms.logout');

Route::middleware('skms.timeout')->group(function () {
    Route::get('/skms', [SkmsController::class, 'index'])->name('skms.beranda');
    Route::get('/skms/tanya', [SkmsController::class, 'showQuestionForm'])->name('skms.tanya');
    Route::post('/skms/tanya', [SkmsController::class, 'submitQuestion'])->name('skms.tanya.store');
    Route::get('/skms/faq/{faq}', [SkmsController::class, 'show'])->name('skms.faq.show');
    Route::get('/skms/faq/{faq}/download', [FaqController::class, 'downloadAttachment'])->name('skms.faq.download');
    Route::post('/skms/faq/{faq}/view', [SkmsController::class, 'incrementView'])->name('skms.faq.view');
    Route::post('/skms/faq/{faq}/helpful', [SkmsController::class, 'toggleHelpful'])->name('skms.faq.helpful');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data-entry', [DashboardController::class, 'dataEntry'])->name('dashboard.data_entry');

    Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
    Route::get('/faqs/submissions', [FaqController::class, 'submissions'])->name('faqs.submissions');
    Route::get('/faqs/create', [FaqController::class, 'create'])->name('faqs.create');
    Route::post('/faqs', [FaqController::class, 'store'])->name('faqs.store');
    Route::post('/faqs/check-similarity', [FaqController::class, 'checkSimilarity'])->name('faqs.check_similarity');
    Route::get('/faqs/history', [FaqController::class, 'history'])->name('faqs.history');
    Route::get('/faqs/{faq}/download', [FaqController::class, 'downloadAttachment'])->name('faqs.download');
    Route::get('/faqs/{faq}', [FaqController::class, 'show'])->name('faqs.show');
    Route::get('/faqs/{faq}/edit', [FaqController::class, 'edit'])->name('faqs.edit');
    Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
    Route::patch('/faqs/{faq}/unpublish', [FaqController::class, 'unpublish'])->name('faqs.unpublish');
    Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');

    // ─── ANTREAN TIKET PENGAJUAN PERTANYAAN MAHASISWA (Data Entry & Super Admin) ─
    Route::get('/questions', [QuestionSubmissionController::class, 'index'])->name('questions.index');
    Route::get('/questions/search-faqs', [QuestionSubmissionController::class, 'searchFaqs'])->name('questions.search_faqs');
    Route::get('/questions/{questionSubmission}', [QuestionSubmissionController::class, 'show'])->name('questions.show');
    Route::post('/questions/{questionSubmission}/link-faq', [QuestionSubmissionController::class, 'linkFaq'])->name('questions.link_faq');

    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update');

    // ─── KNOWLEDGE GAP ANALYTICS (Super Admin & Data Entry) ──────────────────────
    Route::middleware('knowledge_gap.access')->group(function () {
        Route::get('/analytics/knowledge-gap', [KnowledgeGapController::class, 'index'])->name('analytics.knowledge_gap');
        Route::get('/analytics/knowledge-gap/export', [KnowledgeGapController::class, 'export'])->name('analytics.knowledge_gap.export');
    });

    // ─── VALIDATOR ROUTES ───────────────────────────────────────────────────────
    Route::middleware('validator.only')->group(function () {
        Route::get('/validator/dashboard', [ValidatorController::class, 'dashboard'])->name('validator.dashboard');
        Route::get('/validator/antrian', [ValidatorController::class, 'antrian'])->name('validator.antrian');
        Route::get('/validator/disetujui', [ValidatorController::class, 'disetujui'])->name('validator.disetujui');
        Route::get('/validator/semua-faq', [ValidatorController::class, 'semuaFaq'])->name('validator.semua_faq');
        Route::get('/validator/faq/{faq}', [ValidatorController::class, 'show'])->name('validator.faq.show');
        Route::post('/validator/faq/{faq}/approve', [ValidatorController::class, 'approve'])->name('validator.faq.approve');
        Route::post('/validator/faq/{faq}/revision', [ValidatorController::class, 'revision'])->name('validator.faq.revision');
        Route::post('/validator/faq/{faq}/reject', [ValidatorController::class, 'reject'])->name('validator.faq.reject');
    });

    // ─── TES PENGIRIMAN EMAIL SMTP GMAIL ─────────────────────────────────────────
    Route::get('/test-email', function (\Illuminate\Http\Request $request) {
        $recipient = $request->query('to') ?: config('mail.from.address');

        if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'status'  => 'error',
                'message' => "Parameter email tujuan [{$recipient}] tidak valid. Contoh penggunaan: /test-email?to=alamat@gmail.com",
            ], 422);
        }

        $username = config('mail.mailers.smtp.username');
        $password = config('mail.mailers.smtp.password');

        if (empty($username) || $username === 'your_email@gmail.com' || empty($password) || $password === 'your_google_app_password') {
            return response()->json([
                'status'  => 'warning',
                'message' => 'MAIL_USERNAME atau MAIL_PASSWORD di file .env masih berupa placeholder. Silakan isi terlebih dahulu dengan email Gmail dan Google App Password Anda.',
            ], 400);
        }

        try {
            \Illuminate\Support\Facades\Mail::to($recipient)->send(new \App\Mail\TestSmtpMail($recipient));
            return response()->json([
                'status'    => 'success',
                'message'   => "Email uji coba berhasil dikirim ke: {$recipient}",
                'timestamp' => now()->toIso8601String(),
                'details'   => [
                    'host'      => config('mail.mailers.smtp.host'),
                    'port'      => config('mail.mailers.smtp.port'),
                    'from'      => config('mail.from.address'),
                    'recipient' => $recipient,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengirim email: ' . $e->getMessage(),
            ], 500);
        }
    })->name('mail.test');

    // ─── PRATINJAU & TES EMAIL NOTIFIKASI FAQ RESMI ──────────────────────────────
    Route::get('/test-faq-email', function (\Illuminate\Http\Request $request) {
        $submission = \App\Models\QuestionSubmission::latest()->first() ?? new \App\Models\QuestionSubmission([
            'id' => 1,
            'nrp' => '161124011',
            'pertanyaan' => 'Bagaimana prosedur pengajuan beasiswa prestasi dan bantuan biaya kuliah semester ini?',
            'status' => 'answered',
            'note' => 'Pertanyaan telah dijawab melalui panduan resmi SKMS.',
        ]);

        $faq = \App\Models\Faq::where('status', 'published')->latest()->first() ?? new \App\Models\Faq([
            'id' => 1,
            'pertanyaan' => 'Panduan & Prosedur Beasiswa Prestasi UBAYA',
            'jawaban' => "Pengajuan beasiswa prestasi dibuka setiap awal semester melalui Direktorat Kemahasiswaan UBAYA.\nSilakan lengkapi berkas transkrip nilai, sertifikat prestasi, dan surat rekomendasi fakultas.",
            'status' => 'published',
        ]);

        $scenario = $request->query('scenario', 'new_faq');
        $to = $request->query('to');

        // Jika ada query ?to=email@gmail.com, kirim email sungguhan via SMTP Gmail
        if ($to && filter_var($to, FILTER_VALIDATE_EMAIL)) {
            try {
                \Illuminate\Support\Facades\Mail::to($to)->send(new \App\Mail\FaqAnsweredMail($submission, $faq, $scenario));
                return response()->json([
                    'status'    => 'success',
                    'message'   => "Email template resmi FaqAnsweredMail [{$scenario}] berhasil dikirim ke: {$to}!",
                    'timestamp' => now()->toIso8601String(),
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Gagal mengirim email: ' . $e->getMessage(),
                ], 500);
            }
        }

        // Tampilkan preview HTML di browser langsung jika tidak ada parameter ?to
        return (new \App\Mail\FaqAnsweredMail($submission, $faq, $scenario))->render();
    })->name('mail.test-faq');
});
