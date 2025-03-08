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
        $user = User::create([
            'first_name' => 'Thanh',
            'last_name' => 'Thinh',
            'email' => 'asjdasdasdsd@gmail.com',
            'password' => bcrypt('Admin123123@'), // Mã hóa mật khẩu
            'address' => 'TP.HCM',
            'status' => '3',
            'role' => 'admin',
        ]);

        // Cập nhật id của người dùng
        $user->id = 1;
        $user->save();
    }
}