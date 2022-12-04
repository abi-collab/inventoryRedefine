<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->insert(
            [
                [   
              
                    'name' => 'Rey Tolentino',
                    'email' => 'rey@gmail.com',
                    'phone' => '09781435545',
                    'address' => 'manila bay st.',
                    'photo' => '',
                    'nid' => '00155',
                    'salary' => '4500',
                    'joining_date' => '2022-11-5',
                ],
                [
                    'name' => 'Ken Santiago',
                    'email' => 'ken@gmail.com',
                    'phone' => '097323133',
                    'address' => 'san roque st.',
                    'photo' => '',
                    'nid' => '03333',
                    'salary' => '4800',
                    'joining_date' => '2022-11-6',
                ],
                [
                    'name' => 'troy Santiago',
                    'email' => 'troy@gmail.com',
                    'phone' => '011215456566',
                    'address' => 'san miguel.',
                    'photo' => '',
                    'nid' => '1215554',
                    'salary' => '3000',
                    'joining_date' => '2022-11-6',
                ]
            ]
                );
    }
}
