<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'boshliq', 'xodim', 'kadr', 'kotib') DEFAULT 'xodim'");

        // Salimov Sherzod - kadr
        DB::table('users')
            ->where('id', 10)
            ->orWhere('name', 'LIKE', '%Салимов%')
            ->orWhere('full_name', 'LIKE', '%Салимов Шерзод%')
            ->update(['role' => 'kadr']);

        // Nurumbetova Sadoqat - kotib
        DB::table('users')
            ->where('id', 59)
            ->orWhere('name', 'LIKE', '%Нурумбетова%')
            ->orWhere('full_name', 'LIKE', '%Нурумбетова Садокат%')
            ->update(['role' => 'kotib']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'boshliq', 'xodim') DEFAULT 'xodim'");
    }
};
