<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Job permissions
            'view jobs',
            'create jobs',
            'edit jobs',
            'delete jobs',

            // Application permissions
            'view applications',
            'create applications',
            'manage applications',

            // Profile permissions
            'view profiles',
            'edit own profile',

            // Admin permissions
            'view metrics',
            'manage users',
            'manage system',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $employerRole = Role::create(['name' => 'employer']);
        $employerRole->givePermissionTo([
            'view jobs',
            'create jobs',
            'edit jobs',
            'delete jobs',
            'view applications',
            'manage applications',
            'edit own profile',
        ]);

        $candidateRole = Role::create(['name' => 'candidate']);
        $candidateRole->givePermissionTo([
            'view jobs',
            'create applications',
            'view applications',
            'edit own profile',
        ]);
    }
}
