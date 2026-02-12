<?php

namespace Modules\Reporting\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Modules\Reporting\Services\WorkforceService;
use Modules\Project\Models\Project; // For sector enum potentially, but service handles strings.
// Actually, I should probably use the enum for the dropdown to be safe.
use Modules\Project\Enums\ProjectSector;

class WorkforcePage extends Component
{
    public string $sector = 'All';
    public $projectDetails = [];
    public bool $showModal = false;
    public string $selectedStaffName = '';

    protected $queryString = ['sector'];

    public float $currentStaffCoefficient = 0.0;

    public function updatedSector()
    {
        // Reset or just let re-render happen
    }

    public function openProjectModal(int $userId, string $staffName, WorkforceService $service)
    {
        $this->selectedStaffName = $staffName;
        $this->projectDetails = $service->getProjectBreakdown($userId);
        
        $activeCount = $this->projectDetails->count();
        $this->currentStaffCoefficient = ($activeCount > 0) ? round(1 / $activeCount, 2) : 0.00;
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->projectDetails = [];
    }

    #[Layout('layouts.app')]
    public function render(WorkforceService $service)
    {
        return view('reporting::livewire.workforce-page', [
            'staff' => $service->getStaffUtilization($this->sector),
            'sectors' => ProjectSector::cases(),
        ]);
    }
}
