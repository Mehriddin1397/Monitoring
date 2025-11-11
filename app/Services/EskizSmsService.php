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

        $response = Http::post($this->baseUrl . '/api/auth/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $token = $response->json('data.token');
        Cache::put('eskiz_token', $token, now()->addHours(12)); // 12 soat saqlanadi

        return $token;
    }

    // SMS yuborish
    public function sendSms($phone, $message)
    {
        $token = $this->getToken();

        return Http::withToken($token)->post($this->baseUrl . '/api/message/sms/send', [
            'mobile_phone' => 998942551397,
            'message' => $message,
            'from' => '4546', // alpha-name emas, default xizmat raqami
            'callback_url' => 'http://your-site.com/callback' // optional
        ]);
    }

    public static function send($phone)
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . env('ESKIZ_TOKEN')
        ])->post('https://notify.eskiz.uz/api/message/sms/send', [
            'mobile_phone' => $phone,
            'message' => "Bu Eskiz dan test",
            'from' => '4546',
        ]);
    }
}
