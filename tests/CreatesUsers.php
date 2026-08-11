<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

trait CreatesUsers
{
    use RefreshDatabase;

    protected function makeUser(array $overrides = []): User
    {
        $id = DB::table('users')->insertGetId(array_merge([
            'name' => 'Test User',
            'username' => 'user'.uniqid(),
            'email' => uniqid().'@example.com',
            'password' => Hash::make('password123'),
            'user_role' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));

        return User::findOrFail($id);
    }

    protected function makeAdmin(array $overrides = []): User
    {
        return $this->makeUser(array_merge(['user_role' => 1, 'username' => 'admin'.uniqid()], $overrides));
    }

    protected function authHeaders(User $user): array
    {
        $token = auth('api')->login($user);

        return ['Authorization' => 'Bearer '.$token];
    }

    protected function seedProduct(int $qty = 10): int
    {
        DB::table('categories')->insertOrIgnore([
            'id' => 1,
            'category_name' => 'Default',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('products')->insertGetId([
            'category_id' => 1,
            'product_name' => 'Test Product',
            'selling_price' => '100',
            'product_quantity' => $qty,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function seedCustomer(): int
    {
        return DB::table('customers')->insertGetId([
            'name' => 'Walk-in',
            'email' => 'walkin@example.com',
            'phone' => '000',
            'address' => 'n/a',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
