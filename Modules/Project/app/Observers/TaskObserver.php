<?php

namespace Modules\Project\Observers;

use Modules\Project\Models\Task;
use Modules\Reporting\Services\ProjectPlanService;

class TaskObserver
{
    protected $projectPlanService;

    public function __construct(ProjectPlanService $projectPlanService)
    {
        $this->projectPlanService = $projectPlanService;
    }

    /**
     * Handle the Task "saved" event.
     */
    public function saved(Task $task): void
    {
        // Only trigger if weight, start_date, or end_date changed
        if ($task->wasChanged(['weight', 'start_date', 'end_date'])) {
            $this->projectPlanService->generateProjectPlan($task->project);
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        $this->projectPlanService->generateProjectPlan($task->project);
    }
}
