<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'user4',
            'email' => 'user4@gmail.com',
            'password' => Hash::make('44444444'),
            'role_id' => 2
        ]);

        User::create([
            'name' => 'user5',
            'email' => 'user5@gmail.com',
            'password' => Hash::make('55555555'),
            'role_id' => 2
        ]);

        User::create([
            'name' => 'user6',
            'email' => 'user6@gmail.com',
            'password' => Hash::make('66666666'),
            'role_id' => 2
        ]);

        User::create([
            'name' => 'user7',
            'email' => 'user7@gmail.com',
            'password' => Hash::make('77777777'),
            'role_id' => 2
        ]);

        User::create([
            'name' => 'user8',
            'email' => 'user8@gmail.com',
            'password' => Hash::make('88888888'),
            'role_id' => 2
        ]);

        User::create([
            'name' => 'user9',
            'email' => 'user9@gmail.com',
            'password' => Hash::make('99999999'),
            'role_id' => 2
        ]);

        User::create([
            'name' => 'user10',
            'email' => 'user10@gmail.com',
            'password' => Hash::make('10101010'),
            'role_id' => 2
        ]);
    }
}
