<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Throwable;

class TurniketService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.turniket.base_url', 'http://192.168.40.95:8000'), '/');
        $this->apiKey  = (string) config('services.turniket.api_key', 'turniket_api_secret_40_253_2026');
        $this->timeout = (int) config('services.turniket.timeout', 6);
    }

    /**
     * Get default headers for Turniket API requests
     */
    protected function getHeaders(): array
    {
        return [
            'Accept'    => 'application/json',
            'X-API-KEY' => $this->apiKey,
        ];
    }

    /**
     * Send GET request to turniket API with error handling
     */
    protected function get(string $path, array $query = []): array
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->get($url, $query);

            if ($response->successful()) {
                return $response->json() ?? [];
            }

            Log::warning("Turniket API HTTP {$response->status()}", [
                'url'  => $url,
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error'   => "HTTP {$response->status()}: " . $response->reason(),
            ];
        } catch (Throwable $e) {
            Log::error("Turniket API connection failed: " . $e->getMessage(), [
                'url' => $url,
            ]);

            return [
                'success' => false,
                'error'   => 'Турникет сервери билан боғланиш имконсиз: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * 1. Health check (Ochiq)
     */
    public function ping(): array
    {
        return $this->get('/api/ping');
    }

    /**
     * Is the turniket server reachable?
     */
    public function isOnline(): bool
    {
        $res = $this->ping();
        return !empty($res['success']);
    }

    /**
     * 2. Dashboard metrics
     */
    public function getMetrics(): array
    {
        return $this->get('/api/v1/dashboard/metrics');
    }

    /**
     * 3. Today's arrived employees
     */
    public function getArrived(): array
    {
        return $this->get('/api/v1/dashboard/arrived');
    }

    /**
     * 4. Today's absent employees
     */
    public function getAbsent(): array
    {
        return $this->get('/api/v1/dashboard/absent');
    }

    /**
     * 5. Today's late employees
     */
    public function getLate(): array
    {
        return $this->get('/api/v1/dashboard/late');
    }

    /**
     * 6. Employees currently inside building
     */
    public function getInside(): array
    {
        return $this->get('/api/v1/dashboard/inside');
    }

    /**
     * 7. Employees currently outside building
     */
    public function getOutside(): array
    {
        return $this->get('/api/v1/dashboard/outside');
    }

    /**
     * 8. 4 Turnstiles status & today's pass count
     */
    public function getDevices(): array
    {
        return $this->get('/api/v1/dashboard/devices');
    }

    /**
     * 9. Daily, weekly, monthly reports
     */
    public function getReports(array $params = []): array
    {
        return $this->get('/api/v1/reports', $params);
    }

    /**
     * 10. Real-time attendance log
     */
    public function getAttendance(array $params = []): array
    {
        return $this->get('/api/v1/attendance', $params);
    }

    /**
     * 11. Active employees list
     */
    public function getEmployees(array $params = []): array
    {
        return $this->get('/api/v1/employees', $params);
    }

    /**
     * 12. Single employee profile & events
     */
    public function getEmployee(string $employeeNo): array
    {
        return $this->get('/api/v1/employees/' . urlencode($employeeNo));
    }

    /**
     * Send POST request to turniket API with error handling
     */
    protected function post(string $path, array $data = []): array
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->post($url, $data);

            return $response->json() ?? [
                'success' => $response->successful(),
                'status'  => $response->status(),
            ];
        } catch (Throwable $e) {
            Log::error("Turniket API POST failed: " . $e->getMessage(), ['url' => $url]);
            return [
                'success' => false,
                'error'   => 'Турникет сервери билан боғланиш имконсиз: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send PUT request to turniket API with error handling
     */
    protected function put(string $path, array $data = []): array
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->put($url, $data);

            return $response->json() ?? [
                'success' => $response->successful(),
                'status'  => $response->status(),
            ];
        } catch (Throwable $e) {
            Log::error("Turniket API PUT failed: " . $e->getMessage(), ['url' => $url]);
            return [
                'success' => false,
                'error'   => 'Турникет сервери билан боғланиш имконсиз: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send DELETE request to turniket API with error handling
     */
    protected function delete(string $path): array
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->delete($url);

            return $response->json() ?? [
                'success' => $response->successful(),
                'status'  => $response->status(),
            ];
        } catch (Throwable $e) {
            Log::error("Turniket API DELETE failed: " . $e->getMessage(), ['url' => $url]);
            return [
                'success' => false,
                'error'   => 'Турникет сервери билан боғланиш имконсиз: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * 13. Leaves, excuses and business trips
     */
    public function getLeaves(array $params = []): array
    {
        return $this->get('/api/v1/leaves', $params);
    }

    /**
     * 14. Create new leave / excuse
     */
    public function createLeave(array $data): array
    {
        return $this->post('/api/v1/leaves', $data);
    }

    /**
     * 15. Get single leave by ID
     */
    public function getLeave(int|string $id): array
    {
        return $this->get('/api/v1/leaves/' . urlencode((string) $id));
    }

    /**
     * 16. Update leave by ID
     */
    public function updateLeave(int|string $id, array $data): array
    {
        return $this->put('/api/v1/leaves/' . urlencode((string) $id), $data);
    }

    /**
     * 17. Delete leave by ID
     */
    public function deleteLeave(int|string $id): array
    {
        return $this->delete('/api/v1/leaves/' . urlencode((string) $id));
    }
}
