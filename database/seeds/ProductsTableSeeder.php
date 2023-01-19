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
                    'category_id' => 1,
                    'product_name' => 'Asus Aspire',
                    'product_code' => '090911',
                    'root' => 'laptop',
                    'buying_price' => 15000,
                    'selling_price' => 20000,
                    'supplier_id' => 2,
                    'buying_date' => '2022-11-5',
                    'image' => '',
                    'product_quantity' => 0,

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
                    'category_id' => 1,
                    'product_name' => 'Lenovo ThinkAgain',
                    'product_code' => '080811',
                    'root' => 'laptop',
                    'buying_price' => 16500,
                    'selling_price' => 25000,
                    'supplier_id' => 2,
                    'buying_date' => '2022-11-5',
                    'image' => '',
                    'product_quantity' => 0,

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
                    'category_id' => 1,
                    'product_name' => 'HP Ryzen 5',
                    'product_code' => '070721',
                    'root' => 'laptop',
                    'buying_price' => 18500,
                    'selling_price' => 31000,
                    'supplier_id' => 2,
                    'buying_date' => '2022-11-5',
                    'image' => '',
                    'product_quantity' => 0,

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
                    'category_id' => 2,
                    'product_name' => 'Super Dragon Built Package ',
                    'product_code' => '000021',
                    'root' => 'pc built',
                    'buying_price' => 28500,
                    'selling_price' => 35000,
                    'supplier_id' => 1,
                    'buying_date' => '2022-11-5',
                    'image' => '',
                    'product_quantity' => 0,

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
                    'category_id' => 3,
                    'product_name' => 'Rapoo Gaming Mouse',
                    'product_code' => '01111',
                    'root' => 'pc component',
                    'buying_price' => 500,
                    'selling_price' => 700,
                    'supplier_id' => 3,
                    'buying_date' => '2022-11-6',
                    'image' => '',
                    'product_quantity' => 0,

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
