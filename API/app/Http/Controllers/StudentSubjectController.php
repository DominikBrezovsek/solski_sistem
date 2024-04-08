<?php

namespace App\Http\Controllers;

use App\Models\StudentSubjectTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentSubjectController extends Controller
{
    public function enrollStudent(Request $request){
        $loginId = session('loginId');
        $subject_id = $request->subjectId;
        date_default_timezone_set('Europe/Ljubljana');
        $enrolledAt = date('Y-m-d h:i:s', time());

        $student_id = DB::table('StudentTable')
            ->select('id')
            ->where('loginId', '=', $loginId)
            ->first();
        $studentEnrolled = DB::table('StudentSubjectTable')
            ->select('studentId', 'subjectId')
            ->where('studentId', '=', $student_id->id)
            ->where('subjectId', '=', $subject_id)
            ->first();
        if ($studentEnrolled == null){
            StudentSubjectTable::create([
                'studentId' => $student_id->id,
                'subjectId' => $subject_id,
                'enrolledAt' => $enrolledAt
            ]);

            return response()->json([
                'success' => 'true'
            ]);
        } else {
            return response()->json([
                'error' => 'enrolled'
            ]);
        }


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

    public function removeSubject(Request $request){
        $loginId = session('loginId');
        $subjectId = $request->subjectId;
        if ($loginId != null && $subjectId != null){
            $studentId = DB::table('StudentTable')
                ->select('id')
                ->where('loginId', '=', $loginId)
                ->first();

            $studSubId = DB::table('StudentSubjectTable')
                ->select('id')
                ->where('studentId', '=', $studentId->id)
                ->where('subjectId', '=', $subjectId)
                ->first();

            $materialRemove = DB::table('SubmissionTable')
                ->select('file')
                ->where('studSubjectId', '=', $studSubId->id)
                ->get();
            foreach ($materialRemove as $item){
                Storage::delete('/public/ass_sub/'.$item->file);
            }

            DB::table('SubmissionTable')
                ->where('studSubjectId', '=', $studSubId->id)
                ->delete();

            DB::table('StudentSubjectTable')
                ->where('studentId', '=', $studentId->id)
                ->where('subjectId', '=', $subjectId)
                ->delete();
            return response()->json([
                'success' => 'true'
            ]);
        }
        else {
            return response()->json([
                'error' => 'data'
            ]);
        }
    }
}
