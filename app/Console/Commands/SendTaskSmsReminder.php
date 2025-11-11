<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Services\EskizSmsService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendTaskSmsReminder extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'Send SMS reminders for tasks expiring tomorrow';

    public function handle(EskizSmsService $sms): void
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $tasks = Task::whereDate('end_date', $tomorrow)
            ->whereIn('status', ['yangi', 'bajarilmoqda', 'uzaytirildi'])
            ->with('assignedUsers')
            ->get();

        foreach ($tasks as $task) {
            foreach ($task->assignedUsers as $user) {
                if (!$user->tel_number) {
                    continue;
                }

                $message = "Eslatma: '{$task->title}' topshirig'ini bajarish muddatiga 1 kun qoldi.";

                $sms->sendSms(998942551397, $message);
            }
        }

        $this->info('SMSlar yuborildi ✅');
    }
}
