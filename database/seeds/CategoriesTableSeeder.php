<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert(
            [
                [   
                    'category_name' => 'Laptop',
                ],
                [   
                    'category_name' => 'PC Package',
                ],
                [   
                    'category_name' => 'Peripheral',
                ]
            ]
                );
    }
}
