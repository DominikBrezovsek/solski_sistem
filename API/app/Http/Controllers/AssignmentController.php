<?php

namespace App\Http\Controllers;

use App\Models\SubjectAssignmentTable;
use App\Models\SubmissionTable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function getAssignment(Request $request)
    {
        $assignment_id = $request->assignmentId;
        if ($assignment_id != null) {
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

    public function getAssignments(Request $request)
    {
        $teacherId = session('teacherId');

        if ($teacherId != null) {
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

    public function submitAssignment(Request $request)
    {
        $assignmentId = $request->assignmentId;
        $subjectId = $request->subjectId;
        $file = $request->file('file');
        $request->file('file');
        $loginId = (session('loginId'));
        if ($loginId != null && $subjectId != null) {
            $sstId = DB::table('StudentSubjectTable AS SST')
                ->select('SST.id')
                ->join('StudentTable AS S', 'S.id', '=', 'SST.studentId')
                ->where(['loginId' => $loginId, 'SST.subjectId' => $subjectId])
                ->first();
            $handedIn = gmdate('Y-m-d h:i:s', time());
            $file_tittle = DB::table('SubjectAssignmentTable AS SAT')
                ->select('SAT.tittle', 'ST.name', 'ST.surname')
                ->join('StudentSubjectTable AS SST', 'SAT.subjectId', '=', 'SST.subjectId')
                ->join('StudentTable AS ST', 'SST.studentId', '=', 'ST.id')
                ->where('SAT.id', '=', $assignmentId)
                ->first();
            $fileName = ($file_tittle->surname . ' ' . $file_tittle->name . ' - ' . $file_tittle->tittle);
            $fileExists = SubmissionTable::where('file', '=', $fileName)->first();
            if ($file != null) {
                if($fileExists == null) {
                    Storage::putFileAs(
                        '/ass_sub', $file, $fileName
                    );
                    SubmissionTable::create([
                        'assignmentId' => $assignmentId,
                        'studSubjectId' => $sstId->id,
                        'handedInAt' => $handedIn,
                        'file' => $fileName
                    ]);

                    return response()->json([
                        'success' => true
                    ]);
                } else  {
                    return response()->json([
                        'error' => 'duplicate'
                    ]);
                }
            } else {
                return response()->json([
                    'error' => 'file'
                ]);
            }
        } else {
            return response()->json([
                'error' => 'session'
            ]);

        }
    }
}
