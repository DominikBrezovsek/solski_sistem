<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function RegisterUser(Request $request){
        $username = $request->username;
        $password = Hash::make($request->password);
        $userType = $request->userType;
        $loginId = $request->loginId;
    }
}
