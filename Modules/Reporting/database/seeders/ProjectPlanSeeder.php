<?php

namespace Modules\Reporting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Project\Models\Project;
use Modules\Project\Models\Task;
use Modules\Reporting\Models\ProjectPlan;
use Modules\Reporting\Services\ProjectPlanService;
use Carbon\Carbon;

class ProjectPlanSeeder extends Seeder
{
    protected $service;

    public function __construct(ProjectPlanService $service)
    {
        $this->service = $service;
    }

    public function run(): void
    {
        $this->command->info('Starting S-Curve Verification Seeder...');

        // 1. Clean up previous test data
        $projectName = 'S-Curve Validation Project';
        $projectId = DB::table('projects')->where('name', $projectName)->value('id');
        
        if ($projectId) {
            DB::table('tasks')->where('project_id', $projectId)->delete();
            DB::table('project_plans')->where('project_id', $projectId)->delete();
            DB::table('projects')->where('id', $projectId)->delete();
        }

        // 2. Create Project
        // 2. Create Project
        $project = Project::create([
            'name' => $projectName,
            'project_code' => 'SCURVE-001',
            'contract_number' => 'CTR-2026-001',
            'status' => \Modules\Project\Enums\ProjectStatus::Active,
            'project_type' => \Modules\Project\Enums\ProjectType::Internal,
            'technical_service' => \Modules\Project\Enums\TechnicalService::Engineering,
            'sector' => \Modules\Project\Enums\ProjectSector::Building,
        ]);

        $this->command->info("Created Project: {$project->name}");

        // 3. Create Tasks
        // Scenario 1: Task spanning a normal weekend (March 7-8, 2026)
        // Weight: 50%
        Task::create([
            'project_id' => $project->id,
            'wbs_code' => '1',
            'name' => 'Task A (Weekend Span)',
            'weight' => 50,
            'coefficient' => 1,
            'start_date' => '2026-03-06', // Friday
            'end_date' => '2026-03-09',   // Monday
            'total_progress' => 0,
            'sort_order' => 1,
        ]);

        // Scenario 2: Task spanning a Holiday (Nyepi: Mar 19, Idul Fitri: Mar 20-21)
        // Weight: 50%
        Task::create([
            'project_id' => $project->id,
            'wbs_code' => '2',
            'name' => 'Task B (Holiday Span)',
            'weight' => 50,
            'coefficient' => 1,
            'start_date' => '2026-03-18', // Wednesday
            'end_date' => '2026-03-23',   // Monday
            'total_progress' => 0,
            'sort_order' => 2,
        ]);

        $this->command->info('Created Tasks. Generating Project Plan...');

        // 4. Trigger Service manually (Simulating Observer)
        $this->service->generateProjectPlan($project);

        // 5. Verify Results
        $this->verifyWeekendLogic($project);
        $this->verifyHolidayLogic($project);
        $this->verifyTotalProgress($project);
    }

    private function verifyWeekendLogic(Project $project)
    {
        // Task A: Mar 6 (Fri) - Mar 9 (Mon). Weekend: Mar 7, Mar 8.
        // Expected: 
        // Mar 6: Progress increases
        // Mar 7: No increase (Flat)
        // Mar 8: No increase (Flat)
        // Mar 9: Progress increases

        $planFri = ProjectPlan::where('project_id', $project->id)->where('period_date', '2026-03-06')->first();
        $planSat = ProjectPlan::where('project_id', $project->id)->where('period_date', '2026-03-07')->first();
        $planSun = ProjectPlan::where('project_id', $project->id)->where('period_date', '2026-03-08')->first();
        $planMon = ProjectPlan::where('project_id', $project->id)->where('period_date', '2026-03-09')->first();

        if (!$planFri || !$planSat || !$planSun || !$planMon) {
            $this->command->error('❌ Weekend Logic Verification Failed: Missing data points.');
            return;
        }

        if ($planSat->planned_progress == $planFri->planned_progress && $planSun->planned_progress == $planSat->planned_progress) {
             $this->command->info('✅ Weekend Logic Verification Passed (Flat line on Sat/Sun).');
        } else {
             $this->command->error("❌ Weekend Logic Verification Failed. Fri: {$planFri->planned_progress}, Sat: {$planSat->planned_progress}, Sun: {$planSun->planned_progress}");
        }
    }

    private function verifyHolidayLogic(Project $project)
    {
        // Task B: Mar 18 (Wed) - Mar 23 (Mon).
        // Holidays: Mar 19 (Thu - Nyepi), Mar 20 (Fri - Idul Fitri), Mar 21 (Sat - Idul Fitri).
        // Weekend: Mar 21 (Sat), Mar 22 (Sun).
        // Working Days: Mar 18 (Wed), Mar 23 (Mon).
        
        // Expected:
        // Mar 18: Progress increases
        // Mar 19 (Hol): Flat
        // Mar 20 (Hol): Flat
        // Mar 21 (Hol/Sat): Flat
        // Mar 22 (Sun): Flat
        // Mar 23 (Mon): Progress increases

        $planWed = ProjectPlan::where('project_id', $project->id)->where('period_date', '2026-03-18')->first();
        $planThu = ProjectPlan::where('project_id', $project->id)->where('period_date', '2026-03-19')->first();
        $planFri = ProjectPlan::where('project_id', $project->id)->where('period_date', '2026-03-20')->first();

        if (!$planWed || !$planThu || !$planFri) {
            $this->command->error('❌ Holiday Logic Verification Failed: Missing data points.');
            return;
        }

        if ($planThu->planned_progress == $planWed->planned_progress && $planFri->planned_progress == $planThu->planned_progress) {
             $this->command->info('✅ Holiday Logic Verification Passed (Flat line on Holidays).');
        } else {
             $this->command->error("❌ Holiday Logic Verification Failed. Wed: {$planWed->planned_progress}, Thu: {$planThu->planned_progress} (Expected Flat), Fri: {$planFri->planned_progress} (Expected Flat)");
        }
    }

    private function verifyTotalProgress(Project $project)
    {
        // Get the very last plan entry
        $lastPlan = ProjectPlan::where('project_id', $project->id)->orderBy('period_date', 'desc')->first();

        if ($lastPlan && $lastPlan->planned_progress == 100.00) {
            $this->command->info('✅ Final Efficiency Verification Passed: Exactly 100.00%.');
        } else {
            $val = $lastPlan ? $lastPlan->planned_progress : 'NULL';
            $this->command->error("❌ Final Efficiency Verification Failed. Result: {$val}% (Expected 100.00%)");
        }
    }
}
