<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Thanh',
            'last_name' => 'Thinh',
            'email' => 'asjdasdasdsd@gmail.com',
            'password' => 'Admin123123@',
            'status' => '3',
            'role' => 'admin',
        ]);
    }
}
