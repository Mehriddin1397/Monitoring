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
        Schema::table('projects', function (Blueprint $table) {
            // Avval ustunni qo'shamiz
            $table->unsignedBigInteger('user_id')->after('id');

            // So'ng foreign key qo'shamiz
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Foreign key va ustunni o'chirish
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
