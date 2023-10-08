<?php

namespace App\Http\Controllers;

use App\Models\StudentSubjectTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\error;

class DashboardController extends Controller
{
    public function userSubjects(Request $request)
    {
        $user_id = session('loginId');
        if ($user_id) {
            $lastSubjects = DB::table('StudentSubjectTable')
                ->join('SubjectTable', 'SubjectTable.id', '=', 'StudentSubjectTable.subjectId')
                ->where('StudentSubjectTable.studentId', '=', $user_id)
                ->orderBy('StudentSubjectTable.lastAccess', 'desc')
                ->get();

            return response()->json([
                'subjects' => $lastSubjects
            ]);
        } else {
            return response()->json([
                'error' => 'session'
            ], 200);
        }

    }

    public function userAssignments(Request $request)
    {
        $user_id = session('loginId');
        if ($user_id) {
            $fistDueAss = DB::table('SubjectAssignmentTable')
                ->select('deadline', 'SubjectAssignmentTable.tittle', 'SubjectTable.subject')
                ->join('StudentSubjectTable', 'SubjectAssignmentTable.subjectId', '=', 'StudentSubjectTable.subjectId')
                ->join('SubjectTable', 'SubjectTable.id', '=', 'SubjectAssignmentTable.subjectId')
                ->where('StudentSubjectTable.studentId', '=', $user_id)
                ->orderBy('deadline', 'asc')
                ->get();
            return response()->json([
                'assignments' => $fistDueAss
            ]);

        } else {
            return response()->json([
                'error' => 'session'
            ], 200);
        }
    }
}
