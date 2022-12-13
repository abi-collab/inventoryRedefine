<?php

namespace App\Http\Controllers;

use App\Serials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SerialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // { 
    //     $serial = new Serials;
    //     $serial->invoiceNumber = $request->invoiceNumber;
    //     $serial->serialNo = $request->serialNo;
    //     $serial->customerId = $request->customerId;
    //     $serial->product_id = $request->product_id;
    //     $serial->product_name = $request->product_name;
    //     $serial->product_quantity = $request->product_quantity;
    //     $serial->product_price = $request->product_price;
    //     $serial->save();
    // }

    public function getSerials(Request $request)
    {
        $serials=DB::table('serials')->orderBy('id','DESC')->get();
        return response()->json($serials);
        
    }

    public function saveSerials(Request $request)
    { 
        $serial = new Serials;
        $serial->invoiceNumber = $request->invoiceNumber;
        $serial->serialNo = $request->serialNo;
        $serial->customerId = $request->customerId;
        $serial->customerName = $request->customerName;
        $serial->product_id = $request->product_id;
        $serial->product_name = $request->product_name;
        $serial->order_quantity = $request->order_quantity;
        $serial->product_price = $request->product_price;
        $serial->created_by = $request->created_by;
        $serial->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Serials  $serials
     * @return \Illuminate\Http\Response
     */
    public function show(Serials $serials)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Serials  $serials
     * @return \Illuminate\Http\Response
     */
    public function edit(Serials $serials)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Serials  $serials
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Serials $serials)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Serials  $serials
     * @return \Illuminate\Http\Response
     */
    public function destroy(Serials $serials)
    {
        //
    }
}
