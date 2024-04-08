<?php

namespace App\Http\Controllers;

use App\Models\ClassTable;
use App\Models\SchoolTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{

    /* CRUD operations for SchoolTable */
    public function getSchool(Request $request)
    {
        $schools = SchoolTable::all();
        return response()->json($schools);
    }

    public function createSchool(Request $request)
    {
        $schoolExists = SchoolTable::select('id')->where('name', '=', $request->name)->first();

        if ($schoolExists == null) {
            $newSchool = SchoolTable::create([
                'name' => $request->name
            ]);

            return response()->json([
                'success' => 'true'
            ]);
        } else {
            return response()->json([
                "error" => 'duplicate'
            ]);
        }
    }

    public function deleteSchool(Request $request)
    {

        if ($request->schoolId != null) {
            SchoolTable::where('id', '=', $request->schoolId)->delete();
            return response()->json([
                "success" => 'true'
            ]);
        } else {
            return response()->json([
                "error" => 'empty'
            ]);
        }
    }

    public function updateSchool(Request $request)
    {
        $schoolExists = SchoolTable::select('id')->where('name', '=', $request->name)->first();

        if ($schoolExists != null) {
            SchoolTable::where('id', '=', $request->schoolId)->update(array('name' => $request->name));
        }
    }

    // CRUD operations for Classes
    public function getClass(Request $request)
    {
        $schoolId = $request->school;
        $classes = ClassTable::select(['class', 'id'])->where('schoolId', '=', $schoolId)->get();

        return response()->json($classes);
    }

    public function getSpecificClass(Request $request)
    {
        $classId = $request->classId;
        if ($classId != null) {
            $class = ClassTable::where('id', '=', $classId)->first();
            return response()->json($class);
        } else {
            return response()->json([
                "error" => 'school'
            ]);
        }
    }

    public function createClass(Request $request)
    {
        $classExists = ClassTable::select('id')->where(['class' => $request->className, 'schoolId' => $request->schoolId])->first();
        $hasTeacher = ClassTable::select('teacherId')->where('teacherId', '=', $request->teacherId)->first();
        if ($classExists == null && $hasTeacher == null) {
            ClassTable::create([
                'class' => $request->className,
                'schoolId' => $request->schoolId,
                'teacherId' => $request->teacherId,
            ]);
            return response()->json([
                'success' => 'true'
            ]);
        } else if ($classExists != null) {
            return response()->json([
                'error' => 'class'
            ]);
        } else if ($hasTeacher != null) {
            return response()->json([
                'error' => 'teacher'

            ]);
        } else {
            return response()->json([
                'error' => 'unknown'
            ]);
        }
    }

    public function deleteClass(Request $request)
    {
        ClassTable::where('id', '=', $request->classId)->delete();
        return response()->json([
            'success' => 'true'
        ]);
    }

    public function updateClass(Request $request)
    {
        $hasTeacher = ClassTable::select('teacherId')->where('teacherId', '=', 'teacherId');
        if ($hasTeacher != null) {
            DB::table('ClassTable')
                ->where('id', '=', $request->classId)
                ->update(array('teacherId' => $request->teacher));
            return response()->json([
                "success" => "true"
            ]);
        } else {
            return response()->json([
                "error" => "teacher"
            ]);
        }

    }
}
