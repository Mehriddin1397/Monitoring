<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class EmployeeSyncService
{
    protected TurniketService $turniketService;

    public function __construct(TurniketService $turniketService)
    {
        $this->turniketService = $turniketService;
    }

    /**
     * Clean and normalize a string for fuzzy matching:
     * Lowercases, strips punctuation, normalizes Cyrillic letters (e.g. ў/o', ғ/g', қ/q, ҳ/h)
     */
    public static function normalizeName(string $name): string
    {
        $name = mb_strtolower(trim($name));
        // Remove titles like podpolkovnik, mayor, kapitan, dots, dashes
        $name = preg_replace('/\b(podpolkovnik|mayor|kapitan|leytenant|katta leytenant|polkovnik)\b/u', '', $name);
        $name = preg_replace('/[^\p{L}\p{N}]/u', '', $name);
        return trim($name);
    }

    /**
     * Sync Turniket IDs and photos to local employees table
     */
    public function syncTurniketPhotos(): array
    {
        $allTurniket = [];
        for ($page = 1; $page <= 5; $page++) {
            $res = $this->turniketService->getEmployees(['page' => $page, 'limit' => 50]);
            if (!empty($res['data'])) {
                $allTurniket = array_merge($allTurniket, $res['data']);
            } else {
                break;
            }
        }

        if (empty($allTurniket)) {
            return [
                'success' => false,
                'message' => 'Турникет серверидан ходимлар рўйхатини олиб бўлмади.',
                'matched' => 0,
            ];
        }

        $employees = Employee::with('user')->get();
        $matched = 0;

        foreach ($employees as $emp) {
            $namesToTry = [];

            if (!empty($emp->full_name)) {
                $namesToTry[] = self::normalizeName($emp->full_name);
            }
            if ($emp->user && !empty($emp->user->name)) {
                $namesToTry[] = self::normalizeName($emp->user->name);
            }
            if ($emp->user && !empty($emp->user->full_name)) {
                $namesToTry[] = self::normalizeName($emp->user->full_name);
            }

            $foundTurniket = null;

            foreach ($allTurniket as $t) {
                $tNorm = self::normalizeName($t['fullname'] ?? '');
                if (empty($tNorm)) continue;

                foreach ($namesToTry as $uNorm) {
                    if (empty($uNorm)) continue;

                    // Exact match
                    if ($uNorm === $tNorm) {
                        $foundTurniket = $t;
                        break 2;
                    }

                    // Substring match (e.g. full name contains surname and initials)
                    if (mb_strlen($uNorm) >= 4 && mb_strlen($tNorm) >= 4) {
                        // Check if first 5 chars match (surname)
                        $uPrefix = mb_substr($uNorm, 0, 5);
                        $tPrefix = mb_substr($tNorm, 0, 5);
                        if ($uPrefix === $tPrefix) {
                            $foundTurniket = $t;
                            break 2;
                        }
                    }
                }
            }

            if ($foundTurniket) {
                $emp->turniket_id = (string) $foundTurniket['employee_no'];
                $emp->turniket_photo = $foundTurniket['photo_url'] ?? null;
                $emp->save();
                $matched++;
            }
        }

        return [
            'success' => true,
            'message' => "Турникетдан {$matched} та ходимнинг расми ва ID рақами муваффақиятли боғланди.",
            'matched' => $matched,
            'total'   => count($employees),
        ];
    }
}
