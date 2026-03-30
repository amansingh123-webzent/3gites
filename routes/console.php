<?php

use App\Jobs\BirthdayReminderJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run the birthday reminder every day at 8:00 AM (server time)
Schedule::job(new BirthdayReminderJob)->dailyAt('08:00');
