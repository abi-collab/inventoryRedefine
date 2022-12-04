<?php

use Illuminate\Database\Seeder;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('suppliers')->insert(
            [
                [   
              
                    'name' => 'Ruben Delos Reyes',
                    'email' => 'rubenz@gmail.com',
                    'phone' => '08865466',
                    'address' => 'baclaran',
                    'photo' => '',
                    'shopname' => 'pc works',
                   
                ],
                [
                    'name' => 'jonathan bernal',
                    'email' => 'jona@gmail.com',
                    'phone' => '0454856',
                    'address' => 'paranaque',
                    'photo' => '',
                    'shopname' => 'computer deals',
                ],
                [
                    'name' => 'jeanalyn santos',
                    'email' => 'jeanssss@gmail.com',
                    'phone' => '02365635',
                    'address' => 'taguig',
                    'photo' => '',
                    'shopname' => 'cross work',
                ]
            ]
                );
    }
}
