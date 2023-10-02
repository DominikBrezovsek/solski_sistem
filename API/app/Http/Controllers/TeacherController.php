<?php

namespace App\Http\Controllers;

use App\Models\TeacherTable;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function createTeacher(Request $request)
    {
        $name = $request->name;
        $surname = $request->surname;
        $email = $request->email;
        $loginId = $request->loginId;
        $cabinet = $request->cabinet;

        $checkEmail = TeacherTable::select('id')->where('email', '=', $email)->first();

        if ($checkEmail != "") {
            return response()->json([
                'error' => 'email-duplicate'
            ], 200);
        } else {
            TeacherTable::create([
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'cabinet' => $cabinet,
                'loginId' => $loginId,
            ]);

            return response()->json([
                'success' => 'true'
            ], 200);
        }
    }

    public function getTeacher(Request $request){
        $loginId = session('loginId');
        $teacher = TeacherTable::select(['name', 'surname', 'email'])->where('loginId', '=', $loginId)->first();
        return response()->json([$teacher]);
    }
}
