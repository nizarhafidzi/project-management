<?php

namespace Modules\Project\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Project\Models\Project;
use Modules\Project\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\Project\Enums\ProjectStatus;
use Modules\Project\Enums\ProjectType;
use Modules\Project\Enums\TechnicalService;
use Modules\Project\Enums\ProjectSector;
use Modules\Reporting\Models\ProjectPlan;

class Phase63VerificationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users
        $manager = User::firstOrCreate(
            ['email' => 'manager63@example.com'],
            ['name' => 'Manager 6.3', 'password' => Hash::make('password')]
        );
        $manager->syncRoles('Manager');

        $employee = User::firstOrCreate(
            ['email' => 'employee63@example.com'],
            ['name' => 'Employee 6.3', 'password' => Hash::make('password')]
        );
        $employee->syncRoles('Employee');

        // 2. Create Project
        try {
            Project::where('project_code', 'PROJ-63')->delete();
            $project = Project::create([
                'project_code' => 'PROJ-63',
                'name' => 'Phase 6.3 Verification',
                // 'description' => 'Testing assignment logic', // Removed as column might not exist
                'project_type' => ProjectType::Internal,
                'contract_number' => 'CTR-63',
                'technical_service' => TechnicalService::Engineering,
                'sector' => ProjectSector::Building,
                'acc_project_id' => 'ACC-63',
                // 'start_date' => now(), // Removed as not in schema
                // 'end_date' => now()->addMonths(6), // Removed as not in schema
                'status' => ProjectStatus::Active,
            ]);
            
            // Allow created_by to be set manually if model supports it (not in fillable usually)
            // If created_by is a column, we need to update it directly or add to fillable
            // But let's check if it exists first.
            if (\Illuminate\Support\Facades\Schema::hasColumn('projects', 'created_by')) {
                 $project->created_by = $manager->id;
                 $project->saveQuietly();
            }
        } catch (\Exception $e) {
            $this->command->error("Project Check/Create Failed: " . $e->getMessage());
            // throw $e; 
            return;
        }

        // 3. Attach Users to Project
        if (!$project->users()->where('user_id', $manager->id)->exists()) {
            $project->users()->attach($manager->id, ['role_in_project' => 'Manager']);
        }
        if (!$project->users()->where('user_id', $employee->id)->exists()) {
            $project->users()->attach($employee->id, ['role_in_project' => 'Employee']);
        }

        // 4. Create Tasks
        // Task 1: Unassigned Root
        $root1 = Task::create([
            'project_id' => $project->id,
            'name' => 'Root Task 1 (Unassigned)',
            'wbs_code' => '1',
            'weight' => 50,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'user_id' => null, // Unassigned
        ]);

        // Task 2: Assigned to Manager
        $root2 = Task::create([
            'project_id' => $project->id,
            'name' => 'Root Task 2 (Manager)',
            'wbs_code' => '2',
            'weight' => 50,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'user_id' => $manager->id,
        ]);

        // Task 1.1: Assigned to Employee
        Task::create([
            'project_id' => $project->id,
            'parent_id' => $root1->id,
            'name' => 'Sub Task 1.1 (Employee)',
            'wbs_code' => '1.1',
            'weight' => 100, // Relative to parent
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'user_id' => $employee->id,
            'sort_order' => 1,
        ]);

        $this->command->info('Phase 6.3 Verification Data Seeded.');
        $this->command->info("Manager: {$manager->email}");
        $this->command->info("Employee: {$employee->email}");
        $this->command->info("Project: {$project->project_code}");
    }
}
