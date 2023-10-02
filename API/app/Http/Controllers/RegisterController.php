<?php

namespace App\Http\Controllers;

use App\Models\UserLoginTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function registerUser(Request $request){
        $username = $request->username;
        $password = Hash::make($request->password);
        $userType = $request->userType;

        $user = UserLoginTable::select('id')->where('username', '=', $username)->first();

        if($user != null) {
            return response()->json([
                "error" => "duplicate"
            ], 200);
        }
        if ($user == null)
        UserLoginTable::create([
            'username' => $username,
            'password' => $password,
            'userType' => $userType
        ]);

        $loginId = UserLoginTable::select('id')->where('username', '=', $username)->first();
        return response()->json($loginId);
    }
}
