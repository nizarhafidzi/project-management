<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            \Modules\Project\Database\Seeders\ProjectSeeder::class,
            \Modules\Project\Database\Seeders\TaskSeeder::class,
            \Modules\Project\Database\Seeders\ProjectUserSeeder::class,
            \Modules\Operations\Database\Seeders\OperationsDatabaseSeeder::class,
            \Modules\Reporting\Database\Seeders\ReportingDatabaseSeeder::class,
        ]);
    }
}
