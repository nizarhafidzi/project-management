<?php

namespace Modules\Project\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Project\Models\Project;
use Modules\Project\Models\Task;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Use the first project
        $project = Project::first();

        if (!$project) {
            $this->command->warn('No projects found. Run ProjectSeeder first.');
            return;
        }

        $this->command->info("Seeding WBS tasks for: {$project->name}");

        // ──────────────────────────────────────────
        // Level 1 — Root Tasks (sum = 100%)
        // ──────────────────────────────────────────

        $t1 = Task::create([
            'project_id' => $project->id,
            'parent_id' => null,
            'wbs_code' => '1',
            'name' => 'Pekerjaan Persiapan',
            'weight' => 20.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-03-01',
            'end_date' => '2026-04-15',
            'sort_order' => 1,
        ]);

        $t2 = Task::create([
            'project_id' => $project->id,
            'parent_id' => null,
            'wbs_code' => '2',
            'name' => 'Pekerjaan Struktur',
            'weight' => 50.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-04-01',
            'end_date' => '2026-08-31',
            'sort_order' => 2,
        ]);

        $t3 = Task::create([
            'project_id' => $project->id,
            'parent_id' => null,
            'wbs_code' => '3',
            'name' => 'Pekerjaan Finishing',
            'weight' => 30.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-07-01',
            'end_date' => '2026-10-31',
            'sort_order' => 3,
        ]);

        // ──────────────────────────────────────────
        // Level 2 — Sub-tasks under root
        // ──────────────────────────────────────────

        // Under 1. Pekerjaan Persiapan (weight sum = 20%)
        $t1_1 = Task::create([
            'project_id' => $project->id,
            'parent_id' => $t1->id,
            'wbs_code' => '1.1',
            'name' => 'Survey & Pengukuran',
            'weight' => 8.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-03-01',
            'end_date' => '2026-03-21',
            'sort_order' => 1,
        ]);

        $t1_2 = Task::create([
            'project_id' => $project->id,
            'parent_id' => $t1->id,
            'wbs_code' => '1.2',
            'name' => 'Mobilisasi Peralatan',
            'weight' => 7.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-03-15',
            'end_date' => '2026-04-05',
            'sort_order' => 2,
        ]);

        $t1_3 = Task::create([
            'project_id' => $project->id,
            'parent_id' => $t1->id,
            'wbs_code' => '1.3',
            'name' => 'Pembersihan Lahan',
            'weight' => 5.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-03-20',
            'end_date' => '2026-04-15',
            'sort_order' => 3,
        ]);

        // Under 2. Pekerjaan Struktur (weight sum = 50%)
        $t2_1 = Task::create([
            'project_id' => $project->id,
            'parent_id' => $t2->id,
            'wbs_code' => '2.1',
            'name' => 'Pekerjaan Pondasi',
            'weight' => 20.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-04-01',
            'end_date' => '2026-05-31',
            'sort_order' => 1,
        ]);

        $t2_2 = Task::create([
            'project_id' => $project->id,
            'parent_id' => $t2->id,
            'wbs_code' => '2.2',
            'name' => 'Pekerjaan Kolom & Balok',
            'weight' => 18.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-05-15',
            'end_date' => '2026-07-31',
            'sort_order' => 2,
        ]);

        $t2_3 = Task::create([
            'project_id' => $project->id,
            'parent_id' => $t2->id,
            'wbs_code' => '2.3',
            'name' => 'Pekerjaan Plat Lantai',
            'weight' => 12.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-06-15',
            'end_date' => '2026-08-31',
            'sort_order' => 3,
        ]);

        // Under 3. Pekerjaan Finishing (weight sum = 30%)
        $t3_1 = Task::create([
            'project_id' => $project->id,
            'parent_id' => $t3->id,
            'wbs_code' => '3.1',
            'name' => 'Pekerjaan Dinding & Plester',
            'weight' => 12.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-07-01',
            'end_date' => '2026-09-15',
            'sort_order' => 1,
        ]);

        $t3_2 = Task::create([
            'project_id' => $project->id,
            'parent_id' => $t3->id,
            'wbs_code' => '3.2',
            'name' => 'Pekerjaan MEP',
            'weight' => 10.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-08-01',
            'end_date' => '2026-10-15',
            'sort_order' => 2,
        ]);

        $t3_3 = Task::create([
            'project_id' => $project->id,
            'parent_id' => $t3->id,
            'wbs_code' => '3.3',
            'name' => 'Pekerjaan Cat & Pelapis',
            'weight' => 8.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-09-01',
            'end_date' => '2026-10-31',
            'sort_order' => 3,
        ]);

        // ──────────────────────────────────────────
        // Level 3 — Sub-sub-tasks
        // ──────────────────────────────────────────

        // Under 1.1 Survey & Pengukuran (weight sum = 8%)
        Task::create([
            'project_id' => $project->id,
            'parent_id' => $t1_1->id,
            'wbs_code' => '1.1.1',
            'name' => 'Survey Topografi',
            'weight' => 5.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-03-01',
            'end_date' => '2026-03-14',
            'sort_order' => 1,
        ]);

        Task::create([
            'project_id' => $project->id,
            'parent_id' => $t1_1->id,
            'wbs_code' => '1.1.2',
            'name' => 'Marking & Patok',
            'weight' => 3.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-03-10',
            'end_date' => '2026-03-21',
            'sort_order' => 2,
        ]);

        // Under 2.1 Pekerjaan Pondasi (weight sum = 20%)
        Task::create([
            'project_id' => $project->id,
            'parent_id' => $t2_1->id,
            'wbs_code' => '2.1.1',
            'name' => 'Galian Tanah',
            'weight' => 8.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-04-01',
            'end_date' => '2026-04-30',
            'sort_order' => 1,
        ]);

        Task::create([
            'project_id' => $project->id,
            'parent_id' => $t2_1->id,
            'wbs_code' => '2.1.2',
            'name' => 'Pemasangan Bored Pile',
            'weight' => 12.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-04-20',
            'end_date' => '2026-05-31',
            'sort_order' => 2,
        ]);

        // Under 2.2 Kolom & Balok (weight sum = 18%)
        Task::create([
            'project_id' => $project->id,
            'parent_id' => $t2_2->id,
            'wbs_code' => '2.2.1',
            'name' => 'Pembesian Kolom Lantai 1-2',
            'weight' => 10.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-05-15',
            'end_date' => '2026-06-30',
            'sort_order' => 1,
        ]);

        Task::create([
            'project_id' => $project->id,
            'parent_id' => $t2_2->id,
            'wbs_code' => '2.2.2',
            'name' => 'Pengecoran Kolom & Balok Lt. 3',
            'weight' => 8.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-06-15',
            'end_date' => '2026-07-31',
            'sort_order' => 2,
        ]);

        // Under 3.1 Dinding & Plester (weight sum = 12%)
        Task::create([
            'project_id' => $project->id,
            'parent_id' => $t3_1->id,
            'wbs_code' => '3.1.1',
            'name' => 'Pemasangan Bata Ringan',
            'weight' => 7.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-07-01',
            'end_date' => '2026-08-15',
            'sort_order' => 1,
        ]);

        Task::create([
            'project_id' => $project->id,
            'parent_id' => $t3_1->id,
            'wbs_code' => '3.1.2',
            'name' => 'Plesteran & Acian',
            'weight' => 5.00,
            'coefficient' => 1.0000,
            'start_date' => '2026-08-01',
            'end_date' => '2026-09-15',
            'sort_order' => 2,
        ]);

        $this->command->info('WBS tasks seeded successfully (' . Task::where('project_id', $project->id)->count() . ' tasks).');
    }
}
