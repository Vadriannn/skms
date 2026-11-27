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
        Schema::table('faqs', function (Blueprint $table) {
            if (!Schema::hasColumn('faqs', 'not_helpful')) {
                $table->unsignedInteger('not_helpful')->default(0)->after('helpful');
            }
        });

        Schema::table('faq_helpful_votes', function (Blueprint $table) {
            if (!Schema::hasColumn('faq_helpful_votes', 'vote_type')) {
                $table->string('vote_type', 20)->default('helpful')->after('voter_hash');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            if (Schema::hasColumn('faqs', 'not_helpful')) {
                $table->dropColumn('not_helpful');
            }
        });

        Schema::table('faq_helpful_votes', function (Blueprint $table) {
            if (Schema::hasColumn('faq_helpful_votes', 'vote_type')) {
                $table->dropColumn('vote_type');
            }
        });
    }
};
