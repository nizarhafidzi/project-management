<?php

namespace Modules\Reporting\Exports;

use Modules\Reporting\Services\WorkforceService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DateTime;

class WorkforceCoefficientExport implements FromCollection, WithHeadings, WithMapping
{
    protected $month;
    protected $year;
    protected $sector;
    protected $workforceService;

    public function __construct($month, $year, $sector = 'All')
    {
        $this->month = $month;
        $this->year = $year;
        $this->sector = $sector;
        $this->workforceService = new WorkforceService();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // 1. Get the raw data from service
        $staffData = $this->workforceService->getStaffUtilization($this->sector, $this->month, $this->year);

        // 2. Flatten the data: Create a row for each project of each user
        $exportData = collect();

        foreach ($staffData as $user) {
            // If user has active projects, create a row for each
            if (!empty($user->active_projects_list) && $user->active_projects_list->count() > 0) {
                foreach ($user->active_projects_list as $project) {
                    $exportData->push([
                        'user_name' => $user->name,
                        'project_name' => $project->name,
                        // Coefficient is effectively the user's current calculated coefficient (1/N)
                        // The requirement says: "Coefficient: The project's coefficient (e.g., 0.5, 1...)"
                        'coefficient' => $user->utilization_coefficient,
                        'month' => $this->month,
                        'year' => $this->year,
                    ]);
                }
            } else {
                // what if user has no projects but was returned (e.g. if sector filter was loose or just listing all staff)?
                // Service primarily returns users. If active_projects_count is 0, coefficient is 0.
                // We should probably list them with "No Project" or just skip?
                // "Data Transformation (Flattening): ... Combine one User and one Project into one row"
                // If there are no projects, maybe one row with empty project? 
                // Let's include them to show their availability (0 coefficient)
                
                 $exportData->push([
                    'user_name' => $user->name,
                    'project_name' => '-',
                    'coefficient' => 0.00,
                    'month' => $this->month,
                    'year' => $this->year,
                ]);
            }
        }

        return $exportData;
    }

    public function map($row): array
    {
        // Format month name for display
        $monthName = $row['month'] ? DateTime::createFromFormat('!m', $row['month'])->format('F') : 'All Months';
        $yearName = $row['year'] ?: 'All Years';

        return [
            $row['user_name'],
            $row['project_name'],
            $row['coefficient'],
            $monthName,
            $yearName,
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Project',
            'Coefficient',
            'Month',
            'Year',
        ];
    }
}
