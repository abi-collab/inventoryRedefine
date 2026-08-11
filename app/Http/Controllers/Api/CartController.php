<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    protected function cartQuery()
    {
        return DB::table('pos')->where('user_id', auth()->id());
    }

    protected function ownedCartRow(int $id)
    {
        return $this->cartQuery()->where('id', $id)->first();
    }

    public function AddToCart(Request $request, $id)
    {
        $userId = auth()->id();
        $product = DB::table('products')->where('id', $id)->first();

        if (! $product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $check = $this->cartQuery()->where('pro_id', $id)->first();

        if ($check) {
            $available = (int) $product->product_quantity;
            if ((int) $check->pro_quantity >= $available) {
                return response()->json(['error' => 'Quantity exceeds available stock'], 422);
            }

            $this->cartQuery()->where('pro_id', $id)->increment('pro_quantity');
            $pos = $this->cartQuery()->where('pro_id', $id)->first();
            $subtotal = $pos->pro_quantity * $pos->product_price;
            $this->cartQuery()->where('pro_id', $id)->update([
                'sub_total' => $subtotal,
                'updated_at' => now(),
            ]);
        } else {
            if ((int) $product->product_quantity < 1) {
                return response()->json(['error' => 'Product is out of stock'], 422);
            }

            DB::table('pos')->insert([
                'user_id' => $userId,
                'pro_id' => $id,
                'pro_name' => $product->product_name,
                'pro_quantity' => 1,
                'product_price' => $product->selling_price,
                'sub_total' => $product->selling_price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json('done');
    }

    public function CartProduct()
    {
        $cart = $this->cartQuery()->orderBy('id')->get();

        return response()->json($cart);
    }

    public function removeCart($id)
    {
        $row = $this->ownedCartRow((int) $id);
        if (! $row) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }

        $this->cartQuery()->where('id', $id)->delete();

        return response('done');
    }

    public function Increment($id)
    {
        $row = $this->ownedCartRow((int) $id);
        if (! $row) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }

        $product = DB::table('products')->where('id', $row->pro_id)->first();
        if ($product && (int) $row->pro_quantity >= (int) $product->product_quantity) {
            return response()->json(['error' => 'Quantity exceeds available stock'], 422);
        }

        $this->cartQuery()->where('id', $id)->increment('pro_quantity');
        $productRow = $this->ownedCartRow((int) $id);
        $subtotal = $productRow->pro_quantity * $productRow->product_price;
        $this->cartQuery()->where('id', $id)->update([
            'sub_total' => $subtotal,
            'updated_at' => now(),
        ]);

        return response('done');
    }

    public function decrement($id)
    {
        $row = $this->ownedCartRow((int) $id);
        if (! $row) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }

        if ((int) $row->pro_quantity <= 1) {
            $this->cartQuery()->where('id', $id)->delete();

            return response('done');
        }

        $this->cartQuery()->where('id', $id)->decrement('pro_quantity');
        $productRow = $this->ownedCartRow((int) $id);
        $subtotal = $productRow->pro_quantity * $productRow->product_price;
        $this->cartQuery()->where('id', $id)->update([
            'sub_total' => $subtotal,
            'updated_at' => now(),
        ]);

        return response('done');
    }

    public function Vats()
    {
        $vat = DB::table('extras')->first();

        return response()->json($vat);
    }
}
