<?php

namespace Modules\Project\Livewire;

use Livewire\Component;
use Modules\Project\Models\Project;
use Modules\Project\Models\Task;
use Illuminate\Support\Facades\Auth;

class WbsManager extends Component
{
    public Project $project;

    // Tree state
    public array $expandedNodes = [];

    // Modal state
    public bool $showTaskModal = false;
    public bool $isEditing = false;
    public ?int $editingTaskId = null;

    // Form fields
    public ?int $formParentId = null;
    public string $formName = '';
    public string $formWeight = '';
    public string $formStartDate = '';
    public string $formEndDate = '';
    public string $formAccFileName = '';
    public string $formWbsCode = '';
    public $formAssignedTo = '';

    // Data
    public $projectMembers = [];

    // Computed display
    public string $parentLabel = '';

    // Access control
    public bool $canManage = false;

    protected function rules(): array
    {
        return [
            'formName' => 'required|string|max:255',
            'formWeight' => 'required|numeric|min:0|max:100',
            'formStartDate' => 'required|date',
            'formEndDate' => 'required|date|after_or_equal:formStartDate',
            'formAccFileName' => 'nullable|string|max:255',
            'formAssignedTo' => 'nullable|exists:users,id',
        ];
    }

    protected $messages = [
        'formName.required' => 'Task name is required.',
        'formWeight.required' => 'Weight is required.',
        'formWeight.numeric' => 'Weight must be a number.',
        'formWeight.min' => 'Weight must be at least 0.',
        'formWeight.max' => 'Weight cannot exceed 100.',
        'formStartDate.required' => 'Start date is required.',
        'formEndDate.required' => 'End date is required.',
        'formEndDate.after_or_equal' => 'End date must be after or equal to start date.',
    ];

    public function mount(Project $project): void
    {
        $this->project = $project;
        $this->checkAccess();
        $this->projectMembers = $this->project->users()->orderBy('name')->get();
    }

    private function checkAccess(): void
    {
        $user = Auth::user();

        // Superadmin always has access
        if ($user->hasRole('Superadmin')) {
            $this->canManage = true;
            return;
        }

        // Check project-level role
        $pivot = $this->project->users()
            ->where('user_id', $user->id)
            ->first();

        if ($pivot) {
            $role = $pivot->pivot->role_in_project;
            $this->canManage = in_array($role, ['Manager', 'Team Leader']);
        }
    }

    // ──────────────────────────────────────────────
    // Tree Controls
    // ──────────────────────────────────────────────

    public function toggleNode(int $taskId): void
    {
        if (in_array($taskId, $this->expandedNodes)) {
            $this->expandedNodes = array_values(array_diff($this->expandedNodes, [$taskId]));
        } else {
            $this->expandedNodes[] = $taskId;
        }
    }

    public function expandAll(): void
    {
        $this->expandedNodes = $this->project->tasks()->pluck('id')->toArray();
    }

    public function collapseAll(): void
    {
        $this->expandedNodes = [];
    }

    // ──────────────────────────────────────────────
    // Create / Edit Modal
    // ──────────────────────────────────────────────

    public function openCreateModal(?int $parentId = null): void
    {
        abort_if(!$this->canManage, 403);
        $this->resetForm();
        $this->isEditing = false;
        $this->formParentId = $parentId;
        $this->formWbsCode = Task::generateNextWbsCode($this->project->id, $parentId);

        if ($parentId) {
            $parent = Task::find($parentId);
            $this->parentLabel = $parent ? "{$parent->wbs_code} - {$parent->name}" : '';
            // Default dates from parent
            $this->formStartDate = $parent->start_date->format('Y-m-d');
            $this->formEndDate = $parent->end_date->format('Y-m-d');
        } else {
            $this->parentLabel = 'Root Level';
        }

        $this->showTaskModal = true;
    }

    public function openEditModal(int $taskId): void
    {
        abort_if(!$this->canManage, 403);
        $this->resetForm();
        $task = Task::findOrFail($taskId);

        $this->isEditing = true;
        $this->editingTaskId = $taskId;
        $this->formParentId = $task->parent_id;
        $this->formWbsCode = $task->wbs_code;
        $this->formName = $task->name;
        $this->formWeight = (string) $task->weight;
        $this->formStartDate = $task->start_date->format('Y-m-d');
        $this->formEndDate = $task->end_date->format('Y-m-d');
        $this->formAccFileName = $task->acc_file_name ?? '';
        $this->formAssignedTo = $task->user_id;

        if ($task->parent_id) {
            $parent = $task->parent;
            $this->parentLabel = "{$parent->wbs_code} - {$parent->name}";
        } else {
            $this->parentLabel = 'Root Level';
        }

        $this->showTaskModal = true;
    }

    public function closeModal(): void
    {
        $this->showTaskModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->formName = '';
        $this->formWeight = '';
        $this->formStartDate = '';
        $this->formEndDate = '';
        $this->formEndDate = '';
        $this->formAccFileName = '';
        $this->formWbsCode = '';
        $this->formAssignedTo = '';
        $this->formParentId = null;
        $this->editingTaskId = null;
        $this->parentLabel = '';
        $this->resetValidation();
    }

    // ──────────────────────────────────────────────
    // Save Task
    // ──────────────────────────────────────────────

