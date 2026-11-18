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
        Schema::create('faq_helpful_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_id')->constrained('faqs')->onDelete('cascade');
            $table->string('student_nrp', 20)->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('voter_hash', 64)->index();
            $table->timestamps();

            // Mencegah duplikasi: 1 voter_hash hanya bisa memberi suara 1 kali per FAQ
            $table->unique(['faq_id', 'voter_hash']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_helpful_votes');
    }
};
