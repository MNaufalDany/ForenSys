<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Rendra Wijaya',
            'email' => 'admin@forensys.test',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        User::create([
            'name' => 'Maya Salsabila',
            'email' => 'registrasi@forensys.test',
            'password' => Hash::make('register123'),
            'role' => 'register',
            'status' => 'active'
        ]);

        User::create([
            'name' => 'dr. Aditya Pratama',
            'email' => 'dokterAP@forensys.test',
            'password' => Hash::make('dokter123'),
            'role' => 'dokter',
            'status' => 'active'
        ]);
    }
}
