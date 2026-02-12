<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

\Illuminate\Support\Facades\Schedule::command(\App\Console\Commands\AutoCheckoutDailyLogs::class)
    ->dailyAt(\Modules\System\Models\Setting::get('auto_checkout_run_time', '23:59'))
    ->timezone('Asia/Jakarta');
