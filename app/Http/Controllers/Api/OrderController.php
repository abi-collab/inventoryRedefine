<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function AllOrder()
    {
        $order = DB::table('orders')
            ->join('customers', 'orders.customer_id', 'customers.id')
            ->select('customers.name', 'orders.*')
            ->orderBy('orders.id', 'DESC')->get();

        return response()->json($order);
    }

    public function TodayOrder()
    {
        $order = DB::table('orders')
            ->join('customers', 'orders.customer_id', 'customers.id')
            ->where('orders.ordered_on', now()->toDateString())
            ->select('customers.name', 'orders.*')
            ->orderBy('orders.id', 'DESC')->get();

        return response()->json($order);
    }

    public function OrderDetails($id)
    {
        $orders = DB::table('orders')
            ->join('customers', 'orders.customer_id', 'customers.id')
            ->where('orders.id', $id)
            ->select('customers.name', 'customers.phone', 'customers.address', 'orders.*')
            ->first();

        return response()->json($orders);
    }

    public function OrderDetailsAll($id)
    {
        $details = DB::table('order_details')
            ->join('products', 'order_details.product_id', 'products.id')
            ->where('order_details.order_id', $id)
            ->select('products.product_name', 'products.product_code', 'products.image', 'order_details.*')
            ->get();

        return response()->json($details);
    }

    public function SearchOrderDate(Request $request)
    {
        $orderdate = $request->date;
        try {
            $iso = Carbon::parse($orderdate)->toDateString();
        } catch (\Throwable) {
            $newdate = new DateTime($orderdate);
            $iso = $newdate->format('Y-m-d');
        }

        $order = DB::table('orders')
            ->join('customers', 'orders.customer_id', 'customers.id')
            ->select('customers.name', 'orders.*')
            ->where('orders.ordered_on', $iso)
            ->get();

        return response()->json($order);
    }

    public function SearchMonth(Request $request)
    {
        $order = DB::table('orders')
            ->join('customers', 'orders.customer_id', 'customers.id')
            ->select('customers.name', 'orders.*')
            ->get();

        return response()->json($order);
    }
}
