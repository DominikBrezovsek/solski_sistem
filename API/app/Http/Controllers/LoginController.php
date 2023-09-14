<?php

namespace App\Http\Controllers;

use App\Models\LoginModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class LoginController extends Controller
{

    public function checkLogin(Request $request){
        $login = LoginModel::where('username', '=', $request->username)->get();
        if (Hash::check($request->password, $login->password)){
            return response()->json([
                "message" => "User logged in sucessfully"
                ], "200");
        } else {
            return response()->json([
                "message" => "Username or password not matched!"
            ], "416");
        }
    }

    public function createLogin(Request $request){
        $login = LoginModel::where('username', '=', $request->username)->get();
        if ($login != '[]'){
            return response()->json([
                "message" => "Username already exists!",
                "error" => "Duplicate"
            ], "406");
        } else {
            $login = new LoginModel();
            $login->username = $request->username;
            $login->password = Hash::make($request->password);
            $login->type = $request->type;
            $login->save();

            return response()->json([
                "message" => "Login created successfully!",
            ], "201");
        }
    }
}
