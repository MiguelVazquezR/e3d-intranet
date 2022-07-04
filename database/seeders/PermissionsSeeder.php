<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // 1-5
            'tabla_productos',
            'crear_productos',
            'ver_productos',
            'editar_productos',
            'eliminar_productos',

            // 5-10
            'tabla_inventarios',
            'crear_inventarios',
            'ver_inventarios',
            'editar_inventarios',
            'eliminar_inventarios',

            // 10-15
            'tabla_cotizaciones',
            'crear_cotizaciones',
            'ver_cotizaciones',
            'editar_cotizaciones',
            'eliminar_cotizaciones',

            // 15-20
            'tabla_clientes',
            'crear_clientes',
            'ver_clientes',
            'editar_clientes',
            'eliminar_clientes',

            // 20-25
            'tabla_ordenes_venta',
            'crear_ordenes_venta',
            'ver_ordenes_venta',
            'editar_ordenes_venta',
            'eliminar_ordenes_venta',

            // 25-30
            'tabla_ordenes_diseño',
            'crear_ordenes_diseño',
            'ver_ordenes_diseño',
            'editar_ordenes_diseño',
            'eliminar_ordenes_diseño',

            // 30-35
            'tabla_usuarios',
            'crear_usuarios',
            'ver_usuarios',
            'editar_usuarios',
            'eliminar_usuarios',

            // 35-40
            'tabla_roles',
            'crear_roles',
            'ver_roles',
            'editar_roles',
            'eliminar_roles',

            // 40-45
            'tabla_permisos',
            'crear_permisos',
            'ver_permisos',
            'editar_permisos',
            'eliminar_permisos',

            // 45-50
            'tabla_departamento_producción',
            'crear_departamento_producción',
            'ver_departamento_producción',
            'editar_departamento_producción',
            'eliminar_departamento_producción',

            // 50-55
            'tabla_departamento_diseño',
            'crear_departamento_diseño',
            'ver_departamento_diseño',
            'editar_departamento_diseño',
            'eliminar_departamento_diseño',
            
            // 55-60
            'tabla_nóminas',
            'crear_nóminas',
            'ver_nóminas',
            'editar_nóminas',
            'eliminar_nóminas',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }
    }
}
