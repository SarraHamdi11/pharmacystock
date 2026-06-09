<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage products',
            'manage orders',
            'manage patients',
            'manage reports',
            'manage users',
            'delete records',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin: all permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::all());

        // Pharmacist: manage products, orders, and patients
        $pharmacistRole = Role::firstOrCreate(['name' => 'Pharmacist']);
        $pharmacistRole->syncPermissions([
            'manage products',
            'manage orders',
            'manage patients',
        ]);

        // Manager: manage reports, products, orders, patients
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $managerRole->syncPermissions([
            'manage products',
            'manage orders',
            'manage patients',
            'manage reports',
        ]);

        // Employee: manage orders and patients only
        $employeeRole = Role::firstOrCreate(['name' => 'Employee']);
        $employeeRole->syncPermissions([
            'manage orders',
            'manage patients',
        ]);

        // Create demo users for each role
        $this->createDemoUser('Admin User', 'admin@pharma.com', $adminRole);
        $this->createDemoUser('Pharmacist User', 'pharmacist@pharma.com', $pharmacistRole);
        $this->createDemoUser('Manager User', 'manager@pharma.com', $managerRole);
        $this->createDemoUser('Employee User', 'employee@pharma.com', $employeeRole);
    }

    /**
     * Create a demo user and assign a role.
     */
    private function createDemoUser(string $name, string $email, Role $role): void
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $user->syncRoles([$role]);
    }
}
