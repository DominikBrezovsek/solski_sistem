<?php

namespace App\Http\Controllers;

use App\Models\ClassTable;
use App\Models\StudentTable;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function createStudent(Request $request){
        $name = $request->name;
        $surname = $request->surname;
        $email = $request->email;
        $loginId = $request->loginId;
        $classId = $request->class;

        $checkEmail = StudentTable::select('id')->where('email', '=', $email)->first();

        if($checkEmail != ""){
            return response()->json([
                'error' => 'email-duplicate'
            ], 200);
        } else {
            StudentTable::create([
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'loginId' => $loginId,
                'classId' => $classId
            ]);

            return response()->json([
                'success' => 'true'
            ], 200);
        }



    }

    public function getStudent(Request $request){
        $loginId = $_SESSION['loginId'];
        $student = StudentTable::where('loginId', '=', $loginId)->first();
        return response()->json($student);
    }
}
