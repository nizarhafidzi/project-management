<?php

namespace Modules\Reporting\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Operations\Models\DailyLog;

class WorkforceService
{
    // Threshold constants for utilization status (Based on C = 1/N)
    const STATUS_IDEAL = 1.0;
    const STATUS_MODERATE_MIN = 0.5;
    // Overload is anything less than MODERATE_MIN (0.5), meaning N > 2.

    /**
     * Get staff utilization data based on Daily Logs, optionally filtered by sector, month, and year.
     *
     * @param string|null $sector
     * @param mixed $month
     * @param mixed $year
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStaffUtilization(?string $sector = null, $month = null, $year = null): Collection
    {
        // 1. Query Users
        // We still need users, but their "active projects" will be determined by where they logged time.
        $query = User::query();

        // 2. Filter by Sector if provided (Users who have logs in projects of that sector)
        if ($sector && $sector !== 'All') {
            $query->whereHas('dailyLogs', function (Builder $q) use ($sector, $month, $year) {
                // Filter logs by date if provided
                if ($month) {
                    $q->whereMonth('log_date', $month);
                }
                if ($year) {
                    $q->whereYear('log_date', $year);
                }
                
                // Filter logs by project sector
                $q->whereHas('task.project', function (Builder $p) use ($sector) {
                    $p->where('sector', $sector);
                });
            });
        } else {
             // Basic filter: Users who have ANY logs in this period? 
        }

        // Eager load logs for metrics calculation
        // We need unique projects from logs in this period
        $query->with(['dailyLogs' => function ($q) use ($month, $year) {
            $q->select('id', 'user_id', 'task_id', 'log_date');
            
            if ($month) {
                $q->whereMonth('log_date', $month);
            }
            if ($year) {
                $q->whereYear('log_date', $year);
            }

            $q->with(['task.project:id,name,project_code,sector,status']);
        }]);

        $users = $query->get();

        // 3. Process each user to add utilization metrics based on LOGS
        return $users->map(function ($user) use ($sector) {
            // Get unique projects from logs
            $activeProjects = $user->dailyLogs->map(function ($log) {
                return $log->task?->project;
            })->filter(function ($project) use ($sector) {
                // Filter out nulls (deleted tasks/projects) and apply sector filter if strictly needed here too
                // (though query filtered users, we might want to calculate metrics strictly on sector logs? 
                // Usually sector filter means "Show me staff working in this sector". 
                // If they also work in another sector, do we count it? 
                // "Productivity data for *that specific team*". 
                // I will count ALL projects they worked on to get accurate utilization, 
                // but only return users who match the sector filter (which is handled by query above).
                // Wait, if I filter the query by sector, I only get users who worked in that sector.
                // But their "Overload" status should consider ALL their work, not just that sector.
                // So the eager load above should NOT be filtered by sector.
                return $project !== null;
            })->unique('id');

            $activeProjectsCount = $activeProjects->count();
            
            $user->active_projects_count = $activeProjectsCount;
            // Attach the actual list for Export usage
            $user->active_projects_list = $activeProjects->values();

            // Calculate Coefficient C = 1 / N
            if ($activeProjectsCount > 0) {
                $user->utilization_coefficient = round(1 / $activeProjectsCount, 2);
            } else {
                $user->utilization_coefficient = 0.00;
            }

            $user->utilization_status = $this->determineStatus($user->utilization_coefficient);
            
            return $user;
        }); // Filter users if sector active (if we want to hide users with 0 logs in that sector? 
            // The Main Query handles "Users who have logs in sector". active_projects_count counts ALL work. This is correct.)
    }

    /**
     * Determine the utilization status based on coefficient.
     *
     * @param float $coefficient
     * @return string 'Ideal', 'Moderate', 'Overload'
     */
    private function determineStatus(float $coefficient): string
    {
        // Ideal: Exactly 1.0 (N=1)
        if ($coefficient >= self::STATUS_IDEAL) {
            return 'Ideal';
        }
        
        // Moderate: 0.5 to < 1.0 (N=2 implies 0.5)
        if ($coefficient >= self::STATUS_MODERATE_MIN) {
            return 'Moderate';
        }

        // Overload: < 0.5 (N >= 3)
        return 'Overload';
    }

    /**
     * Get active projects for a specific user, for the modal breakdown, derived from Logs.
     *
     * @param int $userId
     * @param mixed $month
     * @param mixed $year
     * @return \Illuminate\Support\Collection
     */
    public function getProjectBreakdown(int $userId, $month = null, $year = null): Collection
    {
        // Fetch logs for user in period, get unique projects
        $logs = DailyLog::where('user_id', $userId)
            ->when($month, fn($q) => $q->whereMonth('log_date', $month))
            ->when($year, fn($q) => $q->whereYear('log_date', $year))
            ->with('task.project')
            ->get();

        $projects = $logs->map(function ($log) {
            return $log->task?->project;
        })->filter()->unique('id');

        return $projects->values();
    }
}
