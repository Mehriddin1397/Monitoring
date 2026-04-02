<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Services\EskizSmsService;
use Carbon\Carbon;

class SendBirthdaySms extends Command
{
    // Komanda nomi (Terminalda ishlatish uchun)
    protected $signature = 'employees:send-birthday-sms';

    // Komanda tavsifi
    protected $description = "Bugun tug'ilgan kuni bo'lgan xodimlarga tabrik SMS yuborish";

    public function handle(EskizSmsService $smsService): int
    {
        $today = Carbon::now();

        // Bugun tug'ilgan kuni bo'lgan xodimlarni qidirish
        $employees = Employee::whereMonth('birth_date', $today->month)
            ->whereDay('birth_date', $today->day)
            ->get();

        // Agar bugun hech kimning tug'ilgan kuni bo'lmasa, komandani to'xtatish
        if ($employees->isEmpty()) {
            $this->info("Bugun hech kimning tug'ilgan kuni emas.");
            return self::SUCCESS;
        }

        $count = 0;

        foreach ($employees as $employee) {
            // Agar telefon raqami yo'q bo'lsa, keyingisiga o'tish
            if (!$employee->phone) continue;

            // Xodim ismini olib, chiroyli tabrik matnini shakllantirish
            // Matnni o'zingizning xohishingizga qarab o'zgartirishingiz mumkin
            $message = "Hurmatli {$employee->full_name}! Kriminologiya jamoasi sizni tug'ilgan kuningiz bilan chin dildan muborakbod etadi. Sizga mustahkam sog'liq, baxt-saodat va ishlaringizda ulkan zafarlar tilaymiz!";

            // Eskiz SMS orqali yuborish
            // (Eskiz odatda '998901234567' formatini kutadi, bazada shunday saqlangan deb faraz qildik)
            $smsService->send($employee->phone, $message);

            $count++;
        }

        $this->info("Tug'ilgan kun tabrigi muvaffaqiyatli yuborildi: {$count} ta xodimga.");

        return self::SUCCESS;
    }
}
