<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IzlanuvchiService
{
    protected string $baseUrl;
    protected string $apiToken;
    protected string $staffPhone;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.izlanuvchi.base_url', 'http://192.168.40.104/api/v1'), '/');
        $this->apiToken = (string) config('services.izlanuvchi.api_token', 'kti_mi_secret_token_2026_xodimlar_kotib_key');
        $this->staffPhone = (string) config('services.izlanuvchi.staff_phone', '+998937983107');
        $this->timeout = (int) config('services.izlanuvchi.timeout', 10);
    }

    /**
     * Tizim API clientini tayyorlash
     */
    protected function client(?string $staffPhone = null)
    {
        $headers = [
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiToken,
            'X-API-KEY'     => $this->apiToken,
        ];

        $phone = $staffPhone ?: $this->staffPhone;
        if (!empty($phone)) {
            $headers['X-Staff-Phone'] = $phone;
        }

        return Http::withHeaders($headers)
            ->timeout($this->timeout);
    }

    /**
     * API serverining onlayn/aloqada ekanligini tezkor tekshirish
     */
    public function isOnline(): bool
    {
        try {
            $res = Http::withHeaders([
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiToken,
            ])->timeout(3)->get($this->baseUrl . '/stats/overview');

            return $res->successful();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 1.1. Tizimning umumiy ko'rsatkichlari (Dashboard)
     */
    public function getOverview(): array
    {
        try {
            $res = $this->client()->get($this->baseUrl . '/stats/overview');

            if ($res->successful()) {
                $json = $res->json();
                return $json['data'] ?? [];
            }

            Log::warning('IzlanuvchiService::getOverview xato javob:', [
                'status' => $res->status(),
                'body'   => $res->body(),
            ]);
            return [];
        } catch (Exception $e) {
            Log::error('IzlanuvchiService::getOverview ulanish xatosi: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * 1.2. Mustaqil izlanuvchilar ro'yxati va ularning o'qish/ariza progressi
     */
    public function getResearchers(array $params = []): array
    {
        try {
            $res = $this->client()->get($this->baseUrl . '/stats/researchers', $params);

            if ($res->successful()) {
                $json = $res->json();
                return [
                    'data'       => $json['data'] ?? [],
                    'pagination' => $json['pagination'] ?? [
                        'current_page' => 1,
                        'last_page'    => 1,
                        'per_page'     => 20,
                        'total'        => 0,
                    ],
                ];
            }

            return ['data' => [], 'pagination' => ['current_page' => 1, 'last_page' => 1, 'per_page' => 20, 'total' => 0]];
        } catch (Exception $e) {
            Log::error('IzlanuvchiService::getResearchers xatosi: ' . $e->getMessage());
            return ['data' => [], 'pagination' => ['current_page' => 1, 'last_page' => 1, 'per_page' => 20, 'total' => 0]];
        }
    }

    /**
     * 1.3. Xodimlar (Kotiblar) ish yuklamasi va unumdorligi
     */
    public function getStaffWorkload(): array
    {
        try {
            $res = $this->client()->get($this->baseUrl . '/stats/staff-workload');

            if ($res->successful()) {
                $json = $res->json();
                return $json['data'] ?? [];
            }

            return [];
        } catch (Exception $e) {
            Log::error('IzlanuvchiService::getStaffWorkload xatosi: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * 2.1. Ko'rib chiqilishi kerak bo'lgan vazifalar ro'yxati
     */
    public function getKotibTasks(array $params = []): array
    {
        try {
            $res = $this->client()->get($this->baseUrl . '/kotib/tasks', $params);

            if ($res->successful()) {
                $json = $res->json();
                return [
                    'data'       => $json['data'] ?? [],
                    'pagination' => $json['pagination'] ?? [
                        'current_page' => 1,
                        'last_page'    => 1,
                        'per_page'     => 20,
                        'total'        => 0,
                    ],
                ];
            }

            return ['data' => [], 'pagination' => ['current_page' => 1, 'last_page' => 1, 'per_page' => 20, 'total' => 0]];
        } catch (Exception $e) {
            Log::error('IzlanuvchiService::getKotibTasks xatosi: ' . $e->getMessage());
            return ['data' => [], 'pagination' => ['current_page' => 1, 'last_page' => 1, 'per_page' => 20, 'total' => 0]];
        }
    }

    /**
     * 2.2. Alohida vazifaning batafsil ma'lumoti
     */
    public function getTask(int $id): ?array
    {
        try {
            $res = $this->client()->get($this->baseUrl . '/kotib/tasks/' . $id);

            if ($res->successful()) {
                $json = $res->json();
                return $json['data'] ?? null;
            }

            return null;
        } catch (Exception $e) {
            Log::error('IzlanuvchiService::getTask xatosi: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * 2.3. Hujjat faylini yuklab olish
     */
    public function downloadDocument(int $id): ?Response
    {
        try {
            $res = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'X-API-KEY'     => $this->apiToken,
            ])->timeout(30)->get($this->baseUrl . '/kotib/documents/' . $id . '/download');

            if ($res->successful()) {
                return $res;
            }

            return null;
        } catch (Exception $e) {
            Log::error('IzlanuvchiService::downloadDocument xatosi: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * 2.4. Hujjatni tasdiqlash (Approve)
     */
    public function approveTask(int $id, ?string $staffPhone = null): array
    {
        try {
            $res = $this->client($staffPhone)->post($this->baseUrl . '/kotib/tasks/' . $id . '/approve');

            $json = $res->json();
            if ($res->successful() && ($json['success'] ?? false)) {
                return [
                    'success' => true,
                    'message' => $json['message'] ?? 'Вазифа муваффақиятли тасдиқланди.',
                    'data'    => $json['data'] ?? [],
                ];
            }

            return [
                'success' => false,
                'message' => $json['message'] ?? 'Вазифани тасдиқлашда хатолик юз берди (' . $res->status() . ').',
            ];
        } catch (Exception $e) {
            Log::error('IzlanuvchiService::approveTask xatosi: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Сервер билан боғланишда хатолик: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * 2.5. Hujjatni qaytarish / rad etish (Reject)
     */
    public function rejectTask(int $id, string $comment, ?string $staffPhone = null): array
    {
        try {
            $res = $this->client($staffPhone)->post($this->baseUrl . '/kotib/tasks/' . $id . '/reject', [
                'comment' => $comment,
            ]);

            $json = $res->json();
            if ($res->successful() && ($json['success'] ?? false)) {
                return [
                    'success' => true,
                    'message' => $json['message'] ?? 'Вазифа рад этилди ва изланувчига хабарнома юборилди.',
                    'data'    => $json['data'] ?? [],
                ];
            }

            return [
                'success' => false,
                'message' => $json['message'] ?? 'Вазифани рад этишда хатолик юз берди (' . $res->status() . ').',
            ];
        } catch (Exception $e) {
            Log::error('IzlanuvchiService::rejectTask xatosi: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Сервер билан боғланишда хатолик: ' . $e->getMessage(),
            ];
        }
    }
}
