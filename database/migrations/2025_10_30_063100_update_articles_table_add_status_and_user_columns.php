<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Avval foydalanuvchi (id = 2) mavjudligini tekshiramiz, agar yo'q bo'lsa — yaratamiz
        $userExists = DB::table('users')->where('id', 2)->exists();

        if (!$userExists) {
            DB::table('users')->insert([
                'id' => 2,
                'name' => 'Default User',
                'email' => 'default_user@example.com',
                'password' => bcrypt('password'),
                'role' => 'author', // agar users jadvalida role bo‘lsa
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Maqolalarda user_id mavjud bo‘lmasa, vaqtincha 2-chi userga bog‘laymiz
        if (Schema::hasTable('articles')) {
            DB::statement('UPDATE articles SET user_id = 2 WHERE user_id IS NULL OR user_id = 0');
        }

        // 3. Endi ustunlarni xavfsiz tarzda qo‘shamiz
        Schema::table('articles', function (Blueprint $table) {

            if (!Schema::hasColumn('articles', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
            }

            if (!Schema::hasColumn('articles', 'checked_by')) {
                $table->foreignId('checked_by')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            }

            if (!Schema::hasColumn('articles', 'status')) {
                $table->enum('status', ['pending', 'checked'])->default('pending')->after('conclusion_pdf');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('articles', 'checked_by')) {
                $table->dropForeign(['checked_by']);
                $table->dropColumn('checked_by');
            }

            if (Schema::hasColumn('articles', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
