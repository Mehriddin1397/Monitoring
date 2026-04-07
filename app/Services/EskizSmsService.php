<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log; // Log fasadini qo'shish
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
                    Log::error('Eskiz Token Error: ' . $response->body());
                    throw new RuntimeException('Eskiz token olishda xatolik');
                }

                return $response->json('data.token');
            }
        );
    }

    public function send(string $phone, string $message): void
    {
        $token = $this->getToken();

        // 1. Telefon raqamini tozalash (faqat raqamlarni qoldirish)
        // Masalan: "+998 90 123 45 67" -> "998901234567"
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

        $response = Http::withToken($token)
            ->asForm()
            ->post('https://notify.eskiz.uz/api/message/sms/send', [
                'mobile_phone' => $cleanPhone,
                'message'      => $message,
                'from'         => config('services.eskiz.from', '4546'), // Agar env da bo'lmasa standart 4546
            ]);

        // 2. Eskizdan kelgan javobni tekshirish
        if (!$response->successful() || $response->json('status') === 'error') {
            // Xatolikni logga yozish
            Log::error("Eskiz SMS Xatoligi ($cleanPhone): " . $response->body());
        } else {
            // Muvaffaqiyatli yuborilsa ham logga yozish (ixtiyoriy)
            Log::info("Eskiz SMS Yuborildi ($cleanPhone): " . $response->body());
        }
    }
}
