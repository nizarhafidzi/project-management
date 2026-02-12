<?php

namespace Modules\Project\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Project\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'project_type' => 'Internal',
                'contract_number' => 'KTR/2026/INT/001',
                'project_code' => '2026-INT-001',
                'name' => 'Gedung Kantor Pusat - Renovasi Lantai 3',
                'technical_service' => 'Engineering',
                'sector' => 'Building',
                'status' => 'Active',
            ],
            [
                'project_type' => 'External',
                'contract_number' => 'KTR/2026/EXT/001',
                'project_code' => '2026-EXT-001',
                'name' => 'Bendungan Serbaguna Kali Brantas',
                'technical_service' => 'Engineering',
                'sector' => 'Water Resources',
                'status' => 'Active',
            ],
            [
                'project_type' => 'Internal',
                'contract_number' => 'KTR/2026/INT/002',
                'project_code' => '2026-INT-002',
                'name' => 'BIM Modeling - Mall Grand Indonesia Phase 2',
                'technical_service' => 'BIM',
                'sector' => 'Building',
                'status' => 'Active',
            ],
            [
                'project_type' => 'External',
                'contract_number' => 'KTR/2026/EXT/002',
                'project_code' => '2026-EXT-002',
                'name' => 'Jalan Tol Trans-Jawa Section 5',
                'technical_service' => 'Engineering',
                'sector' => 'Infrastructure',
                'status' => 'On-Hold',
            ],
            [
                'project_type' => 'External',
                'contract_number' => 'KTR/2026/EXT/003',
                'project_code' => '2026-EXT-003',
                'name' => 'PLTA Cirata - Expansion Unit 3',
                'technical_service' => 'BIM',
                'sector' => 'Energy',
                'status' => 'Completed',
            ],
        ];

        foreach ($projects as $projectData) {
            Project::firstOrCreate(
                ['contract_number' => $projectData['contract_number']],
                $projectData
            );
        }

        // Assign members to projects
        // $this->assignMembers();
    }

    private function assignMembers(): void
    {
        $projects = Project::all();
        $managers = User::role('Manager')->get();
        $teamLeaders = User::role('Team Leader')->get();
        $employees = User::role('Employee')->get();

        if ($managers->isEmpty() || $teamLeaders->isEmpty() || $employees->isEmpty()) {
            return;
        }

        // Project 1: Gedung Kantor Pusat
        if ($projects->count() >= 1) {
            $p = $projects[0];
            $p->users()->syncWithoutDetaching([
                $managers[0]->id => ['role_in_project' => 'Manager'],
                $teamLeaders[0]->id => ['role_in_project' => 'Team Leader'],
                $employees[0]->id => ['role_in_project' => 'Member'],
                $employees[1]->id => ['role_in_project' => 'Member'],
            ]);
        }

        // Project 2: Bendungan Kali Brantas
        if ($projects->count() >= 2) {
            $p = $projects[1];
            $p->users()->syncWithoutDetaching([
                $managers[1]->id => ['role_in_project' => 'Manager'],
                $teamLeaders[1]->id => ['role_in_project' => 'Team Leader'],
                $employees[2]->id => ['role_in_project' => 'Member'],
            ]);
        }

        // Project 3: BIM Mall Grand Indonesia
        if ($projects->count() >= 3) {
            $p = $projects[2];
            $p->users()->syncWithoutDetaching([
                $managers[0]->id => ['role_in_project' => 'Manager'],
                $teamLeaders[1]->id => ['role_in_project' => 'Team Leader'],
                $employees[0]->id => ['role_in_project' => 'Member'],
                $employees[2]->id => ['role_in_project' => 'Member'],
            ]);
        }

        // Project 4: Jalan Tol Trans-Jawa
        if ($projects->count() >= 4) {
            $p = $projects[3];
            $p->users()->syncWithoutDetaching([
                $managers[1]->id => ['role_in_project' => 'Manager'],
                $teamLeaders[0]->id => ['role_in_project' => 'Team Leader'],
                $employees[1]->id => ['role_in_project' => 'Member'],
            ]);
        }

        // Project 5: PLTA Cirata
        if ($projects->count() >= 5) {
            $p = $projects[4];
            $p->users()->syncWithoutDetaching([
                $managers[0]->id => ['role_in_project' => 'Manager'],
                $teamLeaders[0]->id => ['role_in_project' => 'Team Leader'],
                $employees[0]->id => ['role_in_project' => 'Member'],
                $employees[1]->id => ['role_in_project' => 'Member'],
                $employees[2]->id => ['role_in_project' => 'Member'],
            ]);
        }
    }
}
