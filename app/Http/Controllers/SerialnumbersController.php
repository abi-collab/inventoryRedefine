<?php

namespace App\Http\Controllers;
use App\serialnumbers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SerialnumbersController extends Controller
{
    public function saveSerialNumbers(Request $request)
    { 
        $serial = new serialnumbers;
        $serial->serial_number = $request->serial_number;
        $serial->product_id = $request->product_id;
        $serial->supplier_id = $request->supplier_id;
        $serial->status = $request->status;
        $serial->createdby = $request->createdby;
        $serial->buying_price = $request->buying_price;
        $serial->sold_date = $request->sold_date;
        $serial->save();
    }

    public function getSerialNumbers(Request $request)
    {
        $serialnumbers=DB::table('serialnumbers')
        // ->join('products','serialnumbers.product_id','products.id')
        // ->orderBy('id','DESC')->get();
        // ->join('suppliers','serialnumbers.supplier_id','suppliers.id')
        ->get();
        return response()->json($serialnumbers);
        
    }

    public function getProductSerials($id)
    {
        $serialnumbers=DB::table('serialnumbers')
        ->join('products','serialnumbers.product_id','products.id')
        ->where('serialnumbers.product_id', $id)
        // ->orderBy('id','DESC')->get();
        ->join('suppliers','serialnumbers.supplier_id','suppliers.id')
        ->select('serialnumbers.serial_number',
                    'serialnumbers.created_at',
                    'products.product_name',
                    'suppliers.name',
                    'serialnumbers.status',
                    'serialnumbers.createdby', 
                    'serialnumbers.buying_price',
                    'serialnumbers.sold_date'
        )
        ->get();
        return response()->json($serialnumbers);
        
    }

    public function SerialNumberUpdate(Request $request,$id)
    {
        $data=array();
        // $data['id']=$request->id;
        $data['status']=$request->status;
        // $data['sold_date']=$request->sold_date;
        DB::table('serialnumbers')->where('id',$id)->update($data);
    }

    
}
