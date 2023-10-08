<?php

namespace App\Http\Controllers;

use App\Models\SubjectAssignmentTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    public function getAssignment(Request $request){
        $assignment_id = $request->assignmentId;
        if ($assignment_id != null){
            $assignment = DB::table('SubjectAssignmentTable')
                ->select('SubjectAssignmentTable.*', 'TeacherTable.name', 'TeacherTable.surname', 'SubjectTable.subject')
                ->join('TeacherSubjectTable', 'TeacherSubjectTable.id', '=', 'SubjectAssignmentTable.tsId')
                ->join('TeacherTable', 'TeacherSubjectTable.teacherId', '=', 'TeacherTable.id')
                ->join('SubjectTable', 'SubjectTable.id', '=', 'TeacherSubjectTable.subjectId')
                ->where('SubjectAssignmentTable.id', '=', $assignment_id)
                ->get();
            return response()->json([
                'assignment' => $assignment
            ]);
        } else {
            return response()->json([
                'error' => 'id'
            ]);
        }
    }
    public function getAssignments(Request $request){
        $teacherId = session('teacherId');

        if ($teacherId != null){
            $assignment = DB::table('SubjectAssignmentTable')
                ->select('SubjectAssignmentTable.*', 'TeacherTable.name', 'TeacherTable.surname')
                ->join('TeacherSubjectTable', 'TeacherSubjectTable.id', '=', 'SubjectAssignmentTable.tsId')
                ->join('TeacherTable', 'TeacherSubjectTable.teacherId', '=', 'TeacherTable.id')
                ->where('TeacherTable.id', '=', $teacherId)
                ->get();
            return response()->json([
                'assignments' => $assignment
            ]);
        } else {
            return response()->json([
                'error' => 'id'
            ]);
        }
    }
}
