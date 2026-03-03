<?php

namespace Modules\Reporting\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Project\Models\Project;
use Modules\Project\Models\Task;
use Modules\Reporting\Models\ProjectPlan;

class ProjectPlanService
{
    /**
     * Generate or regenerate the S-Curve plan for a project.
     *
     * @param Project $project
     * @return void
     */
    public function generateProjectPlan(Project $project): void
    {
        // 1. Clear existing plan for this project
        ProjectPlan::where('project_id', $project->id)->delete();

        // 2. Fetch all LEAF tasks (tasks with no children)
        // Only leaf tasks contribute to the physical progress weight distribution.
        $tasks = Task::where('project_id', $project->id)
            ->whereDoesntHave('children')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->where('weight', '>', 0)
            ->get();

        if ($tasks->isEmpty()) {
            return;
        }

        // 3. Initialize daily weights array
        // We need to track the sum of weights for each day across all tasks.
        // Key: Date string (Y-m-d), Value: float (daily weight sum)
        $dailyWeights = [];

        // Determine project range to initialize the array (optional but good for continuous dates)
        $projectStartDate = $tasks->min('start_date');
        $projectEndDate = $tasks->max('end_date');
        
        if (!$projectStartDate || !$projectEndDate) {
            return;
        }

        $startDate = Carbon::parse($projectStartDate);
        $endDate = Carbon::parse($projectEndDate);
        
        // Pre-fill dailyWeights with 0 for the entire range to avoid gaps
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $dailyWeights[$date->format('Y-m-d')] = 0.0;
        }

        // 4. Distribute weights for each task
        foreach ($tasks as $task) {
            $this->distributeTaskWeight($task, $dailyWeights);
        }

        // 5. Calculate Cumulative Progress and Save
        $cumulativeProgress = 0.0;
        $batchData = [];

        // Sort by date key to ensure correct cumulative calculation
        ksort($dailyWeights);

