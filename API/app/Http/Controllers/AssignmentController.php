<?php

namespace App\Http\Controllers;

use App\Models\AssignmentMaterialTable;
use App\Models\SubjectAssignmentTable;
use App\Models\SubmissionTable;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function Laravel\Prompts\select;
use function Laravel\Prompts\table;

class AssignmentController extends Controller
{
    public function getAssignment(Request $request)
    {
        $assignment_id = $request->assignmentId;
        if ($assignment_id != null) {
            $assignment = DB::table('SubjectAssignmentTable')
                ->select('SubjectAssignmentTable.*', 'TeacherTable.name', 'TeacherTable.surname', 'SubjectTable.subject', 'SubjectTable.id AS sId')
                ->join('TeacherSubjectTable', 'TeacherSubjectTable.id', '=', 'SubjectAssignmentTable.tsId')
                ->join('TeacherTable', 'TeacherSubjectTable.teacherId', '=', 'TeacherTable.id')
                ->join('SubjectTable', 'SubjectTable.id', '=', 'TeacherSubjectTable.subjectId')
                ->where('SubjectAssignmentTable.id', '=', $assignment_id)
                ->first();
            $material = DB::table('AssignmentMaterialTable')
                ->select('material')
                ->where('id', '=', $assignment->amId)
                ->first();
            return response()->json([
                'assignment' => $assignment
            ]);
        } else {
            return response()->json([
                'error' => 'id'
            ]);
        }
    }

    public function returnFile(Request $request)
    {
        $assignment_id = $request->assignmentId;
        if ($assignment_id != null) {
            $assignment = DB::table('SubjectAssignmentTable')
                ->select('SubjectAssignmentTable.*', 'TeacherTable.name', 'TeacherTable.surname', 'SubjectTable.subject', 'SubjectTable.id AS sId')
                ->join('TeacherSubjectTable', 'TeacherSubjectTable.id', '=', 'SubjectAssignmentTable.tsId')
                ->join('TeacherTable', 'TeacherSubjectTable.teacherId', '=', 'TeacherTable.id')
                ->join('SubjectTable', 'SubjectTable.id', '=', 'TeacherSubjectTable.subjectId')
                ->where('SubjectAssignmentTable.id', '=', $assignment_id)
                ->first();
            $material = DB::table('AssignmentMaterialTable')
                ->select('material')
                ->where('id', '=', $assignment->amId)
                ->first();
            $file = storage_path() . '/app/public/ass_mat/' . $material->material;
            $mimeType = Storage::mimeType('public/ass_mat/' . $material->material);
            $headers = ['Content-Type' => $mimeType];
            return response()->download($file, $material->material, $headers);
        } else {
            return response()->json([
                'error' => 'id'
            ]);
        }
    }

    public function returnSubFile(Request $request)
    {
        $subId = $request->submissionId;
        if ($subId != null) {
            $subFile = DB::table('SubmissionTable')
                ->select('file')
                ->where('id', '=', $subId)
                ->first();
            $file = storage_path() . '/app/public/ass_sub/' . $subFile->file;
            $mimeType = Storage::mimeType('public/ass_sub/' . $subFile->file);
            $headers = ['Content-Type' => $mimeType];
            return response()->download($file, $subFile->file, $headers);
        } else {
            return response()->json([
                'error' => 'id'
            ]);
        }
    }

