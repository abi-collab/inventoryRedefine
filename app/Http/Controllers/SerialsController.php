<?php

namespace App\Http\Controllers;

use App\Models\Serial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SerialsController extends Controller
{
    public function getSerials(Request $request)
    {
        $serials = DB::table('serials')->orderBy('id', 'DESC')->get();

        return response()->json($serials);
    }

    public function saveSerials(Request $request)
    {
        $serial = new Serial;
        $serial->invoiceNumber = $request->invoiceNumber;
        $serial->serialNo = $request->serialNo;
        $serial->customerId = $request->customerId;
        $serial->customerName = $request->customerName;
        $serial->product_id = $request->product_id;
        $serial->product_name = $request->product_name;
        $serial->category_id = $request->category_id;
        $serial->order_quantity = $request->order_quantity;
        $serial->product_price = $request->product_price;
        $serial->created_by = $request->created_by;
        $serial->save();
    }
}
