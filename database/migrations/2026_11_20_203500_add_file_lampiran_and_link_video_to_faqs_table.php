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
            $table->string('file_lampiran', 255)->nullable()->after('jawaban');
            $table->string('file_nama_asli', 255)->nullable()->after('file_lampiran');
            $table->string('link_video', 500)->nullable()->after('file_nama_asli');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['file_lampiran', 'file_nama_asli', 'link_video']);
        });
    }
};
