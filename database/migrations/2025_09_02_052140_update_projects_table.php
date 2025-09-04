<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // name ustunini stringdan textga o‘zgartirish
            $table->text('name')->change();
            $table->text('tel_number')->change();

            // yangi ustunlar qo‘shamiz
            $table->text('izoh')->nullable()->after('job');
            $table->text('manba')->nullable()->after('izoh');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('tel_number')->change();
            $table->dropColumn(['izoh', 'manba']);
        });
    }
};
