<?php

namespace App\Http\Controllers;

use App\Models\LoginModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use App\Models\AuthToken;
use App\Http\Controllers\AuthController;
use PHLAK\StrGen;

class LoginController extends Controller
{

    public function checkLogin(Request $request){
        $login = LoginModel::where('username', '=', $request->email)->first();
        if (Hash::check($request->password, $login->password)){
            $generator = new StrGen\Generator();
            $key = $generator->length(16)->generate();
            $id = $generator->length(20)->generate();
            $token = app('App\Http\Controllers\AuthController')->CreateToken($login->id, $key, $id);
            $request->session()->regenerate();
            $request->session()->put('user_id', $login->id);

            return response()->json([
                "message" => "User logged in sucessfully",
                "logged" => "success",
                "token" => $token
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
