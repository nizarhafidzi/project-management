<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Superadmin
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@cipms.test',
                'password' => Hash::make('password'),
                'role' => 'Superadmin',
            ],
            // Managers
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'manager1@cipms.test',
                'password' => Hash::make('password'),
                'role' => 'Manager',
            ],
            [
                'name' => 'Siti Rahmawati',
                'email' => 'manager2@cipms.test',
                'password' => Hash::make('password'),
                'role' => 'Manager',
            ],
            // Team Leaders
            [
                'name' => 'Budi Santoso',
                'email' => 'teamlead1@cipms.test',
                'password' => Hash::make('password'),
                'role' => 'Team Leader',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'teamlead2@cipms.test',
                'password' => Hash::make('password'),
                'role' => 'Team Leader',
            ],
            // Employees
            [
                'name' => 'Eko Prasetyo',
                'email' => 'employee1@cipms.test',
                'password' => Hash::make('password'),
                'role' => 'Employee',
            ],
            [
                'name' => 'Fitri Handayani',
                'email' => 'employee2@cipms.test',
                'password' => Hash::make('password'),
                'role' => 'Employee',
            ],
            [
                'name' => 'Gunawan Wijaya',
                'email' => 'employee3@cipms.test',
                'password' => Hash::make('password'),
                'role' => 'Employee',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            $user->assignRole($role);
        }
    }
}
