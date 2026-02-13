<?php

namespace Modules\Project\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Modules\Project\Enums\ProjectType;
use Modules\Project\Enums\TechnicalService;
use Modules\Project\Enums\ProjectSector;
use Modules\Project\Enums\ProjectStatus;
use Modules\Project\Models\Project;

class ProjectForm extends Component
{
    public ?int $projectId = null;

    #[Validate('required|in:Internal,External')]
    public string $project_type = '';

    #[Validate('required|string|max:255')]
    public string $contract_number = '';

    public string $project_code = '';

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|in:Engineering,BIM')]
    public string $technical_service = '';

    #[Validate('required|in:Building,Water Resources,Infrastructure,Energy')]
    public string $sector = '';

    #[Validate('required|in:Active,Completed,On-Hold')]
    public string $status = 'Active';

    public bool $isEdit = false;

    // ... (existing properties)
    #[Validate('nullable|date')]
    public $start_date = null;

    #[Validate('nullable|date|after_or_equal:start_date')]
    public $end_date = null;

    // ACC Integration Properties
    public bool $integrateWithAcc = false;
    public string $accCreationMode = 'existing'; // 'existing' or 'new'
    public array $hubs = [];
    public array $accProjects = []; // Renamed from $projects to avoid conflict with $project model
    
    #[Validate('required_if:integrateWithAcc,true')]
    public $selectedHub = '';
    
    #[Validate('required_if:accCreationMode,existing')]
    public $selectedAccProject = '';

    public function mount(?int $project = null): void
    {
        \Illuminate\Support\Facades\Log::info('ProjectForm::mount called', ['project_id' => $project]);

        if ($project) {
            $this->projectId = $project;
            $this->isEdit = true;

            $p = Project::findOrFail($project);
            $this->project_type = $p->project_type->value;
            $this->contract_number = $p->contract_number;
            $this->project_code = $p->project_code;
            $this->name = $p->name;
            $this->technical_service = $p->technical_service->value;
            $this->sector = $p->sector->value;
            $this->status = $p->status->value;
            $this->start_date = $p->start_date?->format('Y-m-d');
            $this->end_date = $p->end_date?->format('Y-m-d');
            
            // Should we load existing binding info? 
            // For now, let's assume we are mostly creating new bindings on create.
            // But if edited, we might want to show if it's bound.
            if ($p->acc_project_id) {
                $this->integrateWithAcc = true;
                $this->selectedHub = $p->acc_account_id;
                $this->selectedAccProject = $p->acc_project_id;
                $this->accCreationMode = 'existing'; // Default to showing it as linked
            }
        }
        
        // Load Hubs if user is connected
        if (\Illuminate\Support\Facades\Auth::check()) {
            $apsService = app(\App\Services\AutodeskService::class);
            try {
                // Try to get token to see if connected
                if ($apsService->getUserToken(\Illuminate\Support\Facades\Auth::user())) {
                    $this->hubs = $apsService->getHubs(\Illuminate\Support\Facades\Auth::user());
                }
            } catch (\Exception $e) {
                // Not connected or token expired
                $this->hubs = [];
            }
        }
    }

    public function updatedSelectedHub()
    {
        if ($this->selectedHub) {
            $apsService = app(\App\Services\AutodeskService::class);
            $this->accProjects = $apsService->getProjects(\Illuminate\Support\Facades\Auth::user(), $this->selectedHub);
        } else {
            $this->accProjects = [];
        }
    }

    /**
     * Reactively generate project code when type or contract number changes.
     */
    public function updated($property): void
    {
        if (in_array($property, ['project_type', 'contract_number'])) {
            $this->generateProjectCode();
        }
    }

    public function generateProjectCode(): void
    {
        if (empty($this->project_type)) {
            $this->project_code = '';
            return;
        }

        $type = ProjectType::from($this->project_type);
        $this->project_code = Project::generateProjectCode($type, $this->contract_number);
    }

