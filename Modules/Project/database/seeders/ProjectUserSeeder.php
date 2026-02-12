<?php

namespace Modules\Project\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Project\Models\Project;
use Modules\Project\Models\Task;

class ProjectUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Fetch Users
        $manager = User::where('email', 'manager1@cipms.test')->first(); // Manager 1 (Ahmad Fauzi)
        $tl1 = User::where('email', 'teamlead1@cipms.test')->first();
        $tl2 = User::where('email', 'teamlead2@cipms.test')->first();
        $emp1 = User::where('email', 'employee1@cipms.test')->first();
        $emp2 = User::where('email', 'employee2@cipms.test')->first();
        $emp3 = User::where('email', 'employee3@cipms.test')->first();

        // 2. Fetch Projects (Assuming ProjectSeeder created 5)
        $projects = Project::take(5)->get();

        if ($projects->count() < 5) {
            $this->command->warn('Not enough projects found. Please run ProjectSeeder first.');
            return;
        }

        // 3. Scenario 1: Manager can view 5 projects
        if ($manager) {
            foreach ($projects as $project) {
                $project->users()->syncWithoutDetaching([
                    $manager->id => ['role_in_project' => 'Manager']
                ]);
            }
        }

        // 4. Scenario 2: Two Team Leaders who can only view 2 different projects each
        // TL1 -> P1, P2
        if ($tl1) {
            $projects[0]->users()->syncWithoutDetaching([$tl1->id => ['role_in_project' => 'Team Leader']]);
            $projects[1]->users()->syncWithoutDetaching([$tl1->id => ['role_in_project' => 'Team Leader']]);
        }
        // TL2 -> P3, P4
        if ($tl2) {
            $projects[2]->users()->syncWithoutDetaching([$tl2->id => ['role_in_project' => 'Team Leader']]);
            $projects[3]->users()->syncWithoutDetaching([$tl2->id => ['role_in_project' => 'Team Leader']]);
        }

        // 5. Scenario 3: Three Employees who can only view their specific tasks in one project
        // Emp1 -> P1
        if ($emp1) {
            $p = $projects[0];
            $p->users()->syncWithoutDetaching([$emp1->id => ['role_in_project' => 'Member']]);
            
            // Assign tasks in P1 to Emp1
            $tasks = $p->tasks()->take(3)->get();
            foreach ($tasks as $task) {
                $task->update(['user_id' => $emp1->id]);
            }
        }

        // Emp2 -> Overloaded Staff (4 Projects: P1, P2, P3, P4)
        if ($emp2) {
            $projectsToAssign = $projects->take(4);
            foreach ($projectsToAssign as $p) {
                $p->users()->syncWithoutDetaching([$emp2->id => ['role_in_project' => 'Member']]);
                
                // Assign a task in each project
                $task = $p->tasks()->first();
                if ($task) {
                    $task->update(['user_id' => $emp2->id]);
                }
            }
        }

        // Emp3 -> Moderate Staff (3 Projects: P1, P3, P5)
        if ($emp3 && $projects->count() >= 5) {
            $moderateProjects = [$projects[0], $projects[2], $projects[4]];
            foreach ($moderateProjects as $p) {
                $p->users()->syncWithoutDetaching([$emp3->id => ['role_in_project' => 'Member']]);
            }
        }
    }
}
