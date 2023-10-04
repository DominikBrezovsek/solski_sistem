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
                ->join('SubjectTable', 'id', '=', 'StudentSubjectTable.subjectId')
                ->where('studentId', '=', $user_id)
                ->orderBy('lastAccess', 'desc')
                ->get();

            return response()->json($lastSubjects);
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
                ->select('description', 'deadline','studentId',)
                ->join('StudentSubjectTable', 'SubjectAssignmentTable.subjectId', '=', 'StudentSubjectTable.subjectId')
                ->where('StudentSubjectTable.studentId', '=', $user_id)
                ->orderBy('deadline', 'asc')
                ->get();
            return response()->json($fistDueAss);
        } else {
            return response()->json([
                'error' => 'session'
            ], 200);
        }
    }
}
