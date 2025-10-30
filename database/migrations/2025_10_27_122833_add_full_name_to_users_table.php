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
        Schema::table('users', function (Blueprint $table) {

            // ilmiy xodim belgisi
            if (!Schema::hasColumn('users', 'is_scientific')) {
                $table->boolean('is_scientific')->default(false);
            }
            // yangi ustunlar qo‘shish
            if (!Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name')->nullable()->after('name');
            }

            if (!Schema::hasColumn('users', 'position')) {
                $table->string('position')->nullable()->after('full_name');
            }

            if (!Schema::hasColumn('users', 'degree')) {
                $table->string('degree')->nullable()->after('position');
            }
            if (!Schema::hasColumn('users', 'tel_number')) {
                $table->string('tel_number')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_scientific')) {
                $table->dropColumn('is_scientific');
            }
            // orqaga qaytarishda yangi ustunlarni o‘chiramiz
            if (Schema::hasColumn('users', 'degree')) {
                $table->dropColumn('degree');
            }

            if (Schema::hasColumn('users', 'position')) {
                $table->dropColumn('position');
            }

            if (Schema::hasColumn('users', 'full_name')) {
                $table->dropColumn('full_name');
            }
        });
    }
};
