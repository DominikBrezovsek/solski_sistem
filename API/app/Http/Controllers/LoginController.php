<?php

namespace App\Http\Controllers;

use App\Models\LoginModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class LoginController extends Controller
{

    public function checkLogin(Request $request){
        $login = LoginModel::where('username', '=', $request->email)->get();
        if (Hash::check($request->password, $login->password)){
            return response()->json([
                "message" => "User logged in sucessfully",
                "logged" => "success"
                ], "200");
        } else {
            return response()->json([
                "message" => "Username or password not matched!",
                "logged" => "false"
            ], "200");
        }
    }

    public function createLogin(Request $request){
        $login = LoginModel::where('username', '=', $request->username)->first();
        if ($login != '[]'){
            return response()->json([
                "message" => "Username already exists!",
                "error" => "Duplicate"
            ], "200");
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
