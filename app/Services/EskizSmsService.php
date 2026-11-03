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

    public function send(string $phone, string $message): bool
    {
        // 1. Telefon raqamini tozalash (faqat raqamlarni qoldirish)
        // Masalan: "+998 (90) 123-45-67" -> "998901234567"
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

        // O'zbekiston raqamlarini to'g'rilash (9 xonali bo'lsa 998 qo'shish)
        if (strlen($cleanPhone) === 9) {
            $cleanPhone = '998' . $cleanPhone;
        } elseif (strlen($cleanPhone) === 10 && str_starts_with($cleanPhone, '8')) {
            $cleanPhone = '998' . substr($cleanPhone, 1);
        }

        if (empty($cleanPhone) || strlen($cleanPhone) < 9) {
            Log::warning("Eskiz SMS: Telefon raqam bo'sh yoki noto'g'ri: $phone");
            return false;
        }

        try {
            $token = $this->getToken();

            $response = Http::timeout(10)
                ->withToken($token)
                ->asForm()
                ->post('https://notify.eskiz.uz/api/message/sms/send', [
                    'mobile_phone' => $cleanPhone,
                    'message'      => $message,
                    'from'         => config('services.eskiz.from', '4546'),
                ]);

            // 2. Eskizdan kelgan javobni tekshirish
            if (!$response->successful() || $response->json('status') === 'error') {
                Log::error("Eskiz SMS Xatoligi ($cleanPhone): " . $response->body());
                return false;
            }

            Log::info("Eskiz SMS Yuborildi ($cleanPhone): " . $response->body());
            return true;
        } catch (\Throwable $e) {
            Log::error("Eskiz SMS yuborishda kutilmagan xatolik ($cleanPhone): " . $e->getMessage());
            return false;
        }
    }
}
