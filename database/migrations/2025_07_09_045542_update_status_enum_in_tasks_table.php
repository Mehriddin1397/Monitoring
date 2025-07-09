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
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE tasks MODIFY status ENUM('yangi', 'bajarilmoqda', 'bajarildi', 'uzaytirildi') DEFAULT 'yangi'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE tasks MODIFY status ENUM('yangi', 'bajarilmoqda', 'bajarildi') DEFAULT 'yangi'");
    }
};
