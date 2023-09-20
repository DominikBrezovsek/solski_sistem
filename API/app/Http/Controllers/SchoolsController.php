<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class SchoolsController extends Controller
{
    public function getSchools(){
        $schools = DB::table('Schools')->get();

        return response()->json($schools->get('naziv'));
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
