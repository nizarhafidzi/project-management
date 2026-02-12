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

    public function mount(?int $project = null): void
    {
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

    public function save(): void
    {
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
        ];

        if ($this->isEdit) {
            $project = Project::findOrFail($this->projectId);

            // Validate unique contract_number excluding current
            $this->validate([
                'contract_number' => "unique:projects,contract_number,{$this->projectId}",
            ]);

            $project->update($data);
            session()->flash('message', 'Project updated successfully.');
        } else {
            $this->validate([
                'contract_number' => 'unique:projects,contract_number',
            ]);

            Project::create($data);
            session()->flash('message', 'Project created successfully.');
        }

        $this->redirect(route('project.index'), navigate: true);
    }

    #[\Livewire\Attributes\Layout('layouts.app')]
    public function render()
    {
        return view('project::livewire.project-form', [
            'projectTypes' => ProjectType::cases(),
            'technicalServices' => TechnicalService::cases(),
            'sectors' => ProjectSector::cases(),
            'statuses' => ProjectStatus::cases(),
        ]);
    }
}
