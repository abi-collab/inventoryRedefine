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
                    'email' => 'adminRey@gmail.com',
                    'password' => bcrypt('123456')
                ],
                [   
                    'name' => 'user Ben',
                    'email' => 'userBen@gmail.com',
                    'password' => bcrypt('654321')
                ]
            ]
                );
    }
}
