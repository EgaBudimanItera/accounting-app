<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // optional: kosongkan dulu
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456')
        ]);

        User::create([
            'name' => 'Staff',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('123456')
        ]);
    }
}