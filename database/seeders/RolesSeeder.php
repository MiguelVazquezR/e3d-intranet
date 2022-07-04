<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'Admin',
            'Empleado',
            'Auxiliar_producción',
            'Almacenista',
            'Chofer',
            'Diseñador',
            'Jefe_producción',
            'Vendedor',
            'Recursos_humanos',
        ];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
            ]);
        }
    }
}
