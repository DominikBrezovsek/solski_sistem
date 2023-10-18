<?php

namespace App\Http\Controllers;

use App\Models\SubjectTable;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function getSubjects(Request $request)
    {
        $subjects = SubjectTable::all();
        return response()->json([
            'subjects' => $subjects
        ]);
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

        if ($subjectId != null){
            SubjectTable::where('id', '=', $subjectId)->delete();
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
        $key = $request->key;
        $description = $request->description;

        if($subjectId != null){
            SubjectTable::where('id', '=', $subjectId)->update(array(
                'subject' => $subject,
                'key' => $key,
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
