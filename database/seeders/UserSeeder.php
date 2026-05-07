<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin MacaBae',
            'email' => 'admin@macabae.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Staff Perpustakaan',
            'email' => 'pustakawan@macabae.com',
            'password' => bcrypt('password123'),
            'role' => 'pustakawan',
        ]);
    }
}
