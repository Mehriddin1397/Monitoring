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
        Schema::create('ongoing_works', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->text('problems')->nullable();
            $table->text('process')->nullable();
            $table->string('process_color')->nullable(); // rang uchun
            $table->string('remaining_time')->nullable();
            $table->date('deadline')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ongoing_works');
    }
};
