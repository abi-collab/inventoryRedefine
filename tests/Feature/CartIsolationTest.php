<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\CreatesUsers;
use Tests\TestCase;

class CartIsolationTest extends TestCase
{
    use CreatesUsers;

    public function test_carts_are_isolated_per_user(): void
    {
        $a = $this->makeUser(['username' => 'cashier_a']);
        $b = $this->makeUser(['username' => 'cashier_b']);
        $productId = $this->seedProduct(5);

        $this->withHeaders($this->authHeaders($a))
            ->postJson('/api/cart/add/'.$productId)
            ->assertOk();

        $this->withHeaders($this->authHeaders($b))
            ->getJson('/api/cart/product')
            ->assertOk()
            ->assertExactJson([]);

        $cartA = $this->withHeaders($this->authHeaders($a))
            ->getJson('/api/cart/product')
            ->assertOk()
            ->json();

        $this->assertCount(1, $cartA);
        $this->assertSame($productId, (int) $cartA[0]['pro_id']);
        $this->assertSame($a->id, (int) $cartA[0]['user_id']);
    }
}
