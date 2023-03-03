<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert(
            [
                [   
                    'product_name' => 'Asus Aspire',
                    'category_id' => 1,
                    'selling_price' => 20000,
                    'image' => '',
  
                    'manufacturer' => 'Asus',
                    'Processor' => 'Rogue Galaxy',
                    'ram' => 5,
                    'connectivity' => 'VGA,HDMI',
                    'hard_drive' => 'SSD',
                    'hard_drive_size' => 500,
                    'hard_drive_byte' => 'GB',
                    'os' => 'Windows',
                    'os_version' => '8'
                ],
                [   
                    'product_name' => 'Lenovo ThinkAgain',
                    'category_id' => 1,
                    'selling_price' => 25000,
                    'image' => '',
           
                    'manufacturer' => 'Lenovo',
                    'Processor' => 'Core i5',
                    'ram' => 16,
                    'connectivity' => 'VGA,HDMI',
                    'hard_drive' => 'SSD',
                    'hard_drive_size' => 500,
                    'hard_drive_byte' => 'GB',
                    'os' => 'Windows',
                    'os_version' => '10'
                ],
                [   
                    'product_name' => 'HP Ryzen 5',
                    'category_id' => 1,
                    'selling_price' => 31000,
                    'image' => '',

                    'manufacturer' => 'HP',
                    'Processor' => 'Ryzen 5',
                    'ram' => 8,
                    'connectivity' => 'VGA,HDMI',
                    'hard_drive' => 'SSD',
                    'hard_drive_size' => 1,
                    'hard_drive_byte' => 'TB',
                    'os' => 'Windows',
                    'os_version' => '10'
                ],
                [   
                    'product_name' => 'Super Dragon Built Package ',
                    'category_id' => 2,
                    'selling_price' => 35000,
                    'image' => '',

                    'manufacturer' => 'Custom',
                    'Processor' => 'Ultra Dragon',
                    'ram' => 16,
                    'connectivity' => 'VGA,HDMI',
                    'hard_drive' => 'SSD',
                    'hard_drive_size' => 1,
                    'hard_drive_byte' => 'TB',
                    'os' => 'Windows',
                    'os_version' => '11'
                ],
                [   
                    'product_name' => 'Rapoo Gaming Mouse',
                    'category_id' => 3,
                    'selling_price' => 700,
                    'image' => '',

                    'manufacturer' => 'Rapoo',
                    'Processor' => '',
                    'ram' => 0,
                    'connectivity' => '',
                    'hard_drive' => '',
                    'hard_drive_size' => 0,
                    'hard_drive_byte' => '',
                    'os' => '',
                    'os_version' => ''
                ]
               
            ]
                );
    }
}
