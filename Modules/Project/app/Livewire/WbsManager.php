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

    // Bulk state
    public array $selectedTasks = [];
    public array $bulkAssignUsers = [];
    public bool $showBulkAssignModal = false;

    // Form fields
    public ?int $formParentId = null;
    public string $formName = '';
    public string $formWeight = '';
    public string $formStartDate = '';
    public string $formEndDate = '';
    public string $formAccFileName = '';
    public string $formWbsCode = '';
    public $formAssignedUsers = [];

    // Data
    public $projectMembers = [];

    // Computed display
    public string $parentLabel = '';

    // Access control
    public bool $canManage = false;

    // ACC Integration
    public bool $showFilePicker = false;
    public string $formAccFileUrn = '';
    public ?int $formAccFileVersion = null;

    // Bulk Check State
    public array $accUpdateQueue = [];
    public bool $isCheckingUpdates = false;
    public int $checkedCount = 0;
    public int $totalEffectiveCount = 0;

    protected $listeners = [
        'file-selected' => 'handleFileSelected',
        'check-next-version' => 'processNextVersionCheck'
    ];

    protected function rules(): array
    {
        return [
            'formName' => 'required|string|max:255',
            'formWeight' => 'required|numeric|min:0|max:100',
            'formStartDate' => 'required|date',
            'formEndDate' => 'required|date|after_or_equal:formStartDate',
            'formAccFileName' => 'nullable|string|max:255',
            'formAccFileUrn' => 'nullable|string',
            'formAccFileVersion' => 'nullable|integer',
            'formAssignedUsers' => 'nullable|array',
            'formAssignedUsers.*' => 'exists:users,id',
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
    // ACC File Picker & Integration
    // ──────────────────────────────────────────────

    public function openFilePicker()
    {
        abort_if(!$this->canManage, 403);
        
        if (!$this->project->acc_project_id) {
            $this->dispatch('notify', type: 'error', content: 'This project is not linked to ACC.');
            return;
        }

        $this->showFilePicker = true;
        // Props handling initialization now
        // $this->dispatch('trigger-file-picker', ...);
    }

    public function closeFilePicker()
    {
        $this->showFilePicker = false;
    }

    public function handleFileSelected($urn, $name)
    {
        $this->formAccFileName = $name;
        $this->formAccFileUrn = $urn;
        
        // Auto-overwrite Name
        // Remove extension (e.g. .rvt, .dwg)
        $cleanName = preg_replace('/\.[^.]+$/', '', $name);
        $this->formName = $cleanName;

        // Try to fetch initial version info immediately? 
        // Or leave it null and let "Check Updates" fill it?
        // Let's try to fetch it to have a baseline.
        $service = app(\App\Services\AutodeskService::class);
        $versionInfo = $service->checkFileVersion(Auth::user(), $this->project->acc_project_id, $urn);
        
        if ($versionInfo) {
            $this->formAccFileVersion = $versionInfo['version'];
        } else {
            $this->formAccFileVersion = 1; // Default fallback
        }

        $this->closeFilePicker();
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
        $this->formAccFileUrn = $task->acc_file_urn ?? '';
        $this->formAccFileVersion = $task->acc_file_version;
        $this->formAssignedUsers = $task->users->pluck('id')->toArray();

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
        $this->formAccFileUrn = '';
        $this->formAccFileVersion = null;
        $this->formWbsCode = '';
        $this->formAssignedUsers = [];
        $this->formParentId = null;
        $this->editingTaskId = null;
        $this->parentLabel = '';
        $this->resetValidation();
    }

    // ──────────────────────────────────────────────
    // Bulk Assignment
    // ──────────────────────────────────────────────

    public function openBulkAssignModal(): void
    {
        abort_if(!$this->canManage, 403);
        
        if (empty($this->selectedTasks)) {
            $this->dispatch('notify', type: 'error', content: 'No tasks selected.');
            return;
        }
        
        $this->bulkAssignUsers = [];
        $this->showBulkAssignModal = true;
    }

    public function closeBulkAssignModal(): void
    {
        $this->showBulkAssignModal = false;
        $this->bulkAssignUsers = [];
    }

    public function applyBulkAssign(): void
    {
        abort_if(!$this->canManage, 403);
        
        $this->validate([
            'bulkAssignUsers' => 'nullable|array',
            'bulkAssignUsers.*' => 'exists:users,id',
        ]);

        if (empty($this->selectedTasks)) {
            $this->dispatch('notify', type: 'error', content: 'No tasks selected.');
            $this->showBulkAssignModal = false;
            return;
        }

        foreach ($this->selectedTasks as $taskId) {
            $task = Task::find($taskId);
            if ($task) {
                // syncWithoutDetaching attaches users without removing existing
                $task->users()->syncWithoutDetaching($this->bulkAssignUsers);
            }
        }

        session()->flash('message', 'Bulk assignment applied successfully to ' . count($this->selectedTasks) . ' tasks.');
        
        $this->selectedTasks = [];
        $this->bulkAssignUsers = [];
        $this->showBulkAssignModal = false;

        $this->project->refresh();
    }

    // ──────────────────────────────────────────────
    // Save Task
    // ──────────────────────────────────────────────

    public function saveTask(): void
    {
        abort_if(!$this->canManage, 403);
        $this->validate();

        \Illuminate\Support\Facades\Log::info('Submitting WBS Task', [
            'form' => [
                'name' => $this->formName,
                'urn' => $this->formAccFileUrn,
                'version' => $this->formAccFileVersion
            ]
        ]);

        try {
            if ($this->isEditing) {
                $task = Task::findOrFail($this->editingTaskId);
                $task->update([
                    'name' => $this->formName,
                    'weight' => (float) $this->formWeight,
                    'start_date' => $this->formStartDate,
                    'end_date' => $this->formEndDate,
                    'acc_file_name' => $this->formAccFileName ?: null,
                    'acc_file_urn' => $this->formAccFileUrn ?: null,
                    'acc_file_version' => $this->formAccFileVersion,
                ]);
                $task->users()->sync($this->formAssignedUsers);

                session()->flash('message', 'Task updated successfully.');
            } else {
                $sortOrder = Task::where('project_id', $this->project->id)
                    ->where('parent_id', $this->formParentId)
                    ->max('sort_order') ?? 0;

                $task = Task::create([
                    'project_id' => $this->project->id,
                    'parent_id' => $this->formParentId,
                    'wbs_code' => $this->formWbsCode,
                    'name' => $this->formName,
                    'weight' => (float) $this->formWeight,
                    'start_date' => $this->formStartDate,
                    'end_date' => $this->formEndDate,
                    'acc_file_name' => $this->formAccFileName ?: null,
                    'acc_file_urn' => $this->formAccFileUrn ?: null,
                    'acc_file_version' => $this->formAccFileVersion,
                    'sort_order' => $sortOrder + 1,
                ]);
                $task->users()->sync($this->formAssignedUsers);

                // Auto-expand parent to show new child
                if ($this->formParentId && !in_array($this->formParentId, $this->expandedNodes)) {
                    $this->expandedNodes[] = $this->formParentId;
                }

                session()->flash('message', 'Task created successfully.');
            }
            \Illuminate\Support\Facades\Log::info('WBS Save Success');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('WBS Save Failed: ' . $e->getMessage());
            $this->dispatch('notify', type: 'error', content: 'Save Failed: ' . $e->getMessage());
            throw $e; // Re-throw to see if Livewire catches it
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
    // Version Sync Logic
    // ──────────────────────────────────────────────

    public function checkForUpdates()
    {
        abort_if(!$this->canManage, 403);
        
        $tasksToUpdate = $this->project->tasks()
            ->whereNotNull('acc_file_urn')
            ->pluck('id')
            ->toArray();
            
        if (empty($tasksToUpdate)) {
            $this->dispatch('notify', type: 'info', content: 'No tasks linked to ACC files.');
            return;
        }

        $this->accUpdateQueue = $tasksToUpdate;
        $this->isCheckingUpdates = true;
        $this->checkedCount = 0;
        
        // Start the loop
        $this->dispatch('check-next-version');
    }

    public function processNextVersionCheck()
    {
        if (empty($this->accUpdateQueue)) {
            $this->isCheckingUpdates = false;
            $this->dispatch('notify', type: 'success', content: 'BIM Updates check complete.');
            $this->project->refresh();
            return;
        }

        $taskId = array_shift($this->accUpdateQueue);
        $task = Task::find($taskId);

        if ($task && $task->acc_file_urn) {
            $service = app(\App\Services\AutodeskService::class);
            $info = $service->checkFileVersion(Auth::user(), $this->project->acc_project_id, $task->acc_file_urn);
            
            if ($info && isset($info['version'])) {
                $task->update([
                    'acc_latest_version' => $info['version'],
                    'acc_last_synced_at' => now(),
                ]);
            }
        }

        $this->checkedCount++;
        
        // Continue loop
        $this->dispatch('check-next-version');
    }

    public function refreshVersion($taskId)
    {
        abort_if(!$this->canManage, 403);
        $task = Task::findOrFail($taskId);
        
        if ($task->acc_latest_version) {
            $task->update([
                'acc_file_version' => $task->acc_latest_version
            ]);
            $this->dispatch('notify', type: 'success', content: 'Task updated to latest version.');
        }
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

    public function exportWbs()
    {
        abort_if(!auth()->user()->hasAnyRole(['Superadmin', 'Manager', 'Team Leader']), 403);

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \Modules\Project\Exports\WbsExport($this->project->id),
            $this->project->project_code . '_WBS.xlsx'
        );
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
                ->whereHas('users', fn($q) => $q->where('users.id', $user->id))
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
