<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class EskizSmsService
{
    private $baseUrl;
    private $email;
    private $password;

    public function __construct()
    {
        $this->baseUrl = config('services.eskiz.base_url');
        $this->email = config('services.eskiz.email');
        $this->password = config('services.eskiz.password');
    }

    // Token olish
    private function getToken()
    {
        if (Cache::has('eskiz_token')) {
            return Cache::get('eskiz_token');
        }

        return $this->refreshToken();
    }

    // Tokenni yangilash (eskirganida yoki yo‘q bo‘lsa)
    private function refreshToken()
    {
        $response = Http::post($this->baseUrl . '/api/auth/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $token = $response->json('data.token');

        // token topilmasa xatoni qaytaramiz
        if (!$token) {
            throw new \Exception("Eskiz token ololmadi: " . $response->body());
        }

        Cache::put('eskiz_token', $token, now()->addHours(12));

        return $token;
    }

    // SMS yuborish
    public function sendSms($phone, $message)
    {
        $token = $this->getToken();

        $response = Http::withToken($token)->post($this->baseUrl . '/api/message/sms/send', [
            'mobile_phone' => $phone, // ← telefon endi to‘g‘ri ishlamoqda
            'message' => $message,
            'from' => '4546', // tasdiqlangan sender bo‘ladi
        ]);

        // Agar token eskirgan bo‘lsa — qayta token olib, yana yuboramiz
        if ($response->status() === 401) {
            $token = $this->refreshToken();

            $response = Http::withToken($token)->post($this->baseUrl . '/api/message/sms/send', [
                'mobile_phone' => $phone,
                'message' => $message,
                'from' => '4546',
            ]);
        }

        return $response->json();
    }
}
