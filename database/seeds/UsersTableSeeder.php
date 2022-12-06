<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                [   
                    'name' => 'admin Rey',
                    'username' => 'rey',
                    'user_role' => 1,
                    'email' => 'adminRey@gmail.com',
                    'password' => bcrypt('123456')
                ],
                [   
                    'name' => 'uncle Ben',
                    'username' => 'ben',
                    'user_role' => 2,
                    'email' => 'userBen@gmail.com',
                    'password' => bcrypt('654321')
                ]
            ]
                );
    }
}
