<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@zapadictos.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'usuario@zapadictos.com'],
            [
                'name' => 'Usuario Normal',
                'password' => Hash::make('usuario12345'),
                'role' => 'normal',
            ]
        );
    }
}
