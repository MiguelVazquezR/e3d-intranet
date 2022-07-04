<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin
        $admin_permissions = Permission::all();
        Role::find(1)->syncPermissions($admin_permissions->pluck('id'));

        //Empleado
        Role::find(2)->syncPermissions([
            1, 3, 6, 8, 11, 13, 16, 18, 21, 23, 26, 28, 31, 33, 35, 37, 40, 42, 55, 56, 57
        ]);

        //Auxiliar produccion
        Role::find(3)->syncPermissions([
            17, 46, 47, 48, 49, 50
        ]);

        //Alamcenista
        Role::find(4)->syncPermissions([
            6, 7, 8, 9, 10
        ]);

        // Chofer
        Role::find(5)->syncPermissions([
            17, 18, 19, 20
        ]);

        // Diseñador
        Role::find(6)->syncPermissions([
            51, 52, 53, 54, 55
        ]);

        // Jefe produccion
        Role::find(7)->syncPermissions([
            1, 2, 3, 4, 5, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30
        ]);

        // Vendedor
        Role::find(8)->syncPermissions([
            1, 2, 3, 4, 5, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30
        ]);

        // RRHH
        Role::find(9)->syncPermissions([
            31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 55, 56, 57, 58, 59, 60
        ]);
    }
}
