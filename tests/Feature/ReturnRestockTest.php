<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\CreatesUsers;
use Tests\TestCase;

class ReturnRestockTest extends TestCase
{
    use CreatesUsers;

    public function test_completing_return_restores_stock_and_serial(): void
    {
        $user = $this->makeUser();
        $productId = $this->seedProduct(3);

        DB::table('suppliers')->insert([
            'id' => 1,
            'name' => 'Supplier',
            'email' => 's@example.com',
            'phone' => '1',
            'address' => 'a',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('serialnumbers')->insert([
            'serial_number' => 'SN-1',
            'product_id' => $productId,
            'supplier_id' => 1,
            'status' => 'sold',
            'createdby' => 'tester',
            'buying_price' => 50,
            'sold_date' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $returnId = DB::table('returnitems')->insertGetId([
            'return_no' => 'R1',
            'invoiceNumber' => 'INV',
            'serialNo' => 'SN-1',
            'product_id' => $productId,
            'product_name' => 'Test Product',
            'created_by' => (string) $user->id,
            'remarks' => '',
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->withHeaders($this->authHeaders($user))
            ->putJson('/api/returns/'.$returnId, ['status' => 'completed'])
            ->assertOk();

        $this->assertSame(4, (int) DB::table('products')->where('id', $productId)->value('product_quantity'));
        $this->assertSame('available', DB::table('serialnumbers')->where('serial_number', 'SN-1')->value('status'));
        $this->assertNotNull(DB::table('returnitems')->where('id', $returnId)->value('completed_at'));

        $this->withHeaders($this->authHeaders($user))
            ->putJson('/api/returns/'.$returnId, ['status' => 'completed'])
            ->assertStatus(422);
    }
}
