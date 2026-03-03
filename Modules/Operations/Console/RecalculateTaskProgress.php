<?php

namespace Modules\Operations\Console;

use Illuminate\Console\Command;
use Modules\Project\Models\Task;
use Modules\Operations\Models\DailyLog;

class RecalculateTaskProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'operations:recalculate-progress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate total progress for all tasks based on approved daily logs.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting task progress recalculation...');

        $tasks = Task::all();
        $bar = $this->output->createProgressBar($tasks->count());
        $bar->start();

        foreach ($tasks as $task) {
            $total = DailyLog::where('task_id', $task->id)
                ->where('approval_status', 'approved')
                ->sum('progress_increment');

            $task->updateQuietly(['total_progress' => min($total, 100)]);
            
            $bar->advance();
        }

        $bar->finish();
        
        $this->newLine(2);
        $this->info('Recalculating S-Curve Actuals for all projects...');
        $projects = \Modules\Project\Models\Project::all();
        
        $planService = app(\Modules\Reporting\Services\ProjectPlanService::class);
        $bar2 = $this->output->createProgressBar($projects->count());
        $bar2->start();

        foreach ($projects as $project) {
            $planService->updateActualProgress($project);
            $bar2->advance();
        }
        $bar2->finish();

        $this->newLine();
        $this->info('Task progress and S-Curve recalculation completed successfully.');
    }
}
