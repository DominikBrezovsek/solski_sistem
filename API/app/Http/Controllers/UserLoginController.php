<?php

namespace App\Http\Controllers;

use App\Models\UserLoginTable;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    public function checkLogin(Request $request){
        $username = $request->username;
        $password = $request->password;
        $userType = $request->userType;

        if ($username != "" & $password != "" & $userType != ""){
            UserLoginTable::select(['id', 'username'])->where([
                'username' => $username,
                'userType' => $userType
            ])->first();
        }



    }
}
