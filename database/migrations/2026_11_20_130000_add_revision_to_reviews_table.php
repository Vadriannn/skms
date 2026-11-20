<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddRevisionToReviewsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE reviews MODIFY COLUMN keputusan ENUM('approved', 'rejected', 'revision') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE reviews MODIFY COLUMN keputusan ENUM('approved', 'rejected') NOT NULL");
    }
}
