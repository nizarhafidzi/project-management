<?php

namespace Modules\Project\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\Project\Models\Task;

class WbsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected int $projectId;

    public function __construct(int $projectId)
    {
        $this->projectId = $projectId;
    }

    public function collection()
    {
        return Task::with('users')
            ->where('project_id', $this->projectId)
            ->orderByRaw("LENGTH(wbs_code), wbs_code") // Natural WBS sort: 1, 1.1, 1.2, 2
            ->get();
    }

    public function headings(): array
    {
        return [
            'WBS Code',
            'Task Name',
            'Weight (%)',
            'Start Date',
            'End Date',
            'Progress (%)',
            'Assignees',
        ];
    }

    /**
     * @param Task $task
     */
    public function map($task): array
    {
        return [
            $task->wbs_code,
            $task->name,
            number_format($task->weight, 2),
            $task->start_date?->format('Y-m-d') ?? '',
            $task->end_date?->format('Y-m-d') ?? '',
            number_format($task->total_progress, 2),
            $task->users->pluck('name')->join(', ') ?: 'Unassigned',
        ];
    }
}
