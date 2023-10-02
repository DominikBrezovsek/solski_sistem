<?php

namespace App\Http\Controllers;

use App\Models\UserLoginTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\TokenController;

class UserLoginController extends TokenController
{
    public function checkLogin(Request $request){
        $username = $request->username;
        $password = $request->password;

        if ($username != "" & $password != ""){
            $user = UserLoginTable::select(['id', 'password', 'userType'])->where([
                'username' => $username,
            ])->first();
            if(Hash::check($password, $user->password)){
                $_SESSION['loginId'] = $user->id;
                return response()->json([
                    'jwt' => $this->generateToken($user->id),
                    'success' => 'true',
                    'type' => $user->userType
                ], 200);
            } else{
                return response()->json([
                    'error' => 'true'
                ]);
            }
        }
    }
}
