<?php

namespace Database\Seeders;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permissions = [
            'manage-users',
            'view-blotter-records',
            'create-blotter-records',
            'edit-blotter-records',
            'delete-blotter-records',
            'manage-blotter-records',
            'schedule-hearings',
            'households.view',
            'residents.view',
            'assistance-requests.view',
            'posts.view',
            'blotter.view',
            'immunization.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $superAdminRole = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
        ]);

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $memberRole = Role::firstOrCreate([
            'name' => 'member',
            'guard_name' => 'web',
        ]);

        $superAdminRole->syncPermissions($permissions);
        $adminPermissions = [
            'households.view',
            'residents.view',
            'assistance-requests.view',
            'posts.view',
            'blotter.view',
            'immunization.view',
        ];
        $adminRole->syncPermissions([]);
        $memberRole->syncPermissions([]);

        Resident::factory(50)
            ->sequence(fn ($sequence) => [
                'resident_id' => 'RES-' . str_pad(
                    $sequence->index + 1,
                    6,
                    '0',
                    STR_PAD_LEFT
                ),
            ])
            ->create();

        User::factory()->create([
            'name' => 'Super Admin 1',
            'email' => 'admin1@gmail.com',
            'password' => bcrypt('admin123'),
        ])->assignRole($superAdminRole);

        User::factory()->create([
            'name' => 'Super Admin 2',
            'email' => 'admin2@gmail.com',
            'password' => bcrypt('admin123'),
        ])->assignRole($superAdminRole);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
        ])->assignRole($adminRole);
        User::where('email', 'admin@gmail.com')->first()->syncPermissions($adminPermissions);

        User::factory()->create([
            'name' => 'Member User',
            'email' => 'member@gmail.com',
            'password' => bcrypt('member123'),
        ])->assignRole($memberRole);
    }
}
