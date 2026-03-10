<?php

namespace Modules\Reporting\Exports;

use Modules\Reporting\Models\WorkforceMonthlySummary;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WorkforceCoefficientExport implements FromArray, WithHeadings
{
    protected int $month;
    protected int $year;
    protected array $selectedUsers;

    public function __construct($month, $year, $sector = null, array $selectedUsers = [])
    {
        $this->month = (int) ($month ?: now()->month);
        $this->year = (int) ($year ?: now()->year);
        $this->selectedUsers = $selectedUsers;
    }

    /**
     * Build the export data array from workforce_monthly_summaries.
     * Each project in project_details becomes its own row.
     */
    public function array(): array
    {
        $query = WorkforceMonthlySummary::with('user')
            ->where('month', $this->month)
            ->where('year', $this->year);

        // Filter by selected users
        if (!empty($this->selectedUsers)) {
            $query->whereIn('user_id', $this->selectedUsers);
        }

        $summaries = $query->get();

        $exportData = [];

        foreach ($summaries as $summary) {
            if (!$summary->user) {
                continue;
            }

            $projectDetails = $summary->project_details ?? [];

            if (empty($projectDetails)) {
                // Still include the user with no project data
                $exportData[] = [
                    $summary->user->name,
                    'N/A',
                    0,
                    $summary->total_working_days,
                    $summary->attended_days,
                    $summary->total_utilization . '%',
                    $summary->month,
                    $summary->year,
                ];
            } else {
                foreach ($projectDetails as $project) {
                    $exportData[] = [
                        $summary->user->name,
                        $project['project_name'] ?? '-',
                        $project['coefficient'] ?? 0,
                        $summary->total_working_days,
                        $summary->attended_days,
                        $summary->total_utilization . '%',
                        $summary->month,
                        $summary->year,
                    ];
                }
            }
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Project',
            'Project Coefficient',
            'Total Working Days',
            'Attended Days',
            'Total Utilization',
            'Month',
            'Year',
        ];
    }
}
