<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            EmployeeTableSeeder::class,
            CustomersTableSeeder::class,
            CategoriesTableSeeder::class,
            ProductsTableSeeder::class,
            SuppliersTableSeeder::class,
        ]);
    }
}
