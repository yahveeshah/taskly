<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('taskly:send-welcome-emails')->everyMinute();
Schedule::command('taskly:send-feedback-emails')->daily();
Schedule::command('taskly:send-deadline-reminders')->dailyAt('08:00');
