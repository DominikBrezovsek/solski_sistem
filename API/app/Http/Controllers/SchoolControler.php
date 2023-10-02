<?php

namespace App\Http\Controllers;

use App\Models\ClassTable;
use App\Models\SchoolTable;
use Illuminate\Http\Request;

class SchoolControler extends Controller
{
    public function getSchool(Request $request){
        $schools = SchoolTable::all();

        return response()->json($schools);
    }

    public function getClass(Request $request){
        $schoolId = $request->school;
        $classes = ClassTable::select(['class', 'id'])->where('schoolId', '=', $schoolId)->get();

        return response()->json($classes);
    }
}
