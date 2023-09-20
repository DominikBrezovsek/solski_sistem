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
        $email_exists = StudentsModel::where('email', '=', $request->email)->get();

        if ($email_exists != '[]') {
            return response()->json([
                "email_exists" => $email_exists,
                "message" => "Student  with this email already exists!",
                "error" => "duplicate"
            ], '200');
        } else {
            $student = new StudentsModel();
            $student->ime = $request->ime;
            $student->priimek = $request->priimek;
            $student->sola = $request->sola;
            $student->razred = $request->razred;
            $student->email = $request->email;
            $student->save();
            return response()->json([
                "message" => "student record created",
                "created" => "success"
            ], 201);
        }

    }

    public function removeStudent(Request $request){
        $subject = StudentsModel::where('email', '=', $request->email )->delete();
        return response()->json([
            "message" => "Student" . $request->email . "has been removed!"
        ], '201');
    }

    public function updateStudentClass(Request $request)
    {
        StudentsModel::where('email', '=', $request->email)->update(array('razred' => $request->razred));
        return response()->json([
            "message" => "student class record updated"
        ], 201);
    }
}
