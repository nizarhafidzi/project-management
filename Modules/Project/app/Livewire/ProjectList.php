<?php

namespace Modules\Project\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Project\Models\Project;

class ProjectList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public string $filterSector = '';
    public bool $showDeleteModal = false;
    public ?int $deletingProjectId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterSector' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFilterSector(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $projectId): void
    {
        $this->deletingProjectId = $projectId;
        $this->showDeleteModal = true;
    }

    public function deleteProject(): void
    {
        if ($this->deletingProjectId) {
            Project::findOrFail($this->deletingProjectId)->delete();
            session()->flash('message', 'Project deleted successfully.');
        }

        $this->showDeleteModal = false;
        $this->deletingProjectId = null;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingProjectId = null;
    }

    #[\Livewire\Attributes\Layout('layouts.app')]
    public function render()
    {
        \Illuminate\Support\Facades\Gate::authorize('viewAny', Project::class);

        $user = \Illuminate\Support\Facades\Auth::user();

        $projects = Project::query()
            ->when(!$user->hasAnyRole(['Superadmin', 'Manager']), function ($q) use ($user) {
                // Team Leaders and Employees can only see projects they belong to
                $q->whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                });
            })
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('project_code', 'like', "%{$this->search}%")
                  ->orWhere('contract_number', 'like', "%{$this->search}%");
            }))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterSector, fn ($q) => $q->where('sector', $this->filterSector))
            ->latest()
            ->paginate(10);

        return view('project::livewire.project-list', [
            'projects' => $projects,
        ]);
    }
}
