<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;

class SchoolsController extends Controller
{
    public function getSchools(){
        $schools = School::all()->get('naziv');

        return response()->json($schools);
    }
}
