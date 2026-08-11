<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\CreatesUsers;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use CreatesUsers;

    public function test_checkout_decrements_stock_atomically(): void
    {
        $user = $this->makeUser();
        $productId = $this->seedProduct(5);
        $customerId = $this->seedCustomer();

        $this->withHeaders($this->authHeaders($user))
            ->postJson('/api/cart/add/'.$productId)
            ->assertOk();

        $this->withHeaders($this->authHeaders($user))
            ->postJson('/api/orderdone', [
                'customer_id' => $customerId,
                'payby' => 'Cash',
                'qty' => 1,
                'subtotal' => 100,
                'vat' => 0,
                'total' => 100,
                'pay' => 100,
                'due' => 0,
                'invoiceNum' => 'INV1',
                'change' => 0,
            ])
            ->assertOk();

        $this->assertSame(4, (int) DB::table('products')->where('id', $productId)->value('product_quantity'));
        $this->assertSame(0, DB::table('pos')->where('user_id', $user->id)->count());
        $this->assertDatabaseHas('orders', ['cashier_id' => $user->id, 'customer_id' => $customerId]);
    }

    public function test_oversell_returns_422_and_creates_no_order(): void
    {
        $user = $this->makeUser();
        $productId = $this->seedProduct(1);
        $customerId = $this->seedCustomer();

        $headers = $this->authHeaders($user);
        $this->withHeaders($headers)->postJson('/api/cart/add/'.$productId)->assertOk();
        $this->withHeaders($headers)->postJson('/api/cart/increment/'.DB::table('pos')->where('user_id', $user->id)->value('id'));

        // Force cart qty above stock by direct update (bypass controller guard for oversell path)
        DB::table('pos')->where('user_id', $user->id)->update(['pro_quantity' => 5, 'sub_total' => 500]);

        $beforeOrders = DB::table('orders')->count();

        $this->withHeaders($headers)
            ->postJson('/api/orderdone', [
                'customer_id' => $customerId,
                'payby' => 'Cash',
                'qty' => 5,
                'subtotal' => 500,
                'vat' => 0,
                'total' => 500,
                'pay' => 500,
                'due' => 0,
                'invoiceNum' => 'INV2',
                'change' => 0,
            ])
            ->assertStatus(422);

        $this->assertSame($beforeOrders, DB::table('orders')->count());
        $this->assertSame(1, (int) DB::table('products')->where('id', $productId)->value('product_quantity'));
    }
}
