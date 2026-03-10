<?php

namespace Modules\Reporting\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Modules\Reporting\Services\WorkforceCalculationService;
use Modules\Reporting\Models\WorkforceMonthlySummary;
use Modules\Project\Enums\ProjectSector;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Reporting\Exports\WorkforceCoefficientExport;
use App\Models\User;

class WorkforcePage extends Component
{
    public string $sector = 'All';
    public $selectedMonth;
    public $selectedYear;

    // Multi-select user filter
    public array $selectedUsers = [];

    // Flash message
    public string $syncMessage = '';
    public string $syncMessageType = ''; // 'success' or 'error'

    protected $queryString = ['sector', 'selectedMonth', 'selectedYear'];

    public function mount()
    {
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;

        // Default: select all users EXCEPT Superadmin
        $this->selectedUsers = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'Superadmin');
        })->pluck('id')->map(fn ($id) => (string) $id)->toArray();
    }

    public function updatedSector()
    {
        // Re-render will happen automatically
    }

    public function updatedSelectedMonth()
    {
        $this->syncMessage = '';
        $this->dispatch('update-analytics-chart', data: ['month' => $this->selectedMonth, 'year' => $this->selectedYear]);
    }

    public function updatedSelectedYear()
    {
        $this->syncMessage = '';
        $this->dispatch('update-analytics-chart', data: ['month' => $this->selectedMonth, 'year' => $this->selectedYear]);
    }

    /**
     * Sync workforce data for the selected month/year.
     */
    public function syncData(WorkforceCalculationService $service)
    {
        try {
            $month = (int) ($this->selectedMonth ?: now()->month);
            $year = (int) ($this->selectedYear ?: now()->year);

            $count = $service->calculateAndSyncSummary($month, $year);

            $monthName = \DateTime::createFromFormat('!m', $month)->format('F');
            $this->syncMessage = "Successfully synced {$count} user(s) for {$monthName} {$year}.";
            $this->syncMessageType = 'success';
        } catch (\Exception $e) {
            $this->syncMessage = 'Sync failed: ' . $e->getMessage();
            $this->syncMessageType = 'error';
        }
    }

    public function exportExcel()
    {
        $monthName = $this->selectedMonth ? \DateTime::createFromFormat('!m', $this->selectedMonth)->format('F') : 'All';
        $yearName = $this->selectedYear ?: 'All';

        $fileName = "Workforce_Coefficient_{$monthName}_{$yearName}.xlsx";

        return Excel::download(
            new WorkforceCoefficientExport(
                $this->selectedMonth,
                $this->selectedYear,
                $this->sector,
                $this->selectedUsers
            ),
            $fileName
        );
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $month = (int) ($this->selectedMonth ?: now()->month);
        $year = (int) ($this->selectedYear ?: now()->year);

        // All users for the dropdown
        $availableUsers = User::orderBy('name')->get();

        // Query from the summary table — filtered by selected users
        $summaries = WorkforceMonthlySummary::with('user')
            ->where('month', $month)
            ->where('year', $year)
            ->whereIn('user_id', $this->selectedUsers)
            ->get()
            ->filter(function ($summary) {
                return $summary->user !== null;
            });

        return view('reporting::livewire.workforce-page', [
            'summaries' => $summaries,
            'sectors' => ProjectSector::cases(),
            'availableUsers' => $availableUsers,
        ]);
    }
}
