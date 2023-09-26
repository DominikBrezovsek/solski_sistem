<?php

namespace App\Http\Controllers;

use App\Models\TeacherModel;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function returnTeacher(Request $request)
    {
        $teacher = TeacherModel::where('id', '=', $request->id)->first();
        return response()->json($teacher);
    }

    public function addTeacher(Request $request)
    {
        $email_exists = TeacherModel::where('email', '=', $request->email)->get();

        if ($email_exists != '[]') {
            return response()->json([
                "email_exists" => $email_exists,
                "message" => "Teacher with this email already exists!",
                "error" => "Duplicate"
            ], '200');
        } else {
            $teacher = new TeacherModel();
            $teacher->ime = $request->ime;
            $teacher->priimek = $request->priimek;
            $teacher->kabinet = $request->kabinet;
            $teacher->email = $request->email;
            $teacher->save();
            return response()->json([
                "message" => "teacher record created"
            ], 201);
        }

    }

    public function removeTeacher(Request $request) {
        $teacher = TeacherModel::where('email', '=', $request->email )->delete();
        return response()->json([
            "message" => "Teacher" . $request->email . "has been removed!"
        ], '201');
    }
}
