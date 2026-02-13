<?php

namespace Modules\Project\Tests\Feature;

use Tests\TestCase;
use Modules\Project\Livewire\ExecutiveDashboard;
use Modules\Project\Models\Project;
use App\Models\User;
use Modules\Project\Enums\ProjectStatus;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class ExecutiveDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup Roles
        if (!Role::where('name', 'Superadmin')->exists()) Role::create(['name' => 'Superadmin']);
        if (!Role::where('name', 'Employee')->exists()) Role::create(['name' => 'Employee']);
    }

    public function test_superadmin_sees_all_projects_stats()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Superadmin');

        // Create 3 active projects
        Project::factory()->count(3)->create(['status' => ProjectStatus::Active]);
        // Create 2 completed projects
        Project::factory()->count(2)->create(['status' => ProjectStatus::Completed]);

        Livewire::actingAs($admin)
            ->test(ExecutiveDashboard::class)
            ->assertSet('totalProjects', 5)
            ->assertSet('activeProjects', 3)
            ->assertViewHas('projectStatusData', function($data) {
                return $data['Active'] === 3 && $data['Completed'] === 2;
            });
    }

    public function test_employee_sees_only_assigned_projects_stats()
    {
        $employee = User::factory()->create();
        $employee->assignRole('Employee');

        $otherUser = User::factory()->create();

        // Project A: Assigned to Employee (Active)
        $projectA = Project::factory()->create(['status' => ProjectStatus::Active]);
        $projectA->users()->attach($employee, ['role_in_project' => 'Member']);

        // Project B: Assigned to Other (Active)
        $projectB = Project::factory()->create(['status' => ProjectStatus::Active]);
        $projectB->users()->attach($otherUser, ['role_in_project' => 'Member']);

        Livewire::actingAs($employee)
            ->test(ExecutiveDashboard::class)
            ->assertSet('totalProjects', 1)
            ->assertSet('activeProjects', 1);
            
        // Admin check just to be sure
        $admin = User::factory()->create();
        $admin->assignRole('Superadmin');
        
        Livewire::actingAs($admin)
            ->test(ExecutiveDashboard::class)
            ->assertSet('totalProjects', 2);
    }
}
