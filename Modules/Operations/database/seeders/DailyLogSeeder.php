<?php

namespace Modules\Operations\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Operations\Models\DailyLog;
use Modules\Project\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class DailyLogSeeder extends Seeder
{
    /**
     * Seed daily_logs with test data for today and backdate scenarios.
     */
    public function run(): void
    {
        $employee1 = User::where('email', 'employee1@cipms.test')->first();
        $employee2 = User::where('email', 'employee2@cipms.test')->first();

        if (!$employee1) {
            $this->command->warn('DailyLogSeeder: employee1@cipms.test not found. Skipping.');
            return;
        }

        $tasks = Task::take(3)->get();
        if ($tasks->isEmpty()) {
            $this->command->warn('DailyLogSeeder: No tasks found. Skipping.');
            return;
        }

        $today = Carbon::now('Asia/Jakarta')->toDateString();

        // ──────────────────────────────────────────────
        // 1. Standard log for today — Clocked In, not yet out
        // ──────────────────────────────────────────────
        DailyLog::create([
            'user_id' => $employee1->id,
            'task_id' => $tasks[0]->id,
            'log_date' => $today,
            'clock_in' => Carbon::now('Asia/Jakarta')->subHours(2)->format('H:i:s'),
            'clock_out' => null,
            'progress_increment' => 0,
            'is_backdate' => false,
            'approval_status' => 'approved',
        ]);

        // ──────────────────────────────────────────────
        // 2. Completed log for today — Clocked In & Out + progress
        // ──────────────────────────────────────────────
        if (isset($tasks[1])) {
            DailyLog::create([
                'user_id' => $employee1->id,
                'task_id' => $tasks[1]->id,
                'log_date' => $today,
                'clock_in' => Carbon::now('Asia/Jakarta')->subHours(4)->format('H:i:s'),
                'clock_out' => Carbon::now('Asia/Jakarta')->subHours(3)->format('H:i:s'),
                'progress_increment' => 5,
                'is_backdate' => false,
                'approval_status' => 'approved',
            ]);
        }

        // ──────────────────────────────────────────────
        // 3. Pending backdate request — Yesterday (Employee 1)
        // ──────────────────────────────────────────────
        DailyLog::create([
            'user_id' => $employee1->id,
            'task_id' => $tasks[0]->id,
            'log_date' => Carbon::yesterday('Asia/Jakarta')->toDateString(),
            'clock_in' => '09:00:00',
            'clock_out' => '17:00:00',
            'progress_increment' => 10,
            'is_backdate' => true,
            'approval_status' => 'pending',
        ]);

        // ──────────────────────────────────────────────
        // 4. Pending backdate request — 2 days ago (Employee 1)
        // ──────────────────────────────────────────────
        DailyLog::create([
            'user_id' => $employee1->id,
            'task_id' => $tasks[0]->id,
            'log_date' => Carbon::now('Asia/Jakarta')->subDays(2)->toDateString(),
            'clock_in' => '08:00:00',
            'clock_out' => '16:00:00',
            'progress_increment' => 15,
            'is_backdate' => true,
            'approval_status' => 'pending',
        ]);

        // ──────────────────────────────────────────────
        // 5. Pending backdate from Employee 2 (if exists)
        // ──────────────────────────────────────────────
        if ($employee2 && isset($tasks[2])) {
            DailyLog::create([
                'user_id' => $employee2->id,
                'task_id' => $tasks[2]->id,
                'log_date' => Carbon::yesterday('Asia/Jakarta')->toDateString(),
                'clock_in' => '10:00:00',
                'clock_out' => '18:00:00',
                'progress_increment' => 8,
                'is_backdate' => true,
                'approval_status' => 'pending',
            ]);
        }

        // ──────────────────────────────────────────────
        // 6. Rejected backdate — 3 days ago (for audit trail testing)
        // ──────────────────────────────────────────────
        DailyLog::create([
            'user_id' => $employee1->id,
            'task_id' => $tasks[0]->id,
            'log_date' => Carbon::now('Asia/Jakarta')->subDays(3)->toDateString(),
            'clock_in' => '08:30:00',
            'clock_out' => '15:00:00',
            'progress_increment' => 12,
            'is_backdate' => true,
            'approval_status' => 'rejected',
            'rejection_reason' => 'Missing supporting documentation. Please resubmit with proof.',
        ]);
    }
}
