<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class CctvService
{
    protected string $baseUrl;
    protected string $apiUrl;
    protected string $apiKey;
    protected string $apiSecret;

    public function __construct()
    {
        $this->baseUrl   = rtrim(config('services.cctv.base_url', 'http://192.168.40.123:8000'), '/');
        $this->apiUrl    = rtrim(config('services.cctv.api_url', 'http://192.168.40.123:8000/api/v1'), '/');
        $this->apiKey    = (string) config('services.cctv.api_key', 'cctv_portal_40_253');
        $this->apiSecret = (string) config('services.cctv.api_secret', 'SecretKey_40_253_CCTV!');
    }

    /**
     * Get headers required for CCTV API authentication
     */
    protected function getHeaders(): array
    {
        return [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
            'X-API-Key'    => $this->apiKey,
            'X-API-Secret' => $this->apiSecret,
        ];
    }

    /**
     * Fetch all cameras from CCTV server
     */
    public function getCameras(bool $fresh = false): array
    {
        $cacheKey = 'cctv_cameras_list';

        if (!$fresh && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(5)
                ->get("{$this->apiUrl}/cameras");

            if ($response->successful()) {
                $data = $response->json();
                $cameras = $data['data'] ?? [];
                // Cache for 15 seconds to keep camera online/offline statuses updated
                Cache::put($cacheKey, $cameras, now()->addSeconds(15));
                return $cameras;
            }

            Log::warning('CCTV API getCameras returned non-success: ' . $response->status() . ' - ' . $response->body());
        } catch (Exception $e) {
            Log::error('CCTV API getCameras exception: ' . $e->getMessage());
        }

        return Cache::get($cacheKey, []);
    }

    /**
     * Find single camera by ID
     */
    public function getCamera(int|string $id): ?array
    {
        $cameras = $this->getCameras();
        foreach ($cameras as $camera) {
            if ($camera['id'] == $id) {
                return $camera;
            }
        }
        return null;
    }

    /**
     * Generate or retrieve cached embed token for a camera
     */
    public function generateEmbed(int|string $id, int $hours = 72, string $quality = 'sub'): ?array
    {
        $cacheKey = "cctv_embed_cam_{$id}_{$quality}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(7)
                ->post("{$this->apiUrl}/embed/generate/{$id}", [
                    'hours'   => $hours,
                    'quality' => $quality,
                ]);

            if ($response->successful()) {
                $body = $response->json();
                if (!empty($body['success']) && isset($body['data'])) {
                    $embedData = $body['data'];
                    // Cache token for 24 hours (less than requested hours to be safe)
                    Cache::put($cacheKey, $embedData, now()->addHours(24));
                    return $embedData;
                }
            }

            Log::warning("CCTV API generateEmbed for camera #{$id} failed: " . $response->body());
        } catch (Exception $e) {
            Log::error("CCTV API generateEmbed exception for camera #{$id}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Get embed URL directly
     */
    public function getEmbedUrl(int|string $id, string $quality = 'sub'): ?string
    {
        $embed = $this->generateEmbed($id, 72, $quality);
        return $embed['embed_url'] ?? null;
    }

    /**
     * Base URL of the CCTV server
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Send PTZ action to camera
     */
    public function ptzRequest(int|string $id, string $endpoint, string $method = 'POST', array $data = []): array
    {
        try {
            $url = "{$this->apiUrl}/cameras/{$id}/ptz/{$endpoint}";
            $req = Http::withHeaders($this->getHeaders())->timeout(6);

            $response = match (strtoupper($method)) {
                'GET'    => $req->get($url, $data),
                'DELETE' => $req->delete($url, $data),
                default  => $req->post($url, $data),
            };

            return [
                'status'  => $response->status(),
                'success' => $response->successful(),
                'data'    => $response->json() ?? ['raw' => $response->body()],
            ];
        } catch (Exception $e) {
            Log::error("PTZ Request error [cam: {$id}, endpoint: {$endpoint}]: " . $e->getMessage());
            return [
                'status'  => 500,
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