    public function getAssignments(Request $request)
    {
        date_default_timezone_set('Europe/Ljubljana');
        $subjectId = $request->subjectId;
        $loginId = session('loginId');
        $userType = $request->userType;
        if ($userType == 'teacher') {
            $assignment = DB::table('SubjectAssignmentTable')
                ->select('SubjectAssignmentTable.*', 'TeacherTable.name', 'TeacherTable.surname')
                ->join('TeacherSubjectTable', 'TeacherSubjectTable.id', '=', 'SubjectAssignmentTable.tsId')
                ->join('TeacherTable', 'TeacherSubjectTable.teacherId', '=', 'TeacherTable.id')
                ->where('TeacherTable.loginId', '=', $loginId)
                ->where('SubjectAssignmentTable.subjectId', '=', $subjectId)
                ->get();
            return response()->json([
                'assignments' => $assignment
            ]);
        } else if ($userType == 'student') {
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
        AND SubmissionTable.assignmentId = SAT.id
    )
    AND ST.loginId = $loginId
    AND S.id = $subjectId
    ORDER BY SAT.deadline ASC
");
            return response()->json([
                'assignments' => $fistDueAss
            ]);
        }
        return response()->json([
            'error' => 'data'
        ]);

    }

    public function submitAssignment(Request $request)
    {
        date_default_timezone_set('Europe/Ljubljana');
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
            $handedIn = date('Y-m-d H:i:s', time());
            $file_tittle = DB::table('SubjectAssignmentTable AS SAT')
                ->select('SAT.tittle', 'ST.name', 'ST.surname')
                ->join('StudentSubjectTable AS SST', 'SAT.subjectId', '=', 'SST.subjectId')
                ->join('StudentTable AS ST', 'SST.studentId', '=', 'ST.id')
                ->where('SAT.id', '=', $assignmentId)
                ->first();
            $fileNameExt = ($file_tittle->surname . ' ' . $file_tittle->name . ' - ' . $file_tittle->tittle . '.' . $file->getClientOriginalExtension());
            $fileName = ($file_tittle->surname . ' ' . $file_tittle->name . ' - ' . $file_tittle->tittle);
            $fileExists = DB::table('SubmissionTable')
                ->where('file', 'like', $fileName . '%')
                ->where('studSubjectId', '=', $sstId->id)
                ->first();
            if ($file != null) {
                if ($fileExists == null) {
                    Storage::putFileAs(
                        '/public/ass_sub', $file, $fileNameExt,
                    );
                    SubmissionTable::create([
                        'assignmentId' => $assignmentId,
                        'studSubjectId' => $sstId->id,
                        'handedInAt' => $handedIn,
                        'file' => $fileNameExt
                    ]);

                    return response()->json([
                        'success' => true
                    ]);
                } else if ($fileExists != null && $request->resubmit == "true") {
                    $deleteFile = $fileExists->file;

                    Storage::disk('local')->delete('/public/ass_sub/' . $deleteFile);
                    SubmissionTable::where(['file' => $deleteFile, 'studSubjectId' => $sstId->id])->delete();

                    Storage::putFileAs(
                        '/public/ass_sub', $file, $fileNameExt,
                        'public'
                    );
                    SubmissionTable::create([
                        'assignmentId' => $assignmentId,
                        'studSubjectId' => $sstId->id,
                        'handedInAt' => $handedIn,
                        'file' => $fileNameExt
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

    public function getSubmission(Request $request)
    {
        $loginId = session('loginId');
        $subjectId = $request->subjectId;


        if ($loginId != null && $subjectId != null) {
            $submission = DB::table('SubmissionTable AS SUB')
                ->select('SAT.id', 'SAT.tittle', 'S.subject', 'SUB.handedInAt', 'SUB.file')
                ->join('StudentSubjectTable AS SST', 'SUB.studSubjectId', '=', 'SST.id')
                ->join('StudentTable AS ST', 'ST.id', '=', 'SST.studentId')
                ->join('SubjectAssignmentTable AS SAT', 'SUB.assignmentId', '=', 'SAT.id')
                ->join('SubjectTable AS S', 'SST.subjectId', '=', 'S.id')
                ->where('ST.loginId', '=', $loginId)
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

    public function getSubmissions(Request $request)
    {
        $loginId = session('loginId');
        $subjectId = $request->subjectId;

        if ($loginId != null && $subjectId != null) {
            $submissions = DB::table('SubmissionTable')
                ->select('SubmissionTable.id', 'StudentTable.name', 'StudentTable.surname', 'SubjectAssignmentTable.tittle', 'SubmissionTable.handedInAt')
                ->join('StudentSubjectTable', 'StudentSubjectTable.id', '=', 'SubmissionTable.studSubjectId')
                ->join('StudentTable', 'StudentTable.id', '=', 'StudentSubjectTable.studentId')
                ->join('SubjectAssignmentTable', 'SubjectAssignmentTable.id', '=', 'assignmentId')
                ->where('StudentSubjectTable.subjectId', '=', $subjectId)
                ->get();
            return response()->json([
                'submissions' => $submissions
            ]);
        } else {
            return response()->json([
                'error' => 'data'
            ]);
        }
    }

    public function getAllSubmissions(Request $request)
    {
        $loginId = session('loginId');

        if ($loginId != null) {
            $submissions = DB::table('SubmissionTable')
                ->select('SubmissionTable.id', 'StudentTable.name', 'StudentTable.surname', 'SubjectAssignmentTable.tittle', 'SubmissionTable.handedInAt',)
                ->join('StudentSubjectTable', 'StudentSubjectTable.id', '=', 'SubmissionTable.studSubjectId')
                ->join('StudentTable', 'StudentTable.id', '=', 'StudentSubjectTable.studentId')
                ->join('SubjectAssignmentTable', 'SubjectAssignmentTable.id', '=', 'assignmentId')
                ->join('TeacherSubjectTable', 'TeacherSubjectTable.teacherId', '=', 'SubjectAssignmentTable.tsId')
                ->join('TeacherTable', 'TeacherSubjectTable.teacherId', '=', 'teacherId')
                ->where('TeacherTable.loginId', '=', $loginId)
                ->orderBy('SubmissionTable.handedInAt', 'desc')
                ->limit('5')
                ->distinct()
                ->get();
            return response()->json([
                'submissions' => $submissions
            ]);
        } else {
            return response()->json([
                'error' => 'data'
            ]);
        }
    }

    public function deleteSubmission(Request $request)
    {
        $loginId = session('loginId');
        $subjectId = $request->subjectId;
        $deleteFile = $request->fileName;
        if ($loginId != null && $subjectId != null) {
            $sstId = DB::table('StudentSubjectTable AS SST')
                ->select('SST.id')
                ->join('StudentTable AS S', 'S.id', '=', 'SST.studentId')
                ->where(['loginId' => $loginId, 'SST.subjectId' => $subjectId])
                ->first();


            Storage::disk('local')->delete('/public/ass_sub/' . $deleteFile);
            SubmissionTable::where(['file' => $deleteFile, 'studSubjectId' => $sstId->id])->delete();

            return response()->json([
                'success' => 'true'
            ]);
        } else {
            return response()->json([
                'error' => 'subjectId'
            ]);
        }
    }

    public function addAssignment(Request $request)
    {
        date_default_timezone_set('Europe/Ljubljana');
        $subjectId = $request->subjectId;
        $material = $request->file('material');
        $title = $request->title;
        $description = $request->desc;
        $deadline = date('Y-m-d H:i:s', strtotime($request->deadline));
        $loginId = session('loginId');
        if ($loginId != null) {
            $ts = DB::table('TeacherSubjectTable AS TST')
                ->select('TST.id AS tId', 'TST.subjectId', 'TST.teacherId', 'T.*')
                ->join('TeacherTable AS T', 'T.id', '=', 'TST.teacherId')
                ->where('T.loginId', '=', $loginId)
                ->first();
            if ($material != null && $description != null && $title != null && $subjectId != null && $deadline != null) {
                $fileName = $material->getClientOriginalName();
                Storage::putFileAs(
                    '/public/ass_mat', $material, $fileName,
                );

                AssignmentMaterialTable::create([
                    'material' => $fileName,
                    'addedAt' => date('Y-m-d H:i:s', time()),
                    'author' => $ts->teacherId
                ]);

                $amId = DB::table('AssignmentMaterialTable AS AMT')
                    ->select('AMT.id')
                    ->join('TeacherTable AS T', 'AMT.author', '=', 'T.id')
                    ->where('AMT.material', '=', $fileName)
                    ->where('T.loginId', '=', $loginId)
                    ->first();

                SubjectAssignmentTable::create([
                    'subjectId' => $subjectId,
                    'tsId' => $ts->tId,
                    'amId' => $amId->id,
                    'tittle' => $title,
                    'description' => $description,
                    'deadline' => $deadline,
                    'givenAt' => date('Y-m-d H:i:s', time()),
                ]);
                return response()->json([
                    'success' => 'true'
                ]);
            } else {
                return response()->json([
                    'error' => 'data'
                ]);
            }


        } else {
            return response()->json([
                'error' => 'data'
            ]);
        }
    }

    public function deleteAssignment(Request $request)
    {
        $assignmentId = $request->assignmentId;
        $subjectId = $request->subjectId;
        $loginId = session('loginId');
        $evenIf = $request->evenIf;

        $hasSubmissions = DB::table('SubmissionTable')
            ->select('id')
            ->where('assignmentId', '=', $assignmentId)
            ->first();

        if ($hasSubmissions!= null && $evenIf == null) {
            return response()->json([
                'error' => 'hasSubs'
            ]);
        }

        if ($hasSubmissions != null && $evenIf == true){
            $amId = DB::table('SubjectAssignmentTable')
                ->select('amId')
                ->where('id', '=', $assignmentId)
                ->where('subjectId', '=', $subjectId)
                ->first();

            $material = DB::table('AssignmentMaterialTable')
                ->select('material')
                ->join('TeacherTable', 'TeacherTable.id', '=', 'author')
                ->join('TeacherSubjectTable', 'TeacherSubjectTable.teacherId', '=', 'TeacherTable.id')
                ->where('AssignmentMaterialTable.id', '=', $amId->amId)
                ->where('TeacherTable.loginId', '=', $loginId)
                ->first();

            $submissions = DB::table('SubmissionTable')
                ->select('file')
                ->where('assignmentId', '=', $amId->amId)
                ->get();

            foreach ($submissions as $submission) {
                Storage::delete('/public/ass_sub/' . $submission->file);
            }

            DB::table('SubmissionTable')
                ->where('assignmentId', '=', $amId->amId)
                ->delete();

            Storage::disk('local')->delete('public/ass_mat/' . $material->material);

            DB::table('SubjectAssignmentTable')
                ->where('id', '=', $assignmentId)
                ->where('subjectId', '=', $subjectId)
                ->delete();

            DB::table('AssignmentMaterialTable')
                ->where('id', '=', $amId->amId)
                ->delete();

            return response()->json([
                'success' => 'true'
            ]);

        } else if ($assignmentId != null && $subjectId != null && $loginId != null && $hasSubmissions == null) {

            $amId = DB::table('SubjectAssignmentTable')
                ->select('amId')
                ->where('id', '=', $assignmentId)
                ->where('subjectId', '=', $subjectId)
                ->first();

            $material = DB::table('AssignmentMaterialTable')
                ->select('material')
                ->join('TeacherTable', 'TeacherTable.id', '=', 'author')
                ->join('TeacherSubjectTable', 'TeacherSubjectTable.teacherId', '=', 'TeacherTable.id')
                ->where('AssignmentMaterialTable.id', '=', $amId->amId)
                ->where('TeacherTable.loginId', '=', $loginId)
                ->first();

            Storage::disk('local')->delete('public/ass_mat/' . $material->material);

            DB::table('SubjectAssignmentTable')
                ->where('id', '=', $assignmentId)
                ->where('subjectId', '=', $subjectId)
                ->delete();

            DB::table('AssignmentMaterialTable')
                ->where('id', '=', $amId->amId)
                ->delete();

            return response()->json([
                'success' => 'true'
            ]);
        } else {
            return response()->json([
                'error' => 'data'
            ]);
        }

    }

    public function updateAssignment(Request $request)
    {
        $assignmentId = $request->assignmentId;
        $subjectId = $request->subjectId;
        $loginId = session('loginId');
        $material = $request->file('material');
        $title = $request->title;
        $description = $request->desc;
        $deadline = date('Y-m-d H:i:s', strtotime($request->deadline));

        if ($loginId != null) {
            if ($assignmentId != null && $subjectId != null && $material != null) {

                $amId = DB::table('SubjectAssignmentTable')
                    ->select('amId', 'TeacherTable.id AS tId')
                    ->join('TeacherSubjectTable', 'SubjectAssignmentTable.tsId', '=', 'SubjectAssignmentTable.tsId')
                    ->join('TeacherTable', 'TeacherSubjectTable.teacherId', '=', 'TeacherTable.id')
                    ->where('id', '=', $assignmentId)
                    ->first();

                $material = DB::table('AssignmentMaterialTable')
                    ->select('material')
                    ->where('id', '=', $amId->amId)
                    ->where('author', '=', $amId->tId)
                    ->first();

                Storage::disk('local')->delete('/ass_mat/' . $material->material);

                DB::table('AssignmentMaterialTable')
                    ->where('id', '=', $amId->amId)
                    ->delete();

                $fileName = $material->getClientOriginalName();
                Storage::putFileAs(
                    '/public/ass_mat', $material, $fileName
                );
                DB::table('AssignmentMaterialTable')
                    ->insert(array(
                        'material' => $fileName,
                        'author' => $amId->tId
                    ));

                $assignment = DB::table('AssignmentMaterialTable')
                    ->select('AssignmentMaterialTable.id', 'TeacherSubjectTable.id AS tsId')
                    ->join('TeacherTable', 'TeacherTable.id', '=', 'AssignmentMaterialTable.author')
                    ->join('TeacherSubjectTable', 'TeacherSubjectTable.teacherId', '=', 'TeacherTable.id')
                    ->where('material', '=', $fileName)
                    ->where('TeacherTable.loginId', '=', $loginId)
                    ->first();

                DB::table('SubjectAssignmentTable')
                    ->where('id', '=', $assignmentId)
                    ->update(array(
                        'tittle' => $title,
                        'description' => $description,
                        'deadline' => $deadline,
                        'amId' => $assignment->id,
                        'tsId' => $assignment->tsId
                    ));

                return response()->json([
                    'success' => 'true'
                ]);
            } else if ($material == null) {

                $assignment = DB::table('TeacherSubjectTable')
                    ->select('TeacherSubjectTable.id')
                    ->join('TeacherTable', 'TeacherTable.id', '=', 'TeacherSubjectTable.teacherId')
                    ->where('TeacherTable.loginId', '=', $loginId)
                    ->where('TeacherSubjectTable.subjectId', '=', $subjectId)
                    ->first();


                SubjectAssignmentTable::where('id', '=', $assignmentId)->update(array(
                    'tittle' => $title,
                    'description' => $description,
                    'subjectId' => $subjectId,
                    'tsId' => $assignment->id
                ));
                return response()->json([
                    'success' => 'true'
                ]);
            } else {
                return response()->json([
                    'error' => 'data'
                ]);
            }
        } else {
            return response()->json([
                'error' => 'data'
            ]);
        }
    }
}

