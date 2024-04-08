<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherSubjectController extends Controller
{
    public function getSubjects(){
        $loginId= session('loginId');
        if ($loginId != null) {
            $subjects = DB::table('TeacherSubjectTable AS TS')
                ->select('ST.subject', 'ST.id')
                ->join('SubjectTable AS ST' , 'TS.subjectId', '=', 'ST.id')
                ->join('TeacherTable AS T', 'TS.teacherId', '=', 'T.id')
                ->where('T.loginId', '=', $loginId)
                ->get();

            return response()->json([
                'subjects' => $subjects
            ]);
        } else {
            return response()->json([
                'error' => 'session'
            ]);
        }
    }
}
