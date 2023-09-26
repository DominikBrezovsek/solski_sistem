<?php

namespace App\Http\Controllers;

use App\Models\LoginModel;
use App\Models\StudentsModel;
use App\Models\TeacherModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use App\Models\AuthToken;
use App\Http\Controllers\AuthController;
use PHLAK\StrGen;
use function Laravel\Prompts\select;

class LoginController extends Controller
{

    public function checkLogin(Request $request){
        $login = LoginModel::where('username', '=', $request->email)->first();
        if ($login != '') {
            if (Hash::check($request->password, $login->password)) {
                $generator = new StrGen\Generator();
                $key = $generator->length(16)->generate();
                $id = $generator->length(20)->generate();
                $token = app('App\Http\Controllers\AuthController')->CreateToken($login->id, $key, $id);
                session_start();
                $_SESSION['user_id'] = $login->id;
                $_SESSION['user_type'] = $login->type;

                return response()->json([
                    "message" => "User logged in sucessfully",
                    "logged" => "success",
                    "token" => $token,
                    "type" => $_SESSION['user_type'],
                    "user_id" => $_SESSION['user_id']
                ], "200");
            } else {
                return response()->json([
                    "message" => "Username or password not matched!",
                    "logged" => "false"
                ], "200");
            }
        } else {
            return response()->json([
                "message" => "Username or password not matched!",
                "logged" => "false"
            ], "200");
        }
    }

    public function createLogin(Request $request){
        $login = LoginModel::where('username', '=', $request->username)->first();
        if ($login != ''){
            return response()->json([
                "message" => "Username already exists!",
                "error" => "Duplicate"
            ], "200");
        } else {
            if($request->type == "student"){
                $user_id = StudentsModel::where('email', '=', $request->email)->first();

            }
            else if ($request->type = "teacher"){
                $user_id = TeacherModel::where('email',  '=', $request->email)->first();
            }
            $login = new LoginModel();
            $login->username = $request->username;
            $login->password = Hash::make($request->password);
            $login->type = $request->type;
            $login->user_id = $user_id->id;
            $login->save();

            return response()->json([
                "message" => "Login created successfully!",
                "created" => "success"
            ], "201");
        }
    }

}
