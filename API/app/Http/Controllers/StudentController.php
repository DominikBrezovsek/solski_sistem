<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsModel;

class StudentController extends Controller
{
    public function index()
    {
        $students =  StudentsModel::all();
        return response()->json($students);
    }

    public function show($email)
    {
        $student = StudentsModel::where('email', '=', $email)->get();
        return response()->json($student);
    }

    public function store(Request $request)
    {
        $student = new StudentsModel();
        $student->ime = $post->ime;
        $student->priimek = $post->priimek;
        $student->sola = $post->sola;
        $student->razred = $post->razred;
        $student->email = $post->email;
        $student->save();
        return response()->json([
            "message" => "student record created"
        ], 201);
    }
}
