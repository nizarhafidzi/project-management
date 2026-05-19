<?php

namespace Modules\Project\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Project\Models\Project;
use Modules\Project\Models\Task;
use Modules\Reporting\Services\ProjectPlanService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TaskImportService
{
    protected int $projectId;
    protected ProjectPlanService $projectPlanService;

    // Stats
    public int $createdCount = 0;
    public int $updatedCount = 0;
    public int $skippedCount = 0;
    public int $sheetsProcessedCount = 0;

    /**
     * The target header keywords to search for.
     * Each entry is: canonical_key => [possible keywords to match].
     * We use str_contains for flexible matching against cell values.
     */
    protected const HEADER_KEYWORDS = [
        'DOCUMENT TYPE'              => ['DOCUMENT TYPE', 'DOC TYPE', 'DOCUMENT', 'JENIS DOKUMEN'],
        'DISCIPLINE'                 => ['DISCIPLINE', 'DISIPLIN'],
        'JOB ITEM CODE'              => ['JOB ITEM CODE', 'WORK ITEM CODE', 'ITEM CODE'],
        'LOCATION/STAT/DESCRIPTIONS' => ['LOCATION', 'DESCRIPTIONS', 'DESKRIPSI'],
        'PROJECT PHASE'              => ['PROJECT PHASE', 'PHASE', 'FASE'],
        'PIC'                        => ['PIC'],
        'FILE NAME'                  => ['FILE NAME', 'FILENAME', 'NAMA FILE'],
        // Planned date columns
        'TANGGAL MULAI RENCANA'      => ['TANGGAL MULAI RENCANA'],
        'TANGGAL SELESAI RENCANA'    => ['TANGGAL SELESAI RENCANA'],
    ];

    /**
     * The minimum number of headers that MUST be found to consider a row as the header row.
     * FILE NAME is always required. We require at least 3 of the 7 headers to be flexible.
     */
    protected const MIN_HEADERS_REQUIRED = 3;

    /**
     * The required column — rows without this value are skipped.
     */
    protected const REQUIRED_COLUMN = 'FILE NAME';

    /**
     * The column used as the Task name.
     */
    protected const NAME_COLUMN = 'FILE NAME';

    public function __construct(int $projectId, ProjectPlanService $projectPlanService)
    {
        $this->projectId = $projectId;
        $this->projectPlanService = $projectPlanService;
    }

    /**
     * Import tasks from a TIDP/MIDP formatted Excel file.
     * Optionally supply a $parentId to import all tasks as sub-tasks of that parent.
     */
    public function import(UploadedFile $file, ?int $parentId = null): array
    {
        $project = Project::findOrFail($this->projectId);

        $filePath = $file->getRealPath();
        Log::info("[TIDP/MIDP Import] Loading file: {$filePath}");

        // Load the spreadsheet using PhpSpreadsheet (bundled with maatwebsite/excel)
        $spreadsheet = IOFactory::load($filePath);

        $sheetNames = $spreadsheet->getSheetNames();
        Log::info('[TIDP/MIDP Import] File loaded. Total sheets: ' . count($sheetNames) . '. Names: ' . implode(', ', $sheetNames));

        foreach ($sheetNames as $index => $sheetName) {
            // Rule 1: Only process sheets containing "TIDP" or "MIDP" (case-insensitive)
            if (!$this->isValidSheetName($sheetName)) {
                Log::info("[TIDP/MIDP Import] Skipping sheet #{$index}: '{$sheetName}' (not TIDP/MIDP)");
                continue;
            }

            Log::info("[TIDP/MIDP Import] ✓ Processing sheet #{$index}: '{$sheetName}'");

            $worksheet = $spreadsheet->getSheet($index);
            $this->processSheet($worksheet, $project, $parentId);
            $this->sheetsProcessedCount++;
        }

        // Post-process: regenerate S-Curve plan data
        if ($this->createdCount > 0 || $this->updatedCount > 0) {
            try {
                $this->projectPlanService->generateProjectPlan($project);
            } catch (\Exception $e) {
                Log::warning('[TIDP/MIDP Import] S-Curve recalculation failed: ' . $e->getMessage());
            }
        }

        Log::info("[TIDP/MIDP Import] ══ COMPLETE ══ Sheets: {$this->sheetsProcessedCount}, Created: {$this->createdCount}, Updated: {$this->updatedCount}, Skipped: {$this->skippedCount}");

        return [
            'sheets_processed' => $this->sheetsProcessedCount,
            'created' => $this->createdCount,
            'updated' => $this->updatedCount,
            'skipped' => $this->skippedCount,
        ];
    }

    /**
     * Check if a sheet name contains "TIDP" or "MIDP" (case-insensitive).
     */
    protected function isValidSheetName(string $name): bool
    {
        $upper = strtoupper(trim($name));
        return str_contains($upper, 'TIDP') || str_contains($upper, 'MIDP');
    }

    /**
     * Process a single worksheet: find headers, then iterate data rows.
     */
    protected function processSheet(Worksheet $worksheet, Project $project, ?int $parentId): void
    {
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $highestColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        $sheetTitle = trim($worksheet->getTitle());

        Log::info("[TIDP/MIDP Import] Sheet '{$sheetTitle}': {$highestRow} rows × {$highestColumn} ({$highestColIndex}) cols");

        // Step 1: Scan for the header row (check first 50 rows max)
        $headerMap = null;
        $headerRowIndex = null;
        $maxScanRows = min($highestRow, 50);

        for ($row = 1; $row <= $maxScanRows; $row++) {
            $headerMap = $this->tryParseHeaderRow($worksheet, $row, $highestColIndex);
            if ($headerMap !== null) {
                $headerRowIndex = $row;
                Log::info("[TIDP/MIDP Import] ✓ Header found on row {$row}. Mapped columns: " . json_encode($headerMap));
                break;
            }
        }

        if ($headerMap === null) {
            Log::warning("[TIDP/MIDP Import] ✗ No valid header row found in sheet '{$sheetTitle}'. Skipping entire sheet.");
            return;
        }

        // Verify FILE NAME column is present
        if (!isset($headerMap[self::REQUIRED_COLUMN])) {
            Log::warning("[TIDP/MIDP Import] ✗ Header found but missing required column '" . self::REQUIRED_COLUMN . "'. Skipping sheet.");
            return;
        }

        // Step 2: Process data rows (starting from header + 1)
        $batch = [];
        $dataRowCount = 0;
        $blankRowCount = 0;

        for ($row = $headerRowIndex + 1; $row <= $highestRow; $row++) {
            $rowData = $this->extractRowData($worksheet, $row, $headerMap);
            $dataRowCount++;

            if ($rowData === null) {
                $blankRowCount++;
                continue;
            }

            $fileName = trim($rowData[self::REQUIRED_COLUMN] ?? '');

            if (empty($fileName)) {
                $this->skippedCount++;
                continue;
            }

            $batch[] = $rowData;
        }

        Log::info("[TIDP/MIDP Import] Data scan: {$dataRowCount} rows scanned, {$blankRowCount} blank, " . count($batch) . " valid, {$this->skippedCount} skipped (no FILE NAME)");

        // Step 3: Create/find parent task from sheet name, then persist children
        if (!empty($batch)) {
            DB::transaction(function () use ($batch, $project, $sheetTitle, $parentId) {
                $targetParentId = $parentId;
                
                // If no specific parent is targeted, create or find the parent task (sheet name = parent task name)
                if ($targetParentId === null) {
                    $parentTask = $this->upsertParentTask($sheetTitle, $project);
                    $targetParentId = $parentTask->id;
                }

                foreach ($batch as $rowData) {
                    $this->upsertTask($rowData, $project, $targetParentId);
                }
            });
            Log::info("[TIDP/MIDP Import] DB transaction committed. Parent: " . ($parentId ?? "'{$sheetTitle}'") . ", Created: {$this->createdCount}, Updated: {$this->updatedCount}");
        } else {
            Log::warning("[TIDP/MIDP Import] No valid rows found to import in sheet '{$sheetTitle}'.");
        }
    }

    /**
     * Try to parse a given row as the header row.
     *
     * Uses flexible keyword matching (str_contains) instead of exact match.
     * Returns a map of [canonical_header_name => column_index] if enough headers are found.
     * FILE NAME header is always required.
     */
    protected function tryParseHeaderRow(Worksheet $worksheet, int $row, int $highestColIndex): ?array
    {
        // Read all cell values in this row, normalize them
        $cellValues = [];
        for ($col = 1; $col <= $highestColIndex; $col++) {
            $cell = $worksheet->getCellByColumnAndRow($col, $row);
            $value = $cell->getValue();

            // Handle RichText objects
            if ($value instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                $value = $value->getPlainText();
            }

            // Normalize: uppercase, trim, collapse whitespace, remove newlines
            $normalized = strtoupper(trim((string) ($value ?? '')));
            $normalized = preg_replace('/\s+/', ' ', $normalized);

            if (!empty($normalized)) {
                $cellValues[$col] = $normalized;
            }
        }

        if (empty($cellValues)) {
            return null;
        }

        // Try to match each target header to a cell
        $headerMap = [];
        foreach (self::HEADER_KEYWORDS as $canonicalKey => $keywords) {
            foreach ($cellValues as $colIndex => $cellValue) {
                // Skip columns already assigned
                if (in_array($colIndex, $headerMap)) {
                    continue;
                }

                foreach ($keywords as $keyword) {
                    $keywordUpper = strtoupper($keyword);
                    // Use str_contains for flexible matching
                    if (str_contains($cellValue, $keywordUpper)) {
                        $headerMap[$canonicalKey] = $colIndex;
                        break 2; // Found this header, move to next
                    }
                }
            }
        }

        // FILE NAME must always be found
        if (!isset($headerMap[self::REQUIRED_COLUMN])) {
            return null;
        }

        // Require at least MIN_HEADERS_REQUIRED headers total
        if (count($headerMap) < self::MIN_HEADERS_REQUIRED) {
            return null;
        }

        return $headerMap;
    }

    /**
     * Extract data for a single row based on the header map.
     * Returns null if ALL target cells are empty (blank row).
     */
    protected function extractRowData(Worksheet $worksheet, int $row, array $headerMap): ?array
    {
        $data = [];
        $allEmpty = true;

        foreach ($headerMap as $headerName => $colIndex) {
            $value = $this->getCellValue($worksheet, $colIndex, $row);
            $data[$headerName] = $value;

            if (!empty($value)) {
                $allEmpty = false;
            }
        }

        return $allEmpty ? null : $data;
    }

    /**
     * Safely get a cell's display value, resolving formulas and RichText.
     * Uses getCalculatedValue() which returns the cached calculated result
     * that Excel stored when the file was last saved.
     */
    protected function getCellValue(Worksheet $worksheet, int $col, int $row): string
    {
        $cell = $worksheet->getCellByColumnAndRow($col, $row);

        // Try getCalculatedValue first — this resolves formulas
        // and returns cached results for complex formulas
        try {
            $value = $cell->getCalculatedValue();
        } catch (\Exception $e) {
            // If calculation fails, fall back to raw value
            $value = $cell->getValue();
        }

        // Handle RichText objects
        if ($value instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
            $value = $value->getPlainText();
        }

        $value = trim((string) ($value ?? ''));

        // If the value still looks like a raw formula (starts with =),
        // it means PhpSpreadsheet couldn't resolve it.
        // Try getOldCalculatedValue() as a last resort.
        if (str_starts_with($value, '=')) {
            try {
                $oldValue = $cell->getOldCalculatedValue();
                if ($oldValue !== null && !str_starts_with((string)$oldValue, '=')) {
                    $value = trim((string)$oldValue);
                }
            } catch (\Exception $e) {
                // Keep the formula string as-is
            }
        }

        return $value;
    }

    /**
     * Create or find a parent task based on the sheet name.
     * Parent tasks sit at root level (parent_id = null).
     */
    protected function upsertParentTask(string $sheetName, Project $project): Task
    {
        // Upsert: find existing parent by name, or create new
        $parentTask = Task::where('project_id', $this->projectId)
            ->whereNull('parent_id')
            ->where('name', $sheetName)
            ->first();

        if (!$parentTask) {
            $wbsCode = Task::generateNextWbsCode($this->projectId, null);
            $sortOrder = Task::where('project_id', $this->projectId)
                ->whereNull('parent_id')
                ->max('sort_order') ?? 0;

            $parentTask = Task::create([
                'project_id'  => $this->projectId,
                'parent_id'   => null,
                'wbs_code'    => $wbsCode,
                'name'        => $sheetName,
                'description' => "Imported from sheet: {$sheetName}",
                'weight'      => 0,
                'start_date'  => $project->start_date ?? now()->format('Y-m-d'),
                'end_date'    => $project->end_date   ?? now()->addDays(30)->format('Y-m-d'),
                'sort_order'  => $sortOrder + 1,
            ]);

            Log::info("[TIDP/MIDP Import] Created parent task: '{$sheetName}' (WBS: {$wbsCode})");
        } else {
            Log::info("[TIDP/MIDP Import] Found existing parent task: '{$sheetName}' (ID: {$parentTask->id})");
        }

        return $parentTask;
    }

    /**
     * Upsert a Task from TIDP/MIDP row data.
     *
     * - Upsert key: project_id + name (FILE NAME)
     * - If exists: update description and parent_id
     * - If new: create as child of parentId with auto-generated WBS code
     */
    protected function upsertTask(array $rowData, Project $project, int $parentId): void
    {
        $name = trim($rowData[self::NAME_COLUMN]);
        $description = $this->buildDescription($rowData);
        $categories = trim($rowData['DOCUMENT TYPE'] ?? '');
        $discipline = trim($rowData['DISCIPLINE'] ?? '');

        // Parse planned dates from Excel
        $startDate = $this->parseDate($rowData['TANGGAL MULAI RENCANA'] ?? '');
        $endDate   = $this->parseDate($rowData['TANGGAL SELESAI RENCANA'] ?? '');

        // Fallback to project dates if not provided in Excel
        $finalStartDate = $startDate ?? ($project->start_date ? $project->start_date->format('Y-m-d') : now()->format('Y-m-d'));
        $finalEndDate   = $endDate   ?? ($project->end_date   ? $project->end_date->format('Y-m-d')   : now()->addDays(30)->format('Y-m-d'));

        // Safety: skip rows where the name is still an unresolved formula
        if (str_starts_with($name, '=')) {
            Log::warning("[TIDP/MIDP Import] Skipping row with unresolved formula in FILE NAME: " . mb_substr($name, 0, 80));
            $this->skippedCount++;
            return;
        }

        // Safety: truncate to VARCHAR limits
        if (mb_strlen($name) > 255)       $name = mb_substr($name, 0, 255);
        if (mb_strlen($categories) > 255) $categories = mb_substr($categories, 0, 255);
        if (mb_strlen($discipline) > 255)  $discipline = mb_substr($discipline, 0, 255);

        // Search for existing task by name within this project
        $existingTask = Task::where('project_id', $this->projectId)
            ->where('name', $name)
            ->first();

        if ($existingTask) {
            // Update description, categories, dates, and re-parent under the correct sheet
            $existingTask->update([
                'description' => $description,
                'categories'  => $categories ?: null,
                'discipline'  => $discipline ?: null,
                'parent_id'   => $parentId,
                'start_date'  => $finalStartDate,
                'end_date'    => $finalEndDate,
                'status'      => 'Not Started',
            ]);
            $this->updatedCount++;
        } else {
            // Generate next WBS code as child of parent
            $wbsCode = Task::generateNextWbsCode($this->projectId, $parentId);

            // Determine sort order among siblings
            $sortOrder = Task::where('project_id', $this->projectId)
                ->where('parent_id', $parentId)
                ->max('sort_order') ?? 0;

            Task::create([
                'project_id'  => $this->projectId,
                'parent_id'   => $parentId,
                'wbs_code'    => $wbsCode,
                'name'        => $name,
                'description' => $description,
                'categories'  => $categories ?: null,
                'discipline'  => $discipline ?: null,
                'weight'      => 0,
                'start_date'  => $finalStartDate,
                'end_date'    => $finalEndDate,
                'status'      => 'Not Started',
                'sort_order'  => $sortOrder + 1,
            ]);
            $this->createdCount++;
        }
    }

    /**
     * Parse a date value from Excel.
     * Handles both Excel serial numbers (e.g. 46113) and date strings (e.g. "13-Apr-26").
     * Returns Y-m-d string or null if empty/unparseable.
     */
    protected function parseDate(string $rawValue): ?string
    {
        $rawValue = trim($rawValue);

        if (empty($rawValue) || str_starts_with($rawValue, '=')) {
            return null;
        }

        try {
            // If purely numeric, treat as Excel serial date number
            if (is_numeric($rawValue)) {
                $dateTime = ExcelDate::excelToDateTimeObject((float) $rawValue);
                return $dateTime->format('Y-m-d');
            }

            // Otherwise parse as date string
            return Carbon::parse($rawValue)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Build a neatly formatted description string from the TIDP/MIDP columns.
     * Excludes the FILE NAME column (that becomes the task name).
     */
    protected function buildDescription(array $rowData): string
    {
        $lines = [];

        // DOCUMENT TYPE → 'categories' column, DISCIPLINE → 'discipline' column
        $descriptionFields = [
            'JOB ITEM CODE'              => 'Job Item Code',
            'LOCATION/STAT/DESCRIPTIONS' => 'Location/Stat/Descriptions',
            'PROJECT PHASE'              => 'Project Phase',
            'PIC'                        => 'PIC',
        ];

        foreach ($descriptionFields as $key => $label) {
            if (!isset($rowData[$key])) continue; // Column wasn't found in this sheet
            $value = $rowData[$key];
            if (!empty($value)) {
                $lines[] = "{$label}: {$value}";
            }
        }

        return implode("\n", $lines);
    }
}
