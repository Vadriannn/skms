<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Perluas enum status pada tabel faqs untuk menyertakan 'inactive'
        DB::statement("ALTER TABLE `faqs` MODIFY COLUMN `status` ENUM('draft', 'pending', 'published', 'rejected', 'inactive') NOT NULL DEFAULT 'draft'");

        // 2. Tambahkan kolom jejak penonaktifan (kapan dan oleh siapa)
        Schema::table('faqs', function (Blueprint $table) {
            if (!Schema::hasColumn('faqs', 'deactivated_at')) {
                $table->timestamp('deactivated_at')->nullable()->after('views');
            }
            if (!Schema::hasColumn('faqs', 'deactivated_by')) {
                $table->foreignId('deactivated_by')->nullable()->after('deactivated_at')->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            if (Schema::hasColumn('faqs', 'deactivated_by')) {
                $table->dropForeign(['deactivated_by']);
                $table->dropColumn('deactivated_by');
            }
            if (Schema::hasColumn('faqs', 'deactivated_at')) {
                $table->dropColumn('deactivated_at');
            }
        });

        DB::statement("UPDATE `faqs` SET `status` = 'draft' WHERE `status` = 'inactive'");
        DB::statement("ALTER TABLE `faqs` MODIFY COLUMN `status` ENUM('draft', 'pending', 'published', 'rejected') NOT NULL DEFAULT 'draft'");
    }
};

