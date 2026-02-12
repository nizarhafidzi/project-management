<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Modules\System\Models\Setting;
use Modules\Operations\Models\DailyLog;

class AutoCheckoutDailyLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-checkout-daily-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically checkout daily logs based on system settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $enabled = Setting::get('auto_checkout_enabled', false);

        if (!$enabled) {
            $this->info('Auto-checkout is disabled.');
            return;
        }

        $defaultClockOutTime = Setting::get('default_clock_out_time', '17:00');
        $runTime = Setting::get('auto_checkout_run_time', '23:59');
        
        // Ensure operations are in Asia/Jakarta as requested
        $timezone = 'Asia/Jakarta';
        $now = Carbon::now($timezone);

        $this->info("Running Auto-Checkout at {$now->toDateTimeString()}...");

        // Find open logs. Assuming 'status' column exists and 'open' is the value for active logs.
        // We verify the status value from the model or enum later.
        // The prompt says: "Identify all `daily_logs` that are still `open` on that day."
        // We should target logs for TODAY or older that are still open.
        
        $logs = DailyLog::where('status', 'open') // Verify status value!
            ->whereDate('date', '<=', $now->toDateString())
            ->get();

        if ($logs->isEmpty()) {
            $this->info('No open logs found.');
            return;
        }

        DB::transaction(function () use ($logs, $defaultClockOutTime) {
            foreach ($logs as $log) {
                // Determine the correct clock out time.
                // If the log is for a past date, use the default clock out time.
                // If the log is for today, check if it's time to close it. (Actually the command runs at scheduled time, so we close it)
                
                $description = $log->description ? $log->description . "\nAuto-closed by system" : "Auto-closed by system";

                $log->update([
                    'clock_out_time' => $defaultClockOutTime,
                    'status' => 'system_checkout', // Requirement: 'system_checkout'
                    'description' => $description,
                ]);
            }
        });

        $this->info("Successfully closed {$logs->count()} logs.");
    }
}
