<?php

namespace Modules\Project\Livewire;

use Livewire\Component;
use App\Models\User;
use Modules\Project\Models\Project;

class ProjectShow extends Component
{
    public Project $project;

    public string $searchUser = '';
    public string $selectedUserId = '';
    public string $selectedRole = 'Member';
    public bool $showAssignModal = false;

    public function mount(Project $project): void
    {
        $this->project = $project->load('users');
    }

    public function openAssignModal(): void
    {
        abort_if(auth()->user()->hasRole('Employee'), 403);
        $this->showAssignModal = true;
        $this->searchUser = '';
        $this->selectedUserId = '';
        $this->selectedRole = 'Member';
    }

    public function closeAssignModal(): void
    {
        $this->showAssignModal = false;
    }

    public function assignMember(): void
    {
        abort_if(auth()->user()->hasRole('Employee'), 403);
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'selectedRole' => 'required|in:Manager,Team Leader,Member',
        ]);

        // Check if already assigned
        if ($this->project->users()->where('user_id', $this->selectedUserId)->exists()) {
            session()->flash('error', 'This user is already assigned to the project.');
            return;
        }

        $this->project->users()->attach($this->selectedUserId, [
            'role_in_project' => $this->selectedRole,
        ]);

        $this->project->load('users');
        $this->showAssignModal = false;
        session()->flash('message', 'Member assigned successfully.');
    }

    public function removeMember(int $userId): void
    {
        abort_if(auth()->user()->hasRole('Employee'), 403);
        $this->project->users()->detach($userId);
        $this->project->load('users');
        session()->flash('message', 'Member removed successfully.');
    }

    public function getAvailableUsersProperty()
    {
        $assignedIds = $this->project->users->pluck('id')->toArray();

        return User::query()
            ->whereNotIn('id', $assignedIds)
            ->when($this->searchUser, fn ($q) => $q->where('name', 'like', "%{$this->searchUser}%"))
            ->limit(10)
            ->get();
    }

    #[\Livewire\Attributes\Layout('layouts.app')]
    public function render()
    {
        return view('project::livewire.project-show');
    }
}
