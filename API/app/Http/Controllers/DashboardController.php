<?php

namespace App\Http\Controllers;

use App\Models\StudentSubjectTable;
use App\Models\SubjectAssignmentTable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\error;

class DashboardController extends Controller
{
    public function userSubjects(Request $request)
    {
        $studentId = session('studentId');
        if ($studentId != null) {
            $lastSubjects = DB::table('StudentSubjectTable')
                ->join('SubjectTable', 'SubjectTable.id', '=', 'StudentSubjectTable.subjectId')
                ->where('StudentSubjectTable.studentId', '=', $studentId)
                ->limit('3')
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
            $fistDueAss = DB::select("
    SELECT SAT.id, SAT.tittle, S.subject, SAT.deadline
    FROM SubjectAssignmentTable SAT
    JOIN StudentSubjectTable SST ON SAT.subjectId = SST.subjectId
    JOIN StudentTable ST ON SST.studentId = ST.id
    JOIN SubjectTable S ON SST.subjectId = S.id
    WHERE NOT EXISTS (
        SELECT SubmissionTable.id
        FROM SubmissionTable
        WHERE SubmissionTable.studSubjectId = SST.id
    )
    AND ST.loginId = $user_id
    ORDER BY SAT.deadline ASC
    LIMIT 5
");
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
