<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run()
{
    // Reset cached roles and permissions
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Create permissions
    $permissions = [
        'manage salles',
        'manage events',
        'manage reservations',
        'manage users',
        'manage parrainage',
        'view dashboard'
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    // Create roles and assign created permissions
    $adminRole = Role::firstOrCreate(['name' => 'admin']);
    $adminRole->syncPermissions(Permission::all());

    $promoterRole = Role::firstOrCreate(['name' => 'promoter']);
    $promoterRole->syncPermissions([
        'manage salles',
        'manage events',
        'manage reservations',
        'view dashboard'
    ]);

    $clientRole = Role::firstOrCreate(['name' => 'client']);
    $clientRole->syncPermissions([
        'view dashboard'
    ]);
}
}
