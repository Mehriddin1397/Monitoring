<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Har daqiqada yuborish uchun:
Schedule::command('tasks:send-reminders')->dailyAt('04:00');

Schedule::command('tasks:check-deadlines')->dailyAt('04:00');
