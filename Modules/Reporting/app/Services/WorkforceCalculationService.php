<?php

namespace Modules\Reporting\Services;

use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Operations\Models\DailyLog;
use Modules\Reporting\Models\Holiday;
use Modules\Reporting\Models\WorkforceMonthlySummary;

class WorkforceCalculationService
{
    /**
     * Calculate the total number of working days (Mon-Fri) in a given month/year,
     * minus any national holidays that fall on weekdays.
     */
    public function getWorkingDays(int $month, int $year): int
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        // Count all weekdays (Mon-Fri) in the month
        $weekdays = 0;
        $period = CarbonPeriod::create($start, $end);

        foreach ($period as $date) {
            if ($date->isWeekday()) {
                $weekdays++;
            }
        }

        // Subtract holidays that fall on weekdays
        $holidayCount = Holiday::whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->filter(function ($holiday) {
                return Carbon::parse($holiday->date)->isWeekday();
            })
            ->count();

        return max(0, $weekdays - $holidayCount);
    }

    /**
     * Calculate and sync workforce monthly summary for all users (or a specific user).
     *
     * @return int Number of user summaries synced
     */
    public function calculateAndSyncSummary(int $month, int $year, ?int $userId = null): int
    {
        $totalWorkingDays = $this->getWorkingDays($month, $year);

        if ($totalWorkingDays === 0) {
            return 0;
        }

        $startDate = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();

        // Build the daily_logs query for the period
        $logsQuery = DailyLog::whereBetween('log_date', [$startDate, $endDate])
            ->whereNotNull('clock_in');

        if ($userId) {
            $logsQuery->where('user_id', $userId);
        }

        // Eager-load the task → project relationship for project grouping
        $logs = $logsQuery->with('task.project:id,name,project_code')->get();

        // Group logs by user_id
        $logsByUser = $logs->groupBy('user_id');

        // If filtering by a single user and they have no logs, still create a zero record
        if ($userId && $logsByUser->isEmpty()) {
            WorkforceMonthlySummary::updateOrCreate(
                ['user_id' => $userId, 'month' => $month, 'year' => $year],
                [
                    'total_working_days' => $totalWorkingDays,
                    'attended_days' => 0,
                    'absent_days' => $totalWorkingDays,
                    'total_utilization' => 0,
                    'project_details' => [],
                ]
            );
            return 1;
        }

        // Get all users to process (including those with no logs)
        $usersQuery = User::query();
        if ($userId) {
            $usersQuery->where('id', $userId);
        }
        $allUserIds = $usersQuery->pluck('id');

        $syncedCount = 0;

        foreach ($allUserIds as $uid) {
            $userLogs = $logsByUser->get($uid, collect());

            // Attended days = unique log_date count
            $attendedDays = $userLogs->pluck('log_date')
                ->map(fn($d) => Carbon::parse($d)->toDateString())
                ->unique()
                ->count();

            $absentDays = max(0, $totalWorkingDays - $attendedDays);

            $totalUtilization = round(($attendedDays / $totalWorkingDays) * 100, 2);

            // Group by project to build project_details
            $projectDetails = [];
            $logsByProject = $userLogs->groupBy(function ($log) {
                return $log->task?->project?->id ?? 0;
            });

            foreach ($logsByProject as $projectId => $projectLogs) {
                if ($projectId === 0) {
                    continue; // skip orphan logs with deleted tasks/projects
                }

                $project = $projectLogs->first()->task->project;
                $daysOnProject = $projectLogs->pluck('log_date')
                    ->map(fn($d) => Carbon::parse($d)->toDateString())
                    ->unique()
                    ->count();

                $coefficient = round($daysOnProject / $totalWorkingDays, 2);

                $projectDetails[] = [
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'project_code' => $project->project_code ?? null,
                    'days' => $daysOnProject,
                    'coefficient' => $coefficient,
                ];
            }

            WorkforceMonthlySummary::updateOrCreate(
                ['user_id' => $uid, 'month' => $month, 'year' => $year],
                [
                    'total_working_days' => $totalWorkingDays,
                    'attended_days' => $attendedDays,
                    'absent_days' => $absentDays,
                    'total_utilization' => $totalUtilization,
                    'project_details' => $projectDetails,
                ]
            );

            $syncedCount++;
        }

        return $syncedCount;
    }
}
