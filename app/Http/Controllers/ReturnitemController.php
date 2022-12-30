<?php

namespace App\Http\Controllers;

use App\Returnitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnitemController extends Controller
{
    public function saveReturns(Request $request)
    { 
        $return = new Returnitem;
        $return->return_no = $request->return_no;
        $return->invoiceNumber = $request->invoiceNumber;
        $return->serialNo = $request->serialNo;
        $return->product_id = $request->product_id;
        $return->product_name = $request->product_name;
        $return->created_by = $request->created_by;
        $return->remarks = $request->remarks;
        $return->status = $request->status;
        $return->save();

    }

    public function getReturns(Request $request)
    {
        $returns=DB::table('returnitems')->orderBy('id','DESC')->get();
        return response()->json($returns);
        
    }

    public function updateReturns(Request $request, Returnitem $Returnitem, $id) {
        $returns = Returnitem::findOrFail($id);
        $returns->update($request->all());
    }
}
