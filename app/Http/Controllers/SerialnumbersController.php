<?php

namespace App\Http\Controllers;
use App\serialnumbers;
use Illuminate\Http\Request;

class SerialnumbersController extends Controller
{
    public function saveSerialNumbers(Request $request)
    { 
        $serial = new serialnumbers;
        $serial->serial_number = $request->serial_number;
        $serial->product_id = $request->product_id;
        $serial->supplier_id = $request->supplier_id;
        $serial->save();
    }
}
