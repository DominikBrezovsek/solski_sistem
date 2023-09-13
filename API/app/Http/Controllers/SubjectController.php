<?php

namespace App\Http\Controllers;

use App\Models\SubjectModel;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function addSubject(Request $request){
        $subject_exists = SubjectModel::where('ime_predmeta', '=', $request->subject)->get();
        if($subject_exists){
            return response()->json([
                "message" => "Subject already exists!",
                "error" => "Duplicate"
            ], '201');
        } else {


            $subject = new SubjectModel();
            $subject->ime_predmeta = $request->subject;
            $subject->opis = $request->description;
            $subject->kategorija = $request->category;
            $subject->save();
            return response()->json([
                "message" => "Subject added successfully"
            ], '201');
        }
    }

    public function removeSubject(Request $request){
        $subject = SubjectModel::where('ime_predmeta', '=', $request->subject )->delete();
        return response()->json([
            "message" => "Subject" . $request->subject . "has been removed!"
        ], '201');
    }

    public function returnSubject(Request $request)
    {
        $subject = SubjectModel::where('ime_predmeta', '=', $request->subject)->get();
        return response()->json($subject);
    }

    public function updateSubjectName(Request $request){
        $subject_exists = SubjectModel::where('ime_predmeta', '=', $request->subject)->get();
        if ($subject_exists) {
            return response()->json([
                "message" => "Subject already exists!",
                "error" => "Duplicate"
            ], '201');
        } else {
            $subject = SubjectModel::where('ime_predmeta', '=', $request->subject)->update(array('subject' => $request->new_subject));
            return response()->json([
                "message" => "New subject name has been set successfully"
            ], '201');
        }
    }

    public function updateSubjectDescription(Request $request){
        $subject = SubjectModel::where('ime_predmeta', '=', $request->subject)->update(array('opis' => $request->new_description));
        return response()->json([
            "message" => "New subject description has been set successfully"
        ], '201');
    }
    public function updateSubjectCategory(Request $request){
        $subject = SubjectModel::where('ime_predmeta', '=', $request->category)->update(array('kategorija' => $request->new_category));
        return response()->json([
            "message" => "New subject category has been set successfully"
        ], '201');
    }
}
