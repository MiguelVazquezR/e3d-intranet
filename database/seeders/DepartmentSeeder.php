<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            'Gerencia',
            'Producción',
            'Diseño',
            'Ventas',
            'Calidad',
            'Sistemas',
            'Recursos humanos',
            'Logística',
        ];

        foreach ( $departments as $department ) {
            Department::create([
                'name' => $department
            ]);
        }
    }
}
