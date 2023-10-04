<?php

namespace App\Http\Controllers;

use App\Models\StudentSubjectTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\error;

class DashboardController extends Controller
{
    public function dashboardData(Request $request)
    {
        $user_id = session('userId');
        if ($user_id) {
            $lastSubjects = DB::table('StudentSubjectTable')
                ->join('SubjectTable', 'subjectId', '=', 'id')
                ->where('studentId', '=', $user_id)
                ->orderBy('lastAccess', 'desc')
                ->get();

            $fistDueAss = DB::table('SubjectAssignmentTable')
                ->select('description', 'deadline', 'studentId')
                ->join('StudentSubjectTable', 'SubjectAssignmentTable.subjectId', '=', 'StudentSubjectTable.subjectId')
                ->where('StudentSubjectTable.studentId', '=', $user_id)
                ->orderBy('deadline', 'asc')
                ->get();
            return response()->json([
                'subjects' => $lastSubjects,
                'assignments' => $fistDueAss]);
        } else {
            return response()->json([
                'error' => 'session'
            ], 200);
        }

    }
}
