<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Services\TurniketService;
use App\Models\Employee;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'turniket_id')) {
                $table->string('turniket_id')->nullable()->after('photo')->index();
            }
            if (!Schema::hasColumn('employees', 'turniket_photo')) {
                $table->string('turniket_photo', 500)->nullable()->after('turniket_id');
            }
        });

        // Automatically sync Turniket photos and IDs where names match
        try {
            $turniketService = new TurniketService();
            $allTurniket = [];
            for ($page = 1; $page <= 5; $page++) {
                $res = $turniketService->getEmployees(['page' => $page, 'limit' => 50]);
                if (!empty($res['data'])) {
                    $allTurniket = array_merge($allTurniket, $res['data']);
                } else {
                    break;
                }
            }

            if (!empty($allTurniket)) {
                $employees = Employee::with('user')->get();
                foreach ($employees as $emp) {
                    $uName = mb_strtolower(trim($emp->full_name ?: ($emp->user->name ?? '')));
                    // Normalize: remove dots, spaces, titles
                    $cleanUName = preg_replace('/[^\p{L}\p{N}]/u', '', $uName);

                    foreach ($allTurniket as $t) {
                        $tName = mb_strtolower(trim($t['fullname'] ?? ''));
                        $cleanTName = preg_replace('/[^\p{L}\p{N}]/u', '', $tName);

                        $isMatch = false;
                        if ($cleanUName === $cleanTName) {
                            $isMatch = true;
                        } elseif (mb_strlen($cleanUName) >= 5 && mb_strlen($cleanTName) >= 5) {
                            if (str_contains($cleanUName, $cleanTName) || str_contains($cleanTName, $cleanUName)) {
                                $isMatch = true;
                            }
                        }

                        if ($isMatch) {
                            $emp->turniket_id = (string) $t['employee_no'];
                            $emp->turniket_photo = $t['photo_url'] ?? null;
                            $emp->save();
                            break;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Non-blocking if API is temporarily unavailable
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'turniket_photo')) {
                $table->dropColumn('turniket_photo');
            }
            if (Schema::hasColumn('employees', 'turniket_id')) {
                $table->dropColumn('turniket_id');
            }
        });
    }
};
