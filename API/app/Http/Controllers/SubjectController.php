<?php

namespace App\Http\Controllers;

use App\Models\StudentSubjectTable;
use App\Models\SubjectTable;
use App\Models\TeacherSubjectTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubjectController extends Controller
{
    public function getSubjects(Request $request)
    {
        $search = $request->search;
        if ($search != null){
            $subjectsSearch = DB::table('SubjectTable')
                ->select('SubjectTable.*')
                ->where('subject', 'LIKE', '%'.$search.'%')
                ->orderBy('SubjectTable.subject', 'asc')
                ->get();

            return response()->json([
                'subjects' => $subjectsSearch
            ]);
        } else {
            $subjects = SubjectTable::all();
            return response()->json([
                'subjects' => $subjects
            ]);
        }

    }

    public function getSubject(Request $request){
        $subjectId = $request->subjectId;
        if ($subjectId != null){
            $subject = SubjectTable::where('id', '=', $subjectId)->get();
            return response()->json([
                'subject' => $subject
            ]);
        } else {
            return response()->json([
                'error' => 'subject'
            ]);
        }
    }

    public function createSubject(Request $request)
    {
        $subject = $request->subject;
        $description = $request->description;

        if ($subject != null && $description != null) {
            SubjectTable::create([
                'subject' => $subject,
                'description' => $description
            ]);

            return response()->json([
                'success' => 'true'
            ]);
        } else {
            return response()->json([
                'error' => 'data'
            ]);
        }
    }

    public function deleteSubject(Request $request){
        $subjectId = $request->subjectId;

        $assignmentExists = DB::table('SubjectTable')
            ->select('SubjectAssignmentTable.amId', 'SubjectAssignmentTable.tsId', 'SubjectTable.id')
            ->join('SubjectAssignmentTable', 'SubjectAssignmentTable.subjectId', '=', 'SubjectTable.id')
            ->where('SubjectTable.id', '=', $subjectId)
            ->first();

    if($assignmentExists != null && $subjectId != null){

        $assignments = DB::table('SubjectTable')
            ->select('SubjectAssignmentTable.amId', 'SubjectAssignmentTable.tsId', 'SubjectTable.id')
            ->join('SubjectAssignmentTable', 'SubjectAssignmentTable.subjectId', '=', 'SubjectTable.id')
            ->where('SubjectTable.id', '=', $subjectId)
            ->get();
        foreach ($assignments as $a){
            $material = DB::table('AssignmentMaterialTable')
                ->select('material', 'id')
                ->where('id', '=', $a->amId)
                ->get();
            foreach ($material as $item){
                Storage::delete('/public/ass_mat/'.$item->material);

                DB::table('AssignmentMaterialTable')
                    ->where('id', '=', $item->id)
                    ->delete();
            }

            DB::table('TeacherSubjectTable')
                ->where('id', '=', $a->tsId)
                ->delete();
            DB::table('StudentSubjectTable')
                ->where('subjectId', '=', $subjectId)
                ->delete();

            DB::table('SubjectTable')
                ->where('id', '=', $a->id)
                ->delete();
        }
        return response()->json([
            'success' => 'true'
        ]);
    } else if ($subjectId != null){
            SubjectTable::where('id', '=', $subjectId)->delete();
            TeacherSubjectTable::where('subjectId', '=', $subjectId)->delete();
            StudentSubjectTable::where('subjectId', '=', $subjectId)->delete();
            return response()->json([
                'success' => 'true'
            ]);
        } else {
            return response()->json([
                'error' => 'subject'
            ]);
        }
    }

    public function updateSubject(Request $request){
        $subjectId = $request->subjectId;
        $subject = $request->subject;
        $description = $request->description;

        if($subjectId != null){
            SubjectTable::where('id', '=', $subjectId)->update(array(
                'subject' => $subject,
                'description' => $description
            ));
            return response()->json([
                'success' => 'true'
            ]);
        } else{
            return response()->json([
                'error' => 'subject'
            ]);
        }
    }
}
