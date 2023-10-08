<?php

namespace App\Http\Controllers;

use App\Models\StudentSubjectTable;
use Illuminate\Http\Request;

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
}
