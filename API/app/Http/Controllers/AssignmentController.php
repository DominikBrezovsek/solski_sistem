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
        $loginId = session('loginId');
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
        } else if ($teacherId == null && $loginId != null) {
            $subjectId = $request->subjectId;
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
    AND ST.loginId = $loginId
    AND S.id = $subjectId
    ORDER BY SAT.deadline ASC
");
            return response()->json([
                'assignments' => $fistDueAss
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
            $fileExists = SubmissionTable::where(['file' => $fileName, 'studSubjectId' => $sstId->id])->first();
            if ($file != null) {
                if ($fileExists == null) {
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
                } else if( $fileExists != null && $request->resubmit == "true") {
                    $deleteFile = $fileExists->file;

                    Storage::disk('local')->delete('/ass_sub/'. $deleteFile);
                    SubmissionTable::where(['file' => $fileName, 'studSubjectId' => $sstId->id])->delete();

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
                        'resubmitted' => true
                    ]);
                } else {
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

    public function getSubmission(Request $request){
        $loginId = session('loginId');
        $subjectId = $request->subjectId;

        if ($loginId != null && $subjectId != null){
            $submission = DB::table('SubmissionTable AS SUB')
                ->select('SAT.id', 'SAT.tittle', 'S.subject', 'SUB.handedInAt', 'SUB.file')
                ->join('StudentSubjectTable AS SST', 'SUB.studSubjectId', '=', 'SST.id')
                ->join('StudentTable AS ST', 'ST.id', '=', 'SST.studentId')
                ->join('SubjectAssignmentTable AS SAT', 'SUB.assignmentId', '=', 'SAT.id')
                ->join('SubjectTable AS S', 'SST.subjectId', '=', 'S.id')
                ->where('ST.id', '=', $loginId)
                ->where('S.id', '=', $subjectId)
                ->get();

            return response()->json([
                'assignments' => $submission
            ]);
        } else {
            return response()->json([
                'error' => 'subjectId'
            ]);
        }
    }
}

