<?php

namespace App\Http\Controllers;

use App\Models\TeacherModel;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function returnTeacher(Request $request)
    {
        $teacher = TeacherModel::where('email', '=', $request->email)->get();
        return response()->json($teacher);
    }

    public function addTeacher(Request $request)
    {
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
