<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class SchoolsController extends Controller
{
    public function getSchools(){
        $schools = School::where('naziv', '!=', null)->get('naziv');

        return response()->json($schools);
    }

    public function createSchool(Request $request){
        $naziv = $request->naziv;
        $school_exists = School::where('naziv', '=', $naziv)->get();
        if ($school_exists != '[]') {
            return response()->json([
                "error" => "duplicate"
            ], 200);
        } else {
            $school = new School();
            $school->naziv = $naziv;
            $school->save();

            return response()->json([
                "created" => "true"
            ], 201);
        }
    }
}
