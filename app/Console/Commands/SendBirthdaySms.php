<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Services\EskizSmsService;
use Carbon\Carbon;

class SendBirthdaySms extends Command
{
    // Komanda nomi (Terminalda ishlatish uchun)
    protected $signature = 'employees:send-birthday-sms {--employee= : Maxsus xodim ID si} {--force : Sanasidan qat\'iy nazar yuborish}';

    // Komanda tavsifi
    protected $description = "Bugun tug'ilgan kuni bo'lgan xodimlarga tabrik SMS yuborish";

    public function handle(EskizSmsService $smsService): int
    {
        $today = Carbon::now('Asia/Tashkent');

        $employeeId = $this->option('employee');
        $force = $this->option('force');

        if ($employeeId) {
            $query = Employee::where('id', $employeeId);
            if (!$force) {
                $query->whereMonth('birth_date', $today->month)->whereDay('birth_date', $today->day);
            }
            $employees = $query->get();
        } else {
            // Bugun tug'ilgan kuni bo'lgan xodimlarni qidirish
            $employees = Employee::whereMonth('birth_date', $today->month)
                ->whereDay('birth_date', $today->day)
                ->get();
        }

        // Agar bugun hech kimning tug'ilgan kuni bo'lmasa, komandani to'xtatish
        if ($employees->isEmpty()) {
            $this->info("Bugun hech kimning tug'ilgan kuni emas.");
            return self::SUCCESS;
        }

        $count = 0;

        foreach ($employees as $employee) {
            // Agar telefon raqami yo'q bo'lsa, keyingisiga o'tish
            if (!$employee->phone) {
                $this->warn("Xodim #{$employee->id} ({$employee->full_name}) telefon raqamiga ega emas.");
                continue;
            }

            try {
                // Xodim ismini olib, chiroyli tabrik matnini shakllantirish
                $message = !empty($employee->custom_wish)
                    ? "Hurmatli {$employee->full_name}! {$employee->custom_wish}"
                    : "Hurmatli {$employee->full_name}! Kriminologiya jamoasi sizni tug'ilgan kuningiz bilan chin dildan muborakbod etadi. Sizga mustahkam sog'liq, baxt-saodat va ishlaringizda ulkan zafarlar tilaymiz!";

                // Eskiz SMS orqali yuborish
                $sent = $smsService->send($employee->phone, $message);

                if ($sent) {
                    $this->info("SMS yuborildi: {$employee->full_name} ({$employee->phone})");
                    $count++;
                } else {
                    $this->error("SMS yuborilmadi: {$employee->full_name} ({$employee->phone})");
                }
            } catch (\Throwable $e) {
                $this->error("Xatolik ({$employee->full_name}): " . $e->getMessage());
            }
        }

        $this->info("Tug'ilgan kun tabrigi yakunlandi: {$count} ta xodimga muvaffaqiyatli yuborildi.");

        return self::SUCCESS;
    }
}
