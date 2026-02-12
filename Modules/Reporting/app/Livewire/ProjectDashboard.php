<?php

namespace Modules\Reporting\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Modules\Project\Models\Project;

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
        return view('reporting::livewire.project-dashboard', [
            'members' => $this->project->users,
        ]);
    }
}
