<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

final class EskizSmsService
{
    private function getToken(): string
    {
        return Cache::remember(
            'eskiz_sms_token',
            now()->addMinutes(50),
            function (): string {
                $response = Http::asForm()->post(
                    'https://notify.eskiz.uz/api/auth/login',
                    [
                        'email' => config('services.eskiz.email'),
                        'password' => config('services.eskiz.password'),
                    ]
                );

                if (!$response->successful()) {
                    throw new RuntimeException('Eskiz token olishda xatolik');
                }

                return $response->json('data.token');
            }
        );
    }

    public function send(string $phone, string $message): void
    {
        $token = $this->getToken();

        Http::withToken($token)
            ->asForm()
            ->post('https://notify.eskiz.uz/api/message/sms/send', [
                'mobile_phone' => $phone,
                'message'      => $message,
                'from'         => config('services.eskiz.from'),
            ]);
    }
}
