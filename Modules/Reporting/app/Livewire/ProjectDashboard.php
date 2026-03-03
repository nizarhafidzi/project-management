<?php

namespace Modules\Reporting\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Modules\Project\Models\Project;
use Modules\Operations\Models\DailyLog;

class ProjectDashboard extends Component
{
    public Project $project;
    public float $totalProgress = 0;

    public function mount(Project $project): void
    {
        $this->project = $project->load(['users', 'rootTasks.children']);
        $this->totalProgress = $project->calculateTotalProgress();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $activities = DailyLog::with(['user', 'task'])
            ->whereHas('task', function ($q) {
                $q->where('project_id', $this->project->id);
            })
            ->latest('log_date')
            ->latest('created_at')
            ->take(10)
            ->get();

        return view('reporting::livewire.project-dashboard', [
            'members'    => $this->project->users,
            'activities' => $activities,
        ]);
    }
}
