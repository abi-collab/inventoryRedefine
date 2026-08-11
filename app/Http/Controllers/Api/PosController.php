<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class PosController extends Controller
{
    public function GetProduct($id)
    {
        $product = DB::table('products')
            ->where('category_id', $id)
            ->get();

        return response()->json($product);
    }

    public function OrderDone(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'payby' => 'required',
            'serial_numbers' => 'nullable|array',
            'serial_numbers.*.serialNo' => 'nullable|string',
            'serial_numbers.*.product_id' => 'nullable|integer',
            'serial_numbers.*.customerName' => 'nullable|string',
            'serial_numbers.*.product_name' => 'nullable|string',
            'serial_numbers.*.category_id' => 'nullable|integer',
            'serial_numbers.*.order_quantity' => 'nullable',
            'serial_numbers.*.product_price' => 'nullable',
            'serial_numbers.*.invoiceNumber' => 'nullable|string',
        ]);

        $userId = auth()->id();

        try {
            $orderId = DB::transaction(function () use ($request, $userId) {
                $contents = DB::table('pos')->where('user_id', $userId)->lockForUpdate()->get();

                if ($contents->isEmpty()) {
                    abort(422, 'Cart is empty');
                }

                foreach ($contents as $content) {
                    $qty = (int) $content->pro_quantity;
                    $product = DB::table('products')->where('id', $content->pro_id)->lockForUpdate()->first();

                    if (! $product || (int) $product->product_quantity < $qty) {
                        abort(422, 'Insufficient stock for '.($content->pro_name ?? 'product'));
                    }

                    DB::table('products')->where('id', $content->pro_id)->update([
                        'product_quantity' => (int) $product->product_quantity - $qty,
                        'updated_at' => now(),
                    ]);
                }

                $today = now()->toDateString();
                $orderId = DB::table('orders')->insertGetId([
                    'customer_id' => $request->customer_id,
                    'cashier_id' => $userId,
                    'qty' => $request->qty,
                    'sub_total' => $request->subtotal,
                    'vat' => $request->vat,
                    'total' => $request->total,
                    'payby' => $request->payby,
                    'pay' => $request->pay,
                    'due' => $request->due,
                    'invoiceNum' => $request->invoiceNum,
                    'change' => $request->change,
                    'invoiceImg' => $request->invoiceImg,
                    'order_date' => date('m/d/Y'),
                    'ordered_on' => $today,
                    'order_month' => date('F'),
                    'order_year' => date('Y'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($contents as $content) {
                    DB::table('order_details')->insert([
                        'order_id' => $orderId,
                        'product_id' => $content->pro_id,
                        'pro_quantity' => $content->pro_quantity,
                        'product_price' => $content->product_price,
                        'sub_total' => $content->sub_total,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $serials = $request->input('serial_numbers', []);
                foreach ($serials as $serial) {
                    $serialNo = $serial['serialNo'] ?? null;
                    if (! $serialNo) {
                        continue;
                    }

                    DB::table('serials')->insert([
                        'invoiceNumber' => $serial['invoiceNumber'] ?? $request->invoiceNum,
                        'customerId' => $request->customer_id,
                        'customerName' => $serial['customerName'] ?? null,
                        'serialNo' => $serialNo,
                        'product_id' => $serial['product_id'] ?? null,
                        'product_name' => $serial['product_name'] ?? null,
                        'category_id' => $serial['category_id'] ?? null,
                        'order_quantity' => $serial['order_quantity'] ?? null,
                        'product_price' => $serial['product_price'] ?? null,
                        'created_by' => (string) $userId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $serialQuery = DB::table('serialnumbers')->where('serial_number', $serialNo);
                    if (! empty($serial['product_id'])) {
                        $serialQuery->where('product_id', $serial['product_id']);
                    }
                    $serialQuery->update([
                        'status' => 'sold',
                        'sold_date' => now()->toDateString(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('pos')->where('user_id', $userId)->delete();

                return $orderId;
            });
        } catch (HttpException $e) {
            if ($e->getStatusCode() === 422) {
                return response()->json(['error' => $e->getMessage()], 422);
            }

            throw $e;
        } catch (Throwable $e) {
            if (str_contains($e->getMessage(), 'Cart is empty') || str_contains($e->getMessage(), 'Insufficient stock')) {
                return response()->json(['error' => $e->getMessage()], 422);
            }

            throw $e;
        }

        return response()->json(['done' => true, 'order_id' => $orderId]);
    }

    public function TodaySell()
    {
        $sell = DB::table('orders')->where('ordered_on', now()->toDateString())->sum('total');

        return response()->json($sell);
    }

    public function TodayIncome()
    {
        $income = DB::table('orders')->where('ordered_on', now()->toDateString())->sum('sub_total');

        return response()->json($income);
    }

    public function TodayDue()
    {
        $due = DB::table('orders')->where('ordered_on', now()->toDateString())->sum('due');

        return response()->json($due);
    }

    public function TodayExpense()
    {
        $expense = DB::table('expenses')->where('spent_on', now()->toDateString())->sum('amount');

        return response()->json($expense);
    }

    public function Stockout()
    {
        $stockout = DB::table('products')->where('product_quantity', '<', '1')->get();

        return response()->json($stockout);
    }
}
