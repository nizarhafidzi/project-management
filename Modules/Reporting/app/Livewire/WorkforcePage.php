<?php

namespace Modules\Reporting\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Modules\Reporting\Services\WorkforceService;
use Modules\Project\Models\Project; 
use Modules\Project\Enums\ProjectSector;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Reporting\Exports\WorkforceCoefficientExport;

class WorkforcePage extends Component
{
    public string $sector = 'All';
    public $projectDetails = [];
    public bool $showModal = false;
    public string $selectedStaffName = '';

    public $selectedMonth;
    public $selectedYear;

    protected $queryString = ['sector', 'selectedMonth', 'selectedYear'];

    public float $currentStaffCoefficient = 0.0;

    public function mount()
    {
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
    }

    public function updatedSector()
    {
        // Reset or just let re-render happen
    }

    public function updatedSelectedMonth()
    {
        $this->dispatch('update-analytics-chart', data: ['month' => $this->selectedMonth, 'year' => $this->selectedYear]);
    }

    public function updatedSelectedYear()
    {
        $this->dispatch('update-analytics-chart', data: ['month' => $this->selectedMonth, 'year' => $this->selectedYear]);
    }

    public function exportExcel()
    {
        $monthName = $this->selectedMonth ? \DateTime::createFromFormat('!m', $this->selectedMonth)->format('F') : 'All';
        $yearName = $this->selectedYear ?: 'All';
        
        $fileName = "Workforce_Coefficient_{$monthName}_{$yearName}.xlsx";

        return Excel::download(new WorkforceCoefficientExport($this->selectedMonth, $this->selectedYear, $this->sector), $fileName);
    }

    public function openProjectModal(int $userId, string $staffName, WorkforceService $service)
    {
        $this->selectedStaffName = $staffName;
        // Pass month and year to service
        $this->projectDetails = $service->getProjectBreakdown($userId, $this->selectedMonth, $this->selectedYear);
        
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
            'staff' => $service->getStaffUtilization($this->sector, $this->selectedMonth, $this->selectedYear),
            'sectors' => ProjectSector::cases(),
        ]);
    }
}
