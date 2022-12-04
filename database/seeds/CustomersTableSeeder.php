<?php

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->insert(
            [
                [   
              
                    'name' => 'Jonel Regarde',
                    'email' => 'jonel@gmail.com',
                    'phone' => '0121545656',
                    'address' => 'brgy. mantibugao',
                    'photo' => '',
                  
                ],
                [
                    'name' => 'Robert Miranda',
                    'email' => 'robert@gmail.com',
                    'phone' => '093235665',
                    'address' => 'san sebastian II.',
                    'photo' => '',
                   
                ],
                [
                    'name' => 'Patrick Abasolo',
                    'email' => 'patty@gmail.com',
                    'phone' => '0912666314',
                    'address' => 'manolo fortich',
                    'photo' => '',
                   
                ]
            ]
                );
    }
}
