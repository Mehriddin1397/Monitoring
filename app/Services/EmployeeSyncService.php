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
     * Convert Uzbek Cyrillic/Latin name to canonical Latin tokens for strict comparison
     */
    public static function toCanonicalTokens(string $name): array
    {
        $name = mb_strtolower(trim($name));

        // 1. Remove military and civil titles/ranks
        $ranks = [
            'podpolkovnik', 'polkovnik', 'mayor', 'kapitan', 'leytenant', 'katta leytenant', 'ppk.', 'ppk',
            'подполковник', 'полковник', 'майор', 'капитан', 'лейтенант', 'катта лейтенант', 'ппк.', 'ппк',
            'o‘g‘li', 'o\'g\'li', 'o’g’li', 'oʼgʼli', 'og\'li', 'o\'gli', 'o‘gli', 'ўғли', 'угли',
            'qizi', 'қизи', 'кизи', 'kizi'
        ];
        foreach ($ranks as $r) {
            $name = preg_replace('/\b' . preg_quote($r, '/') . '\b/u', ' ', $name);
        }

        // 2. Remove punctuation, apostrophes, hyphens, brackets
        $name = str_replace(['.', ',', '-', '_', '/', '\\', '(', ')', '"', "'", 'ʻ', '’', '`', '“', '”'], ' ', $name);

        // 3. Transliterate Uzbek Cyrillic to Latin
        $map = [
            'ш' => 'sh', 'ч' => 'ch', 'ё' => 'yo', 'ю' => 'yu', 'я' => 'ya',
            'ў' => 'o', 'ғ' => 'g', 'қ' => 'q', 'ҳ' => 'h', 'х' => 'x',
            'ц' => 'ts', 'ж' => 'j', 'щ' => 'sh',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p',
            'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f',
            'ъ' => '', 'ь' => '', 'э' => 'e'
        ];
        $name = strtr($name, $map);

        // 4. Clean extra spaces
        $name = preg_replace('/\s+/u', ' ', trim($name));
        if ($name === '') return [];

        // 5. Split into individual words
        $rawWords = explode(' ', $name);
        $tokens = [];
        foreach ($rawWords as $w) {
            $w = trim($w);
            if ($w === '') continue;
            // Normalize common Uzbek phonetic equivalents (h/x, q/k)
            $norm = str_replace(['kh', 'h'], 'x', $w);
            $norm = str_replace('q', 'k', $norm);
            $tokens[] = $norm;
        }

        return $tokens;
    }

    /**
     * Clean and normalize a string for fast hash lookup
     */
    public static function normalizeName(string $name): string
    {
        $tokens = self::toCanonicalTokens($name);
        return implode('', $tokens);
    }

    /**
     * Determine if a local Employee tokens match a Turniket Employee tokens with high precision
     */
    public static function isMatch(array $uTokens, array $tTokens): bool
    {
        if (empty($uTokens) || empty($tTokens)) {
            return false;
        }

        // Exact match of all tokens
        if ($uTokens === $tTokens) {
            return true;
        }

        $uCount = count($uTokens);
        $tCount = count($tTokens);

        // Case A: Standard Order: [Surname, Firstname, ...]
        if ($uCount >= 2 && $tCount >= 2) {
            $uSurname = $uTokens[0];
            $uFirstname = $uTokens[1];
            $tSurname = $tTokens[0];
            $tFirstname = $tTokens[1];

            // Match normal order: [Surname, Firstname] vs [Surname, Firstname]
            if (self::namesMatch($uSurname, $tSurname) && self::firstnamesMatch($uFirstname, $tFirstname)) {
                return true;
            }

            // Match reversed order: [Firstname, Surname] in Turniket e.g. "Asadbek Abdumanopov"
            if (self::namesMatch($uSurname, $tFirstname) && self::firstnamesMatch($uFirstname, $tSurname)) {
                return true;
            }
        }

        // Case B: Turniket has single token with initial, e.g. "Saydahmedov Q" -> [saydaxmedov, k]
        if ($uCount >= 2 && $tCount === 2) {
            if (self::namesMatch($uTokens[0], $tTokens[0])) {
                // If turniket second token is 1 char initial, check first letter of user's firstname
                if (mb_strlen($tTokens[1]) === 1 && mb_substr($uTokens[1], 0, 1) === $tTokens[1]) {
                    return true;
                }
            }
        }

        // Case C: User has 2 tokens and Turniket has 1 token (or vice versa), only match if exactly equal
        if ($uCount === 1 && $tCount === 1) {
            return $uTokens[0] === $tTokens[0];
        }

        return false;
    }

    /**
     * Compare surnames allowing for gender endings (-ov/-ova, -ev/-eva)
     */
    protected static function namesMatch(string $a, string $b): bool
    {
        if ($a === $b) return true;

        // Strip gender endings to match base stem: e.g. ubaydullaev vs ubaydullaeva
        $stemA = preg_replace('/(ev|eva|ov|ova|yev|yeva)$/u', '', $a);
        $stemB = preg_replace('/(ev|eva|ov|ova|yev|yeva)$/u', '', $b);

        if (mb_strlen($stemA) >= 4 && mb_strlen($stemB) >= 4 && $stemA === $stemB) {
            return true;
        }

        return false;
    }

    /**
     * Compare firstnames strictly (NEVER allow different firstnames like Nilufar vs Munira!)
     */
    protected static function firstnamesMatch(string $a, string $b): bool
    {
        if ($a === $b) return true;

        // One is initial (1 letter)
        if (mb_strlen($a) === 1 || mb_strlen($b) === 1) {
            return mb_substr($a, 0, 1) === mb_substr($b, 0, 1);
        }

        // Both are full words: require high similarity (e.g. Solijon vs Soli, Mehriddin vs Mextridin)
        if (mb_strlen($a) >= 4 && mb_strlen($b) >= 4) {
            // Levenshtein distance <= 2 for long names
            if (levenshtein($a, $b) <= 2) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sync Turniket IDs and photos to local employees table safely (Strict 1-to-1 Mapping)
     */
    public function syncTurniketPhotos(): array
    {
        // 1. Fetch all turniket employees across all pages
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

        // 2. Precompute tokens for all turniket employees
        $turniketTokensList = [];
        foreach ($allTurniket as $t) {
            $tNo = (string) ($t['employee_no'] ?? '');
            $rawName = $t['fullname'] ?? '';
            if ($tNo === '' || empty($rawName)) continue;

            $turniketTokensList[] = [
                'employee_no' => $tNo,
                'fullname'    => $rawName,
                'photo_url'   => $t['photo_url'] ?? null,
                'tokens'      => self::toCanonicalTokens($rawName),
                'raw'         => $t,
            ];
        }

        // 3. Clear existing turniket IDs to start clean and prevent leftover duplicate bugs
        Employee::query()->update([
            'turniket_id'    => null,
            'turniket_photo' => null,
        ]);

        $employees = Employee::with('user')->get();
        $matched = 0;
        $usedTurniketNos = []; // Tracks assigned turniket IDs to guarantee 1-to-1 uniqueness

        foreach ($employees as $emp) {
            // Try employee full_name, user name, user full_name
            $candidateNames = array_filter([
                $emp->full_name,
                $emp->user->name ?? null,
                $emp->user->full_name ?? null,
            ]);

            $matchedTurniket = null;

            foreach ($candidateNames as $name) {
                $uTokens = self::toCanonicalTokens($name);
                if (empty($uTokens)) continue;

                foreach ($turniketTokensList as $tData) {
                    $tNo = $tData['employee_no'];

                    // Skip if already assigned to another employee
                    if (isset($usedTurniketNos[$tNo])) {
                        continue;
                    }

                    if (self::isMatch($uTokens, $tData['tokens'])) {
                        $matchedTurniket = $tData;
                        break 2;
                    }
                }
            }

            if ($matchedTurniket) {
                $tNo = $matchedTurniket['employee_no'];
                $usedTurniketNos[$tNo] = true;

                $emp->turniket_id = $tNo;
                $emp->turniket_photo = $matchedTurniket['photo_url'];
                $emp->save();
                $matched++;
            }
        }

        return [
            'success' => true,
            'message' => "Турникетдан {$matched} та ходимнинг расми ва ID рақами аниқ ва хавфсиз (1-га-1) боғланди.",
            'matched' => $matched,
            'total'   => count($employees),
        ];
    }
}
