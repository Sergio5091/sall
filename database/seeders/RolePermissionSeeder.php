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
        Permission::create(['name' => $permission]);
    }

    // Create roles and assign created permissions
    $adminRole = Role::create(['name' => 'admin']);
    $adminRole->givePermissionTo(Permission::all());

    $promoterRole = Role::create(['name' => 'promoter']);
    $promoterRole->givePermissionTo([
        'manage salles',
        'manage events',
        'manage reservations',
        'view dashboard'
    ]);

    $clientRole = Role::create(['name' => 'client']);
    $clientRole->givePermissionTo([
        'view dashboard'
    ]);
}
}
