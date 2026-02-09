<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\User;
use App\Services\EskizSmsService;
use Carbon\Carbon;

class CheckTaskDeadlines extends Command
{
    protected $signature = 'tasks:check-deadlines';
    protected $description = 'Check task deadlines and send Eskiz SMS';

    public function handle(EskizSmsService $smsService): int
    {
        $today = now()->toDateString();
//        $twoDaysLater = now()->addDays(2)->toDateString();

        // 2 kun qolgan tasklar
//        $tasks = Task::whereDate('end_date', $twoDaysLater)
//            ->with('assignedUsers:id,tel_number')
//            ->get();
//
//        $userTaskCounts = [];
//
//        foreach ($tasks as $task) {
//            foreach ($task->assignedUsers as $user) {
//                if (!$user->tel_number) continue;
//
//                $userTaskCounts[$user->id]['phone'] = $user->tel_number;
//                $userTaskCounts[$user->id]['count'] =
//                    ($userTaskCounts[$user->id]['count'] ?? 0) + 1;
//            }
//        }
//
//        foreach ($userTaskCounts as $data) {
//            $smsService->send(
//                $data['phone'],
//                "Sizda {$data['count']} ta topshiriq muddati tugamoqda. Topshirishga 2-kun vaqt qoldi."
//            );
//        }

        // Bugungi topshiriqlar
        $todayCount = Task::whereDate('end_date', $today)->count();

//        $adminPhone = '998942551397';
        $adminPhone = '998941050078';

        $smsService->send(
            $adminPhone,
            "Smart Ijro-Nazorat tizimida bugun topshirilishi kerak bo'lgan topshiriqlar soni: {$todayCount} ta."
        );

        $this->info('SMS muvaffaqiyatli yuborildi');

        return self::SUCCESS;
    }
}
