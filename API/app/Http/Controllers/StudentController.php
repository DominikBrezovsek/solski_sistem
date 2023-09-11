<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsModel;

class StudentController extends Controller
{

    public function returnStudent(Request $request)
    {
        $student = StudentsModel::where('email', '=', $request->email)->get();
        return response()->json($student);
    }

    public function addStudent(Request $request)
    {
        $student = new StudentsModel();
        $student->ime = $request->ime;
        $student->priimek = $request->priimek;
        $student->sola = $request->sola;
        $student->razred = $request->razred;
        $student->email = $request->email;
        $student->save();
        return response()->json([
            "message" => "student record created"
        ], 201);
        
    }

    public function updateStudentClass(Request $request)
    {
        StudentsModel::where('email', '=', $request->email)->update(array('razred' => $request->razred));
        return response()->json([
            "message" => "student class record updated"
        ], 201);
    }
}
