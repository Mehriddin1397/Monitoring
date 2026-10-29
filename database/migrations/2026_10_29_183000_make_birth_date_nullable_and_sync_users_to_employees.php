<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Make birth_date, phone, position nullable
        DB::statement("ALTER TABLE employees MODIFY COLUMN birth_date DATE NULL DEFAULT NULL");
        DB::statement("ALTER TABLE employees MODIFY COLUMN phone VARCHAR(255) NULL DEFAULT NULL");
        DB::statement("ALTER TABLE employees MODIFY COLUMN position VARCHAR(255) NULL DEFAULT NULL");

        // 2. Add user_id column if not exists
        if (!Schema::hasColumn('employees', 'user_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            });
        }

        // 3. Sync all users into employees table
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $fullName = trim($user->full_name ?: $user->name);
            if (empty($fullName)) {
                continue;
            }

            // Check if already in employees
            $exists = DB::table('employees')
                ->where('user_id', $user->id)
                ->orWhere('full_name', $fullName)
                ->first();

            if ($exists) {
                if (empty($exists->user_id)) {
                    DB::table('employees')->where('id', $exists->id)->update(['user_id' => $user->id]);
                }
                continue;
            }

            // Detect gender
            $lowerName = mb_strtolower($fullName);
            $gender = 'male';
            if (
                str_contains($lowerName, 'қизи') || str_contains($lowerName, 'qizi') ||
                str_contains($lowerName, 'овна') || str_contains($lowerName, 'ovna') ||
                str_contains($lowerName, 'евна') || str_contains($lowerName, 'evna') ||
                str_contains($lowerName, 'кизи')
            ) {
                $gender = 'female';
            }

            DB::table('employees')->insert([
                'user_id' => $user->id,
                'full_name' => $fullName,
                'phone' => $user->tel_number ?? '',
                'position' => $user->position ?? 'Ходим',
                'birth_date' => null,
                'photo' => null,
                'theme' => 'gold',
                'custom_wish' => null,
                'gender' => $gender,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('employees', 'user_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
};
