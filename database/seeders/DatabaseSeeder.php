<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or update roles
        $roles = [
            ['name' => 'admin', 'label' => 'Administrator'],
            ['name' => 'fornissure', 'label' => 'Fornissure'],
            ['name' => 'operator', 'label' => 'Operator'],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']], // match by name
                ['label' => $roleData['label']] // update label if changed
            );
        }

        // Create or update users
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Fornissure',
                'email' => 'fornissuer@example.com',
                'password' => bcrypt('password'),
                'role' => 'fornissure',
            ],
            [
                'name' => 'Operator',
                'email' => 'operator@example.com',
                'password' => bcrypt('password'),
                'role' => 'operator',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']], // match by email
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                ]
            );

            // Attach role (sync to avoid duplicates)
            $role = Role::where('name', $userData['role'])->first();
            $user->roles()->sync([$role->id]);
        }
    }
}
