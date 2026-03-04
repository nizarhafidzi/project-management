<?php

namespace Modules\Project\Livewire;

use Livewire\Component;
use Modules\Project\Models\Project;
use Modules\Project\Models\Task;
use App\Services\AutodeskService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TaskWorkspace extends Component
{
    public Task $task;
    public Project $project;
    public $token;

    public function mount(Project $project, Task $task, AutodeskService $autodeskService)
    {
        $this->project = $project;
        $this->task = $task->load('user');

        // Access Control: Valid Member or Manager+
        $user = auth()->user();
        // Check if user is attached to the project
        $isMember = $project->users()->where('user_id', $user->id)->exists();
        
        if (!$isMember && !$user->hasRole(['Superadmin', 'Manager', 'Team Leader', 'Employee'])) {
            abort(403, 'You do not have access to this project workspace.');
        }

        // Fetch System Token for Viewer
        // Fetch Token for Viewer (Try User Token first, then System Token)
        try {
            // Use getValidViewerToken which handles the fallback logic
            $this->token = $autodeskService->getValidViewerToken(auth()->user());
        } catch (\Exception $e) {
            $this->token = null;
            session()->flash('error', 'Autodesk Service is unavailable: ' . $e->getMessage());
        }
    }

    #[Computed]
    public function logs()
    {
        return $this->task->dailyLogs()
            ->with('user')
            ->orderBy('log_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    #[Computed]
    public function totalManHours()
    {
        $hours = $this->logs()->reduce(function ($carry, $log) {
            return $carry + $log->man_hours; // Uses safe accessor with cross-midnight handling
        }, 0);

        return round($hours, 2);
    }

    #[Computed]
    public function viewerApi()
    {
        // Default to US/Global unless we have a specific reason to use EU.
        // urn:adsk.wipp matches both US and EU, so it's not a good indicator for EU-only.
        return 'derivativeV2';
    }

    #[Computed]
    public function viewerUrn()
    {
        if (!$this->task->acc_file_urn) {
            return null;
        }
        // Base64 encode, make URL-safe, and remove padding '='
        return rtrim(strtr(base64_encode($this->task->acc_file_urn), '+/', '-_'), '=');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('project::livewire.task-workspace');
    }
}
