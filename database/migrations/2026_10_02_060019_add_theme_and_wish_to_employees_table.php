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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('theme')->nullable()->default('gold')->after('birth_date');
            $table->text('custom_wish')->nullable()->after('theme');
            $table->string('gender', 10)->nullable()->default('male')->after('custom_wish');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['theme', 'custom_wish', 'gender']);
        });
    }
};
