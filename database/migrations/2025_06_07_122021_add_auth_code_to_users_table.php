<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

        public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('auth_code', 4)->nullable(); // 4 xonali kod
            $table->timestamp('last_activity')->nullable(); // Oxirgi faollik
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