    public function saveTask(): void
    {
        abort_if(!$this->canManage, 403);
        $this->validate();

        if ($this->isEditing) {
            $task = Task::findOrFail($this->editingTaskId);
            $task->update([
                'name' => $this->formName,
                'weight' => (float) $this->formWeight,
                'start_date' => $this->formStartDate,
                'end_date' => $this->formEndDate,
                'acc_file_name' => $this->formAccFileName ?: null,
                'user_id' => $this->formAssignedTo ?: null,
            ]);

            session()->flash('message', 'Task updated successfully.');
        } else {
            $sortOrder = Task::where('project_id', $this->project->id)
                ->where('parent_id', $this->formParentId)
                ->max('sort_order') ?? 0;

            Task::create([
                'project_id' => $this->project->id,
                'parent_id' => $this->formParentId,
                'wbs_code' => $this->formWbsCode,
                'name' => $this->formName,
                'weight' => (float) $this->formWeight,
                'start_date' => $this->formStartDate,
                'end_date' => $this->formEndDate,
                'acc_file_name' => $this->formAccFileName ?: null,
                'user_id' => $this->formAssignedTo ?: null,
                'sort_order' => $sortOrder + 1,
            ]);

            // Auto-expand parent to show new child
            if ($this->formParentId && !in_array($this->formParentId, $this->expandedNodes)) {
                $this->expandedNodes[] = $this->formParentId;
            }

            session()->flash('message', 'Task created successfully.');
        }

        $this->closeModal();
        $this->project->refresh();
    }

    // ──────────────────────────────────────────────
    // Delete Task
    // ──────────────────────────────────────────────

    public function deleteTask(int $taskId): void
    {
        abort_if(!$this->canManage, 403);
        $task = Task::findOrFail($taskId);

        // Check if it has children
        $childCount = $task->children()->count();
        $taskName = $task->name;

        $task->delete();

        $message = "Task \"{$taskName}\" deleted successfully.";
        if ($childCount > 0) {
            $message .= " ({$childCount} sub-task(s) also removed)";
        }

        session()->flash('message', $message);
        $this->project->refresh();
    }

    // ──────────────────────────────────────────────
    // S-Curve Sync
    // ──────────────────────────────────────────────

    public function recalculateSCurve(\Modules\Reporting\Services\ProjectPlanService $service): void
    {
        abort_if(!$this->canManage, 403);
        try {
            $service->generateProjectPlan($this->project);
            
            $this->dispatch('project-plan-updated', projectId: $this->project->id);
            $this->dispatch('notify', 
                type: 'success', 
                content: 'S-Curve successfully synchronized!'
            );
        } catch (\Exception $e) {
            $this->dispatch('notify', 
                type: 'error', 
                content: 'Failed to synchronize S-Curve: ' . $e->getMessage()
            );
        }
    }

    // ──────────────────────────────────────────────
    // Weight Validation Helpers (for the view)
    // ──────────────────────────────────────────────

    public function getWeightInfo(?int $parentId = null): array
    {
        $sum = Task::getSiblingWeightSum($this->project->id, $parentId);
        $expected = Task::getExpectedWeight($this->project->id, $parentId);
        $isValid = abs($sum - $expected) < 0.01;

        return [
            'sum' => round($sum, 2),
            'expected' => round($expected, 2),
            'is_valid' => $isValid,
        ];
    }

    use \Livewire\WithFileUploads;

    public $inputTemplate;

    public function downloadTemplate()
    {
        $service = new \Modules\Project\Services\TaskImportService(
            $this->project->id, 
            app(\Modules\Reporting\Services\ProjectPlanService::class)
        );
        return $service->downloadTemplate();
    }

    public function importExcel()
    {
        abort_if(!$this->canManage, 403);
        
        $this->validate([
            'inputTemplate' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
        ]);

        try {
            $service = new \Modules\Project\Services\TaskImportService(
                $this->project->id, 
                app(\Modules\Reporting\Services\ProjectPlanService::class)
            );

            $stats = $service->import($this->inputTemplate);
            
            $this->dispatch('notify', 
                type: 'success', 
                content: "Import Successful: {$stats['created']} created, {$stats['updated']} updated, {$stats['missing_emails']} missing emails."
            );

            $this->inputTemplate = null; // Reset file input
            $this->project->refresh();
        } catch (\Exception $e) {
            $this->dispatch('notify', 
                type: 'error', 
                content: 'Import Failed: ' . $e->getMessage()
            );
        }
    }

    // ──────────────────────────────────────────────
    // Render
    // ──────────────────────────────────────────────

    #[\Livewire\Attributes\Layout('layouts.app')]
    public function render()
    {
        $user = Auth::user();

        if ($user->hasRole('Employee')) {
            $rootTasks = Task::where('project_id', $this->project->id)
                ->where('user_id', $user->id)
                ->get();
                
            $weightInfo = ['sum' => 0, 'expected' => 0, 'is_valid' => true];
        } else {
            $rootTasks = $this->project->rootTasks()->with('childrenRecursive')->get();
            $weightInfo = $this->getWeightInfo(null);
        }

        return view('project::livewire.wbs-manager', [
            'rootTasks' => $rootTasks,
            'rootWeightInfo' => $weightInfo,
        ]);
    }
}
