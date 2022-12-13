<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    function index() {
        $blog=Blog::query()->get();
        return response()->json([
            "status"=>true,
            "message"=>"",
            "data"=>$blog
        ]);
    }
    public function show($id)
    {
        $blog = Blog::query()->where("id", $id)->first();

        if ($blog == null) {
            return response()->json([
                "status" => false,
                "message" => "blog tidak ditemukan",
                "data" => null
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $blog
        ]);
    }

    public function store(Request $request)
    {
        $newName = "";
        $extension = $request->file('photo')->getClientOriginalExtension();
        $newName = $request->title.'-'.now()->timestamp.'.'.$extension;
        $request->file('photo')->storeAs('images',$newName);
        $request['cover']=$newName;
        $payload = $request->all();
        if (!isset($payload["cover"])) {
            return response()->json([
                "status" => false,
                "message" => "wajib ada cover",
                "data" => null
            ]);
        }
        if (!isset($payload["title"])) {
            return response()->json([
                "status" => false,
                "message" => "wajib ada judul",
                "data" => null
            ]);
        }
        if (!isset($payload["content"])) {
            return response()->json([
                "status" => false,
                "message" => "wajib ada content",
                "data" => null
            ]);
        }
        if (!isset($payload["author"])) {
            return response()->json([
                "status" => false,
                "message" => "wajib ada author",
                "data" => null
            ]);
        }


        $blog = Blog::query()->create($payload);
        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $blog
        ]);
    }
    function update($id,Request $request){
        if($request->file('photo')) {
            $newName = "";
            $extension = $request->file('photo')->getClientOriginalExtension();
            $newName = $request->title.'-'.now()->timestamp.'.'.$extension;
            $request->file('photo')->storeAs('images',$newName);
            $request['cover']=$newName;
            }
        $blog = Blog::query()->where('id',$id)->first();
        if($blog == null){
            return response()->json([
                "status" =>false,
                "message" => "data tidak ditemukan",
                "data" => null
            ]);
        }
        $blog->fill($request->all());
        $blog->save();
        return response()->json([
            'status' => true,
            'message' => 'data telah berubah',
            "data"=> $blog
        ]);
    }

    function destroy($id){
        $delete =  Blog::query()->where("id", $id)->delete();
        return response()->json([
            'status' =>true,
            'message' => 'data telah dihapus'
        ]);
    }
}
