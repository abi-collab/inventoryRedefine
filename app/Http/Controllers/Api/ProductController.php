<?php

namespace App\Http\Controllers\Api;

use DB;
use Image;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{

//-----------------index------------------------------------------
    public function index()
    {
        $product=DB::table('products')
            ->join('categories','products.category_id','categories.id')
            // ->join('suppliers','products.supplier_id','suppliers.id')
            ->select('categories.category_name','products.*')
            ->orderBy('products.id','DESC')
            ->get();
        return response()->json($product);
    }

//------------------Insert/Store------------------------------------
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|max:255',
            'category_id' => 'required',
            'selling_price' => 'required',

            'manufacturer' => 'required',
            'connectivity' => 'required',
            'Processor' => 'required',
            'ram' => 'required',
            'hard_drive' => 'required',
            'hard_drive_size' => 'required',
            'hard_drive_byte' => 'required',
            'os' => 'required',
            'os_version' => 'required',

        ]);

        if($request->image){
            $position = strpos($request->image, ';');
            $sub=substr($request->image, 0 ,$position);
            $ext=explode('/', $sub)[1];
            $name=time().".".$ext;
            $img=Image::make($request->image)->resize(240,200);
            $upload_path='backend/product/';
            $image_url=$upload_path.$name;
            $img->save($image_url);

            $product = new Product;
            $product->product_name = $request->product_name;
            $product->category_id = $request->category_id;
            $product->selling_price = $request->selling_price;
            $product->image = $image_url;

            $product->manufacturer = $request->manufacturer;
            $product->connectivity = $request->connectivity;
            $product->Processor = $request->Processor;
            $product->ram = $request->ram;
            $product->hard_drive = $request->hard_drive;
            $product->hard_drive_size = $request->hard_drive_size;
            $product->hard_drive_byte = $request->hard_drive_byte;
            $product->os = $request->os;
            $product->os_version = $request->os_version;
            $product->save();
        }else{
            $product = new Product;
            $product->product_name = $request->product_name;
            $product->category_id = $request->category_id;
            $product->selling_price = $request->selling_price;

            $product->manufacturer = $request->manufacturer;
            $product->connectivity = $request->connectivity;
            $product->Processor = $request->Processor;
            $product->ram = $request->ram;
            $product->hard_drive = $request->hard_drive;
            $product->hard_drive_size = $request->hard_drive_size;
            $product->hard_drive_byte = $request->hard_drive_byte;
            $product->os = $request->os;
            $product->os_version = $request->os_version;
            $product->save();
        }
    }

//----------------------------Show(id)-------------------------------------------
    public function show($id)
    {
        $product=DB::table('products')->where('id',$id)->first();
        return response()->json($product);
    }

//---------------------------Edit/Update----------------------------------------
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|max:255',
            'category_id' => 'required',
            'selling_price' => 'required',

            'manufacturer' => 'required',
            'connectivity' => 'required',
            'Processor' => 'required',
            'ram' => 'required',
            'hard_drive' => 'required',
            'hard_drive_size' => 'required',
            'hard_drive_byte' => 'required',
            'os' => 'required',
            'os_version' => 'required',
        ]);
        $data=array();
        $data['product_name']=$request->product_name;
        $data['category_id']=$request->category_id;
        $data['selling_price']=$request->selling_price;
        
        $data['manufacturer']=$request->manufacturer;
        $data['connectivity']=$request->connectivity;
        $data['ram']=$request->ram;
        $data['hard_drive']=$request->hard_drive;
        $data['hard_drive_size']=$request->hard_drive_size;
        $data['hard_drive_byte']=$request->hard_drive_byte;
        $data['os']=$request->os;
        $data['os_version']=$request->os_version;

        $image=$request->newphoto;
        if ($image) {
            $position = strpos($image, ';');
            $sub=substr($image, 0 ,$position);
            $ext=explode('/', $sub)[1];
            $name=time().".".$ext;
            $img=Image::make($image)->resize(240,200);
            $upload_path='backend/product/';
            $image_url=$upload_path.$name;
            $success=$img->save($image_url);
            if  ($success) {
                $data['image']=$image_url;
                $img=DB::table('products')->where('id',$id)->first();
                $image_path = $img->image;
                if($image_path){
                    $done=unlink($image_path);
                }
                $user=DB::table('products')->where('id',$id)->update($data);
            }
        }else{
            $oldlogo=$request->image;
            $data['image']=$oldlogo;
            DB::table('products')->where('id',$id)->update($data);
        }
    }

//---------------------------Delete--------------------------------------------
    public function destroy($id)
    {
        $product=DB::table('products')->where('id',$id)->first();
        $image=$product->image;
        if ($image) {
            unlink($image);
            DB::table('products')->where('id',$id)->delete();
        }else{
            DB::table('products')->where('id',$id)->delete();
        }
    }
}
