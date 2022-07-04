<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Jorge Sherman',
                'email' => 'j.sherman@emblemas3d.com',
                'password' => bcrypt('Sherman.e3d'),
            ],
            [
                'name' => 'Maribel Ortíz',
                'email' => 'maribel@emblemas3d.com',
                'password' => bcrypt('Maribel.e3d'),
            ],
            [
                'name' => 'Miguel Osvaldo Vázquez Rodríguez',
                'email' => 'ingeniaria.desarrollo@emblemas3d.com',
                'password' => bcrypt('123123123'),
            ],
        ];

        foreach($users as $user) {
            $_user = User::create($user);
            $_user->syncRoles([1]);
        }
    }
}
