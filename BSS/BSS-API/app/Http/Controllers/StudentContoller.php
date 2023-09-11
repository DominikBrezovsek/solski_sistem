<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentContoller extends Controller
{
    public function index()
    {
        $students = Student::all();
        return response()->json($students);
    }

    public function show($email)
    {
        $student = Student::find($email);
        return response()->json($student);
    }
    public function store(Request $request)
    {
        $student = new Student;
        $student->ime = $request->ime;
        $student->priimek = $request->priimek;
        $student->sola = $request->sola;
        $student->oddelek = $request->oddelek;
        $student->naslov = $request->naslov;
        $student->posta = $request->posta;
        $student->postna_st = $request->postna_st;
        $student->telefonska = $request->telefonska;
        $student->email = $request->email;
        $student->save();
        return response()->json([
            "message" => "student record created"
        ], 201);
    }
}