    public function save(\App\Services\AutodeskService $apsService): void
    {
        \Illuminate\Support\Facades\Log::info('ProjectForm::save called', ['user_id' => \Illuminate\Support\Facades\Auth::id(), 'integrate' => $this->integrateWithAcc]);
        
        $this->validate();

        // Generate code if not already set
        if (empty($this->project_code)) {
            $this->generateProjectCode();
        }

        $data = [
            'project_type' => $this->project_type,
            'contract_number' => $this->contract_number,
            'project_code' => $this->project_code,
            'name' => $this->name,
            'technical_service' => $this->technical_service,
            'sector' => $this->sector,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];

        // 1. Save Local Project First
        if ($this->isEdit) {
            $project = Project::findOrFail($this->projectId);
            
            $this->validate([
                'contract_number' => "unique:projects,contract_number,{$this->projectId}",
            ]);

            $project->update($data);
            session()->flash('message', 'Project updated successfully.');
        } else {
            $this->validate([
                'contract_number' => 'unique:projects,contract_number',
            ]);

            $project = Project::create($data);
            session()->flash('message', 'Project created successfully.');
        }

        // 2. Handle ACC Integration
        // 2. Handle ACC Integration
        // 2. Handle ACC Integration
        if ($this->integrateWithAcc) {
            \Illuminate\Support\Facades\Log::info('ACC Integration Started', [
                'mode' => $this->accCreationMode,
                'hub' => $this->selectedHub,
                'user_id' => \Illuminate\Support\Facades\Auth::id()
            ]);

            try {
                $finalAccProjectId = $this->selectedAccProject;
                $finalAccAccountId = $this->selectedHub;

                if ($this->accCreationMode === 'new') {
                    // Create New Project in ACC
                    $projectData = [
                        'name' => $this->name, // Use local name
                        'start_date' => $this->start_date,
                        'end_date' => $this->end_date,
                        'type' => 'construction-management'
                    ];

                    $cleanAccountId = \Illuminate\Support\Str::replaceFirst('b.', '', $this->selectedHub);
                    
                    \Illuminate\Support\Facades\Log::info('Creating ACC Project', ['account_id' => $cleanAccountId, 'data' => $projectData]);

                    $newAccProject = $apsService->createProject(\Illuminate\Support\Facades\Auth::user(), $cleanAccountId, $projectData);
                    $finalAccProjectId = $newAccProject['id'];
                    
                    \Illuminate\Support\Facades\Log::info('ACC Project Created. ID: ' . $finalAccProjectId);
                }

                // Update Local Project with Binding
                $project->update([
                    'acc_account_id' => $finalAccAccountId,
                    'acc_project_id' => $finalAccProjectId,
                ]);
                
                \Illuminate\Support\Facades\Log::info('Local Project Updated with ACC IDs');

            } catch (\Exception $e) {
                // Log and Flash Error
                session()->flash('error', 'Project created locally, but ACC Integration failed: ' . $e->getMessage());
                \Illuminate\Support\Facades\Log::error('ACC Integration Failed', ['error' => $e->getMessage()]);
                
                // Optional: If new project creation failed after partial success (e.g. created but not active), 
                // we might want to store the partial ID or let the user retry binding later.
                // For now, leaving columns null as per "Local Database Cleanup" request (implied "ensure if successful... saved", so if not, don't save).
            }
        } else {
             \Illuminate\Support\Facades\Log::info('ACC Integration Skipped (Checkbox not checked)');
        }

        $this->redirect(route('project.index'), navigate: true);
    }

    #[\Livewire\Attributes\Layout('layouts.app')]
    public function render()
    {
        return view('project::livewire.project-form', [
            'isEdit' => $this->isEdit,
            'integrateWithAcc' => $this->integrateWithAcc,
            'accCreationMode' => $this->accCreationMode,
            'hubs' => $this->hubs,
            'accProjects' => $this->accProjects,
            'projectTypes' => ProjectType::cases(),
            'technicalServices' => TechnicalService::cases(),
            'sectors' => ProjectSector::cases(),
            'statuses' => ProjectStatus::cases(),
        ]);
    }
}
