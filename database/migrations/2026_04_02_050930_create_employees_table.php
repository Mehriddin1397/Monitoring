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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('full_name'); // To'liq ism
            $table->string('photo')->nullable(); // Rasm yo'li
            $table->string('phone'); // Telefon raqami
            $table->string('position'); // Lavozimi
            $table->date('birth_date'); // Tug'ilgan sanasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
