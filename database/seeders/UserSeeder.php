<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

//dsaddasdsaasdasdsa

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'azus0111',
            'fullname' => 'Huỳnh Nhật Lâm',
            'email' => 'huynhnhatlam391@gmail.com',
            'password' => Hash::make('01112006Lam@'),
            'avatar' => 'default.png',
            'role' => '1',
        ]);

        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "user$i",
                'fullname' => "User $i",
                'email' => "user$i@gmail.com",
                'password' => Hash::make("password$i"),
                'avatar' => 'default.png',
                'role' => '0',
            ]);
        }
    }
}
