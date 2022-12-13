<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    function index() {
        $pengguna=User::query()->get();
        return response()->json([
            "status"=>true,
            "message"=>"",
            "data"=>$pengguna
        ]);
    }
    public function show($id)
    {
        $pengguna = User::query()->where("id", $id)->first();

        if ($pengguna == null) {
            return response()->json([
                "status" => false,
                "message" => "Pengguna tidak ditemukan",
                "data" => null
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $pengguna
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->all();
        if (!isset($payload["name"])) {
            return response()->json([
                "status" => false,
                "message" => "wajib ada nama",
                "data" => null
            ]);
        }
        if (!isset($payload["email"])) {
            return response()->json([
                "status" => false,
                "message" => "wajib ada email",
                "data" => null
            ]);
        }
        if (!isset($payload["password"])) {
            return response()->json([
                "status" => false,
                "message" => "wajib ada password",
                "data" => null
            ]);
        }

        $pengguna = User::query()->create($payload);
        return response()->json([
            "status" => true,
            "message" => "",
            "data" => $pengguna
        ]);
    }
    function update($id,Request $request){
        $pengguna = User::query()->where('id',$id)->first();
        if($pengguna == null){
            return response()->json([
                "status" =>false,
                "message" => "data tidak ditemukan",
                "data" => null
            ]);
        }
        $pengguna->fill($request->all());
        $pengguna->save();
        return response()->json([
            'status' => true,
            'message' => 'data telah berubah',
            "data"=> $pengguna
        ]);
    }

    function destroy($id){
        $delete =  User::query()->where("id", $id)->delete();
        return response()->json([
            'status' =>true,
            'message' => 'data telah dihapus'
        ]);
    }
}
