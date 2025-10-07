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
        Schema::create('planned_works', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->text('required_expenses')->nullable();
            $table->text('preparation_time')->nullable();
            $table->text('performance_results')->nullable();
            $table->string('required_amount')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planned_works');
    }
};
