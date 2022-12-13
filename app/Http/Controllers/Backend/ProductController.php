<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    function index() {
        $product=Product::query()->get();
        return response()->json([
            "status"=>true,
            "message"=>"",
            "data"=>$product
        ]);
    }
    public function show($id)
    {
        $product = Product::query()->where("id", $id)->first();

        if ($product == null) {
            return response()->json([
                "status" => false,
                "message" => "product tidak ditemukan",
                "data" => null
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $product
        ]);
    }

    public function store(Request $request)
    {
        $newName = "";
        $extension = $request->file('photo')->getClientOriginalExtension();
        $newName = $request->title.'-'.now()->timestamp.'.'.$extension;
        $request->file('photo')->storeAs('images',$newName);
        $request['image']=$newName;

        $payload = $request->all();
        if (!isset($payload["image"])) {
            return response()->json([
                "status" => false,
                "message" => "wajib ada image",
                "data" => null
            ]);
        }
        if (!isset($payload["title"])) {
            return response()->json([
                "status" => false,
                "message" => "wajib ada nama product",
                "data" => null
            ]);
        }
        if (!isset($payload["description"])) {
            return response()->json([
                "status" => false,
                "message" => "wajib ada description",
                "data" => null
            ]);
        }
        if (!isset($payload["price"])) {
            return response()->json([
                "status" => false,
                "message" => "wajib ada harga",
                "data" => null
            ]);
        }
            if (!isset($payload["stock"])) {
                return response()->json([
                    "status" => false,
                    "message" => "wajib ada stok",
                    "data" => null
                ]);
        }


        $product = Product::query()->create($payload);
        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $product
        ]);
    }
    function update($id,Request $request){
        if($request->file('photo')) {
            $newName = "";
            $extension = $request->file('photo')->getClientOriginalExtension();
            $newName = $request->title.'-'.now()->timestamp.'.'.$extension;
            $request->file('photo')->storeAs('images',$newName);
            $request['image']=$newName;
            }
        $product = Product::query()->where('id',$id)->first();
        if($product == null){
            return response()->json([
                "status" =>false,
                "message" => "data tidak ditemukan",
                "data" => null
            ]);
        }

        $product->fill($request->all());
        $product->save();
        return response()->json([
            'status' => true,
            'message' => 'data telah berubah',
            "data"=> $product
        ]);
    }

    function destroy($id){
        $delete =  Product::query()->where("id", $id)->delete();
        return response()->json([
            'status' =>true,
            'message' => 'data telah dihapus'
        ]);
    }
}
