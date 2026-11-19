<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('question_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('nrp', 9)->index();
            $table->string('pertanyaan', 500);
            $table->enum('status', ['pending', 'answered'])->default('pending')->index();
            $table->text('note')->nullable();
            $table->foreignId('faq_id')->nullable()->constrained('faqs')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_submissions');
    }
};
