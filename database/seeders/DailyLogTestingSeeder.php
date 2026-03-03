<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Operations\Models\DailyLog;
use Modules\Project\Enums\ProjectSector;
use Modules\Project\Enums\ProjectStatus;
use Modules\Project\Enums\ProjectType;
use Modules\Project\Enums\TechnicalService;
use Modules\Project\Models\Project;
use Modules\Project\Models\Task;
use Carbon\Carbon;

class DailyLogTestingSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting Daily Log Testing Seeder...');

        // 1. Create Users
        $budi = $this->createUser('Budi Overload', 'budi.overload@cipms.test');
        $staff = [];
        $staffNames = ['Staff A', 'Staff B', 'Staff C', 'Staff D', 'Staff E', 'Staff F', 'Staff G', 'Staff H', 'Staff I'];
        foreach ($staffNames as $name) {
            $staff[] = $this->createUser($name, strtolower(str_replace(' ', '.', $name)) . '@cipms.test');
        }

        // 2. Create Projects
        $projects = [];
        for ($i = 1; $i <= 5; $i++) {
            $projects[] = Project::firstOrCreate(
                ['name' => "Analytics Project $i"],
                [
                    'project_code' => "2026-INTLOG-00$i", // Manual code for simplicity
                    'contract_number' => "CTR/2026/00$i",
                    'project_type' => ProjectType::Internal,
                    'technical_service' => TechnicalService::Engineering,
                    'sector' => ProjectSector::Infrastructure,
                    'status' => ProjectStatus::Active,
                    'start_date' => Carbon::now()->subMonths(4),
                    'end_date' => Carbon::now()->addMonths(8),
                    'contract_value' => 1000000000 * $i,
                    'description' => "Test Project for Analytics $i",
                    'address' => "Jakarta",
                    'owner' => "Internal",
                    'contract_date' => Carbon::now()->subMonths(4),
                ]
            );
        }

        // 3. Assign Users to Projects
        // Budi: All 5 projects
        foreach ($projects as $project) {
            $project->users()->syncWithoutDetaching([$budi->id => ['role_in_project' => 'Member']]);
        }

        // Staff A & B: 3 Projects (High Load) - Projs 1, 2, 3
        for ($i = 0; $i < 2; $i++) {
            $user = $staff[$i];
            for ($j = 0; $j < 3; $j++) {
                $projects[$j]->users()->syncWithoutDetaching([$user->id => ['role_in_project' => 'Member']]);
            }
        }

        // Remaining Staff: 1-2 Projects (Ideal Load)
        for ($i = 2; $i < count($staff); $i++) {
            $user = $staff[$i];
            // Randomly assign to 1 or 2 projects
            $assignedProjects = collect($projects)->random(rand(1, 2));
            foreach ($assignedProjects as $project) {
                $project->users()->syncWithoutDetaching([$user->id => ['role_in_project' => 'Member']]);
            }
        }

        // 4. Create Tasks per Project and Assign to Members
        foreach ($projects as $project) {
            $members = $project->users;
            for ($t = 1; $t <= 10; $t++) {
               $assignee = $members->random();
               Task::firstOrCreate(
                   [
                       'project_id' => $project->id,
                       'name' => "Task $t for " . $project->name,
                   ],
                   [
                       'wbs_code' => (string)$t, // Simple WBS
                       'weight' => 10, // Assuming 10 tasks sum to 100 for simplicity (validation might not be strict in seeder)
                       'start_date' => $project->start_date,
                       'end_date' => $project->end_date,
                       'user_id' => $assignee->id, // Assign to a random member so they have something to log
                   ]
               );
            }

            // Ensure Budi has at least one task in this project to log against
            if (!$project->tasks()->where('user_id', $budi->id)->exists()) {
                Task::firstOrCreate(
                   [
                       'project_id' => $project->id,
                       'name' => "Special Task for Budi in " . $project->name,
                   ],
                   [
                       'wbs_code' => "99",
                       'weight' => 0,
                       'start_date' => $project->start_date,
                       'end_date' => $project->end_date,
                       'user_id' => $budi->id,
                   ]
               );
            }
        }

        // 5. Generate Daily Logs (90 Days)
        $startDate = Carbon::today()->subDays(90);
        $endDate = Carbon::today();
        
        $allUsers = array_merge([$budi], $staff);

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $isWeekend = $date->isWeekend();

            foreach ($allUsers as $user) {
                $isBudi = $user->id === $budi->id;

                // Budi Logic
                if ($isBudi) {
                    // 95-100% attendance (rarely skip)
                    if (rand(1, 100) > 98) continue; 

                    // Clock out late (19:00 - 22:00)
                    $clockOutHour = rand(19, 22);
                    
                } else {
                    // Staff Logic
                    if ($isWeekend) continue; // Staff skip weekends
                    // 70-80% attendance
                    if (rand(1, 100) > 80) continue;

                    // Clock out normal (17:00 - 18:00)
                    $clockOutHour = rand(17, 18);
                }

                // Find a project and task for this user
                // Get projects user is assigned to
                $userProjects = $user->projects;
                if ($userProjects->isEmpty()) continue;

                // Pick ONE project to log for this day (One Log/Day Rule)
                // For Budi, this rotates naturally by random selection across his 5 projects
                $project = $userProjects->random();

                // Find a task assigned to user in this project
                $task = $project->tasks()->where('user_id', $user->id)->inRandomOrder()->first();
                
                // Fallback: if no task assigned, simplify setup by picking ANY task in project (or create one)
                // Ideally we use tasks assigned to them. If null, skip log to avoid error.
                if (!$task) continue; 

                // Create Log
                // Use withoutEvents to skip the 'boot' check that forces 'pending' on backdates
                // We want these directly Approved for analytics testing
                DailyLog::withoutEvents(function () use ($user, $task, $date, $clockOutHour) {
                    DailyLog::create([
                        'user_id' => $user->id,
                        'task_id' => $task->id,
                        'log_date' => $date->format('Y-m-d'),
                        'clock_in' => '09:00:00',
                        'clock_out' => "$clockOutHour:00:00",
                        'progress_increment' => rand(1, 5),
                        'is_backdate' => false, // Forced
                        'approval_status' => 'approved',
                        'notes' => 'Seeded log entry',
                    ]);
                });
            }
        }
        
        $this->command->info('Daily Log Testing Seeder Completed!');
    }

    private function createUser(string $name, string $email): User
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
            ]
        );
        
        if (!$user->hasRole('Employee')) {
            $user->assignRole('Employee');
        }

        return $user;
    }
}
