<?php

namespace Modules\Project\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Project\Models\Project;
use Modules\Project\Models\Task;
use Modules\Reporting\Services\ProjectPlanService;

class TaskImportService
{
    protected int $projectId;
    protected ProjectPlanService $projectPlanService;

    // Stats
    public int $createdCount = 0;
    public int $updatedCount = 0;
    public int $missingEmailCount = 0;

    // Cache
    protected ?Collection $projectUsers = null;

    public function __construct(int $projectId, ProjectPlanService $projectPlanService)
    {
        $this->projectId = $projectId;
        $this->projectPlanService = $projectPlanService;
    }

    public function downloadTemplate()
    {
        $data = [
            ['WBS Code', 'Task Name', 'Weight (%)', 'Start Date (YYYY-MM-DD)', 'End Date (YYYY-MM-DD)', 'Assignee Email'],
            ['1', 'Analysis Phase', '20', '2026-01-01', '2026-01-15', 'manager@example.com'],
            ['1.1', 'Requirements Gathering', '50', '2026-01-01', '2026-01-07', 'staff@example.com'],
            ['1.2', 'Documentation', '50', '2026-01-08', '2026-01-15', 'staff@example.com'],
        ];

        return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray {
            protected $data;
            public function __construct($data) { $this->data = $data; }
            public function array(): array { return $this->data; }
        }, 'wbs_template.xlsx');
    }

    public function import(UploadedFile $file): array
    {
        // 1. Load Data
        $data = Excel::toArray(new class implements \Maatwebsite\Excel\Concerns\WithHeadingRow {
            public function rules(): array { return []; }
        }, $file);

        if (empty($data)) {
            return ['created' => 0, 'updated' => 0, 'missing_emails' => 0];
        }

        $rows = collect($data[0]);

        // 2. Sort by WBS Code (Natural Sort: 1, 1.1, 1.2, 1.10, 2)
        $rows = $rows->sort(function ($a, $b) {
            return strnatcmp((string)($a['wbs_code'] ?? ''), (string)($b['wbs_code'] ?? ''));
        });

        // 3. Prepare Environment
        $this->projectUsers = User::all()->keyBy(fn($u) => trim(strtolower($u->email)));
        $project = Project::find($this->projectId);
        
        if (!$project) {
            throw new \Exception("Project not found.");
        }

        // 4. Process in Chunks
        $rows->chunk(100)->each(function ($chunk) use ($project) {
            DB::transaction(function () use ($chunk, $project) {
                foreach ($chunk as $row) {
                    $this->processRow($row, $project);
                }
            });
        });

        // 5. Post-Process
        $this->projectPlanService->generateProjectPlan($project);

        return [
            'created' => $this->createdCount,
            'updated' => $this->updatedCount,
            'missing_emails' => $this->missingEmailCount,
        ];
    }

    protected function processRow(array $row, Project $project)
    {
        // Sanitization
        $wbsCode = trim((string)($row['wbs_code'] ?? ''));
        $name = trim((string)($row['task_name'] ?? ''));
        $weight = isset($row['weight_']) ? round((float)$row['weight_'], 2) : 0.0;
        // Date Logic: 
        // Support multiple column variations
        $startKey = $this->findKey($row, ['start_date_yyyy_mm_dd', 'start_date', 'start']);
        $endKey = $this->findKey($row, ['end_date_yyyy_mm_dd', 'end_date', 'end']);

        $startDate = $this->parseDate($row[$startKey] ?? null) 
            ?? $project->start_date 
            ?? now()->format('Y-m-d');
            
        $endDate = $this->parseDate($row[$endKey] ?? null) 
            ?? $project->end_date 
            ?? now()->addDays(7)->format('Y-m-d');
        $email = trim(strtolower($row['assignee_email'] ?? ''));

        if (empty($wbsCode)) return;

        // Resolve Parent
        // Logic: 1.1 -> Parent is 1. 1.2.3 -> Parent is 1.2
        $parentId = null;
        if (str_contains($wbsCode, '.')) {
            $parentCode = substr($wbsCode, 0, strrpos($wbsCode, '.'));
            $parentTask = Task::where('project_id', $this->projectId)
                ->where('wbs_code', $parentCode)
                ->first();
            $parentId = $parentTask->id ?? null;
            // If parent not found, we treat as Root? Or Skip? 
            // In WBS, if parent missing, it's orphan. Let's make it Root to be safe, or just null.
        }

        // Resolve User
        $userId = null;
        if (!empty($email)) {
            if ($this->projectUsers->has($email)) {
                $userId = $this->projectUsers->get($email)->id;
            } else {
                $this->missingEmailCount++;
            }
        }

        // Upsert
        $task = Task::where('project_id', $this->projectId)
            ->where('wbs_code', $wbsCode)
            ->first();

        if ($task) {
            $task->update([
                'name' => $name ?: $task->name,
                'parent_id' => $parentId, // Update hierarchy if changed
                'weight' => $weight,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'user_id' => $userId ?: $task->user_id, // Update assignee only if provided, or clear? Logic says "unassigned" if missing email.
            ]);
            $this->updatedCount++;
        } else {
            Task::create([
                'project_id' => $this->projectId,
                'parent_id' => $parentId,
                'wbs_code' => $wbsCode,
                'name' => $name ?: 'Untitled Task ' . $wbsCode,
                'weight' => $weight,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'user_id' => $userId,
                'sort_order' => 0, // Should calculate, but 0 is safe
            ]);
            $this->createdCount++;
        }
    }

    protected function findKey(array $row, array $candidates): ?string
    {
        foreach ($candidates as $key) {
            if (array_key_exists($key, $row)) {
                return $key;
            }
        }
        return null;
    }

    protected function parseDate($value): ?string
    {
        if (empty($value)) return null;
        try {
            // Excel numeric dates
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }

            // String dates
            $value = trim($value);
            
            // Try standard formats (Y-m-d, d-m-Y, d/m/Y)
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $value)) {
                return Carbon::createFromFormat('d/m/Y', str_replace('-', '/', $value))->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
