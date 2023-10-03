<?php

namespace App\Http\Controllers;

use App\Models\UserLoginTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\TokenController;
use function Laravel\Prompts\error;

class UserLoginController extends TokenController
{
    public function checkLogin(Request $request){
        $username = $request->username;
        $password = $request->password;

        if ($username != "" & $password != ""){
            $user = UserLoginTable::select(['id', 'password', 'userType'])->where([
                'username' => $username,
            ])->first();

            if($user == null){
                return response()->json([
                    'error' => 'credentials'
                ]);
            }
            if(Hash::check($password, $user->password)){
                session(['loginId' => $user->id]);
                return response()->json([
                    'jwt' => $this->generateToken($user->id),
                    'success' => 'true',
                    'type' => $user->userType
                ], 200);
            } else{
                return response()->json([
                    'error' => 'credentials'
                ]);
            }
        }
        return response()->json([
            'error' => 'credentials'
        ]);
    }

}
