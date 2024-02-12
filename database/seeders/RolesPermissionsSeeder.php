<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $role_permissions = [
            'admin' => ['manage users','create hotel','edit hotel','delete hotel' , 'view hotel'],
            'costumer' => ['make bookings'],
        ];

        foreach($role_permissions as $role => $permissions) {
            foreach($permissions as $permission) {
                Role::where('name', $role)->first()->givePermissionTo($permission);
            }
        }
    }
}
