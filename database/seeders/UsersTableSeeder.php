<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin Rey',
                'username' => 'rey',
                'user_role' => 1,
                'email' => 'adminRey@gmail.com',
                'password' => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'uncle Ben',
                'username' => 'ben',
                'user_role' => 2,
                'email' => 'userBen@gmail.com',
                'password' => Hash::make('user1234'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