        foreach ($dailyWeights as $dateStr => $dailyWeight) {
            $cumulativeProgress += $dailyWeight;
            
            // Cap at 100.00 to be safe, though logic should handle it
            $cumulativeProgress = min($cumulativeProgress, 100.00);

            $batchData[] = [
                'project_id' => $project->id,
                'period_date' => $dateStr,
                'planned_progress' => round($cumulativeProgress, 2),
                'actual_progress' => null, // Actuals are handled separately
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 6. Bulk Insert
        if (!empty($batchData)) {
            ProjectPlan::insert($batchData);
        }
    }

    /**
     * Distribute a single task's weight over its working days.
     *
     * @param Task $task
     * @param array $dailyWeights Reference to the master daily weights array
     */
    private function distributeTaskWeight(Task $task, array &$dailyWeights): void
    {
        $start = Carbon::parse($task->start_date);
        $end = Carbon::parse($task->end_date);
        $weight = (float) $task->weight;

        // Identify Working Days
        $workingDays = [];
        $period = \Carbon\CarbonPeriod::create($start, $end);

        foreach ($period as $date) {
            if ($this->isWorkday($date)) {
                $workingDays[] = $date->format('Y-m-d');
            }
        }

        $totalWorkingDays = count($workingDays);

        if ($totalWorkingDays === 0) {
            // Edge case: Task scheduled entirely on holidays/weekends.
            // Fallback: Assign all weight to the last day of the task duration (even if it's a holiday)
            // Or better, force the last day to be considered "workable" for the sake of math,
            // but effectively it means the user planned it poorly. 
            // Let's dump it on the end date.
            $endStr = $end->format('Y-m-d');
            if (!isset($dailyWeights[$endStr])) $dailyWeights[$endStr] = 0.0;
            $dailyWeights[$endStr] += $weight;
            return;
        }

        // Calculate Daily Weight
        $dailyWeight = $weight / $totalWorkingDays;
        
        // Track distributed amount for rounding check
        $distributedTotal = 0.0;

        // Distribute to all working days EXCEPT the last one (to handle rounding on the last day)
        for ($i = 0; $i < $totalWorkingDays - 1; $i++) {
            $dateStr = $workingDays[$i];
            
            if (!isset($dailyWeights[$dateStr])) $dailyWeights[$dateStr] = 0.0;
            
            $dailyWeights[$dateStr] += $dailyWeight;
            $distributedTotal += $dailyWeight;
        }

        // Assign remainder to the last working day to ensure total equals exact weight
        $lastDateStr = $workingDays[$totalWorkingDays - 1];
        $remainder = $weight - $distributedTotal;

        if (!isset($dailyWeights[$lastDateStr])) $dailyWeights[$lastDateStr] = 0.0;
        $dailyWeights[$lastDateStr] += $remainder;
    }

    /**
     * Update the actual progress for the project plan based on daily logs.
     * Constraint: Only update entries where period_date <= Today.
     *
     * @param Project $project
     * @return void
     */
    public function updateActualProgress(Project $project): void
    {
        $today = Carbon::now('Asia/Jakarta')->startOfDay(); // Keep consistent timezone

        // 1. Calculate Daily Actuals from Approved Logs
        $logs = \Modules\Operations\Models\DailyLog::query()
            ->whereHas('task', fn($q) => $q->where('project_id', $project->id))
            ->where('approval_status', 'approved')
            ->with(['task:id,weight'])
            ->get();

        $dailyActuals = [];
        foreach ($logs as $log) {
            $date = $log->log_date instanceof Carbon ? $log->log_date->format('Y-m-d') : $log->log_date;
            $weight = (float) ($log->task->weight ?? 0);
            $increment = (float) $log->progress_increment;
            
            // Contribution = (% increment / 100) * Task Weight
            $val = ($increment / 100.0) * $weight;
            
            if (!isset($dailyActuals[$date])) {
                $dailyActuals[$date] = 0.0;
            }
            $dailyActuals[$date] += $val;
        }
        ksort($dailyActuals);

        // 2. Iterate Project Plans and Accumulate
        $plans = ProjectPlan::where('project_id', $project->id)
            ->orderBy('period_date')
            ->get();

        $runningTotal = 0.0;
        $logDates = array_keys($dailyActuals);
        $logIndex = 0;
        $logCount = count($logDates);

        foreach ($plans as $plan) {
            $planDate = $plan->period_date instanceof Carbon 
                ? $plan->period_date 
                : Carbon::parse($plan->period_date);

            // Constraint: Do not project actuals into the future
            if ($planDate->gt($today)) {
                if ($plan->actual_progress !== null) {
                    $plan->actual_progress = null;
                    $plan->saveQuietly();
                }
                continue;
            }

            // Accumulate all logs up to this plan date
            while ($logIndex < $logCount) {
                $logDateStr = $logDates[$logIndex];
                if ($logDateStr <= $planDate->format('Y-m-d')) {
                    $runningTotal += $dailyActuals[$logDateStr];
                    $logIndex++;
                } else {
                    break;
                }
            }
            
            $newActual = min($runningTotal, 100.0);
            
            // Update if changed
            if ($plan->actual_progress === null || abs((float)$plan->actual_progress - $newActual) > 0.001) {
                $plan->actual_progress = round($newActual, 2);
                $plan->saveQuietly();
            }
        }
    }

    /**
     * Check if a date is a working day (Not Weekend AND Not Holiday).
     *
     * @param Carbon $date
     * @return bool
     */
    public function isWorkday(Carbon $date): bool
    {
        if ($date->isWeekend()) {
            return false;
        }

        if (in_array($date->format('Y-m-d'), $this->getHolidays())) {
            return false;
        }

        return true;
    }

    /**
     * Get list of holidays.
     * TODO: In Phase 7/8, migrate this to a database table `holidays`.
     *
     * @return array
     */
    private function getHolidays(): array
    {
        return [
            '2026-01-01', // Tahun Baru 2026 Masehi
            '2026-02-14', // Isra Mikraj Nabi Muhammad SAW
            '2026-02-17', // Tahun Baru Imlek 2577 Kongzili
            '2026-03-19', // Hari Suci Nyepi (Tahun Baru Saka 1948)
            '2026-03-20', // Hari Raya Idul Fitri 1447 Hijriah (Hari 1)
            '2026-03-21', // Hari Raya Idul Fitri 1447 Hijriah (Hari 2)
            '2026-04-03', // Wafat Yesus Kristus
            '2026-04-05', // Hari Paskah
            '2026-05-01', // Hari Buruh Internasional
            '2026-05-14', // Kenaikan Yesus Kristus
            '2026-05-27', // Hari Raya Idul Adha 1447 Hijriah
            '2026-05-31', // Hari Raya Waisak 2570 BE
            '2026-06-01', // Hari Lahir Pancasila
            '2026-06-16', // Tahun Baru Islam 1448 Hijriah
            '2026-08-17', // Hari Kemerdekaan Republik Indonesia
            '2026-08-25', // Maulid Nabi Muhammad SAW
            '2026-12-25', // Hari Raya Natal
        ];
    }
}
