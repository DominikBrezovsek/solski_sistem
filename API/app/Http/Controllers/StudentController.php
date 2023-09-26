<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsModel;
use PhpParser\Node\Stmt\Catch_;

class StudentController extends Controller
{

    public function returnStudent(Request $request)
    {
        if ($request->email) {
            $student = StudentsModel::where('email', '=', $request->email)->get();
            return response()->json($student);
        }
        else if ($request->id){
            $student = StudentsModel::where('id', '=', $request->id)->first();
            return response()->json($student);
        }
        else{
            return "";
        }
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
            try {
                StudentsModel::create([
                    'ime' => $request->ime,
                    'priimek' => $request->priimek,
                    'sola' => $request->sola,
                    'razred' => $request->razred,
                    'email' => $request->email
                ]);
                return response()->json([
                    "message" => "student record created",
                    "created" => "success"
                ], 201);

            } catch (\Exception $e){
                return response()->json([
                    'error' => $e
                ]);
            }

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
