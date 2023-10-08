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
        UserLoginTable::create([
            'username' => $username,
            'password' => $password,
            'userType' => $userType
        ]);

        $loginId = UserLoginTable::select('id')->where('username', '=', $username)->first();
        return response()->json($loginId);
    }

    public function deleteUser(Request $request){
        $loginId = $request->loginId;

        UserLoginTable::where('id', '=', $loginId)->delete();
        return response()->json([
            'success' => 'true'
        ]);
    }

    public function updateUser(Request $request){
        $loginId = $request->loginId;
        $username = $request->username;
        $password = Hash::make($request->password);

        $usernameTaken = UserLoginTable::select('id')->where('username', '=', $username)->first();

        if($usernameTaken != null){
            return response()->json([
                'error' => 'duplicate'
            ]);
        } else if ($password != null){
            UserLoginTable::where('id', '=', $loginId)->update(array('username' => $username, 'password' => $password));
        } else {
            UserLoginTable::where('id', '=', $loginId)->update(array('username' => $username));
        }
        return response()->json([
            'error' => 'unknown'
        ]);
    }
}
