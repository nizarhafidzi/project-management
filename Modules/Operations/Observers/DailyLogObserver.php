<?php

namespace Modules\Operations\Observers;

use Modules\Operations\Models\DailyLog;

class DailyLogObserver
{
    /**
     * Handle the DailyLog "created" event.
     */
    public function created(DailyLog $dailyLog): void
    {
        $this->updateTaskProgress($dailyLog);
    }

    /**
     * Handle the DailyLog "updated" event.
     */
    public function updated(DailyLog $dailyLog): void
    {
        // Only update if relevant fields changed
        if ($dailyLog->isDirty(['progress_increment', 'approval_status', 'task_id'])) {
            $this->updateTaskProgress($dailyLog);

            // If task_id changed, we need to update the old task as well
            if ($dailyLog->isDirty('task_id')) {
                $originalTaskId = $dailyLog->getOriginal('task_id');
                if ($originalTaskId) {
                    $originalTask = \Modules\Project\Models\Task::find($originalTaskId);
                    if ($originalTask) {
                        // Create a temporary log instance with the old task id to trigger update on it
                        // Or better, just manually call calculation logic for the old task.
                        // Since updateTaskProgress takes a DailyLog, we can't easily pass the old task
                        // unless we fetch a log from it or refactor.
                        // Let's refactor updateTaskProgress to accept a Task ID or Task object optionally?
                        // Actually, easiest is to just find the old task and run the sum query for it.
                        
                        $total = DailyLog::where('task_id', $originalTaskId)
                            ->where('approval_status', 'approved')
                            ->sum('progress_increment');

                        $originalTask->updateQuietly(['total_progress' => min($total, 100)]);
                    }
                }
            }
        }
    }

    /**
     * Handle the DailyLog "deleted" event.
     */
    public function deleted(DailyLog $dailyLog): void
    {
        $this->updateTaskProgress($dailyLog);
    }

    /**
     * Handle the DailyLog "restored" event.
     */
    public function restored(DailyLog $dailyLog): void
    {
        $this->updateTaskProgress($dailyLog);
    }

    /**
     * Calculate and update the task's total progress.
     * Delegates to the Task model's recalculateProgress() which is the
     * single source of truth for progress sum + status synchronisation.
     */
    private function updateTaskProgress(DailyLog $dailyLog): void
    {
        $task = $dailyLog->task;

        if ($task) {
            // 1. Recalculate progress from approved logs + sync status
            $task->recalculateProgress();

            // 2. Update Project Plan S-Curve Actuals
            $project = $task->project;
            if ($project) {
                // We use app() to resolve the service since we are in an Observer
                $planService = app(\Modules\Reporting\Services\ProjectPlanService::class);
                $planService->updateActualProgress($project);
            }
        }
    }
}
