<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;

class ClassesController extends Controller
{
    public function getClasses(Request $request){
        $school = $request->school;
        $classes = Classes::where('naziv_sole', '=', $school)->get();
        if ($classes != '[]'){
            return response()->json($classes);
        }
        else {
            return response()->json([
                "error" => "No classes found!"
            ], 200);
        }
    }

}
