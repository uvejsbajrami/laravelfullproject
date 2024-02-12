<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = ['manage users','create hotel','edit hotel','delete hotel' , 'view hotel','make bookings'];

        foreach($permissions as $permission){
            Permission::create(["name"=>$permission,"guard_name"=>"web"]);
        }
    }
}
