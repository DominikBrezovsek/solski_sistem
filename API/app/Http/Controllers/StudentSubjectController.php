<?php

namespace App\Http\Controllers;

use App\Models\StudentSubjectTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentSubjectController extends Controller
{
    public function enrollStudent(Request $request){
        $student_id = $request->studentId;
        $subject_id = $request->subjectId;
        $enrolledAt = gmdate('Y-m-d h:i:s \G\M\T', time());

        StudentSubjectTable::create([
            'studentId' => $student_id,
            'subjectId' => $subject_id,
            'enrolledAt' => $enrolledAt
        ]);

        return response()->json([
            'success' => 'true'
        ]);
    }

    public function getSubjects(Request $request){
        $loginId= session('loginId');
        if ($loginId != null) {
            $subjects = DB::table('StudentSubjectTable AS SST')
                ->select('ST.subject', 'ST.id')
                ->join('SubjectTable AS ST' , 'SST.subjectId', '=', 'ST.id')
                ->join('StudentTable AS S', 'SST.studentId', '=', 'S.id')
                ->where('S.loginId', '=', $loginId)
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
