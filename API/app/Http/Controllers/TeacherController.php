<?php

namespace App\Http\Controllers;

use App\Models\TeacherTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function createTeacher(Request $request)
    {
        $name = $request->name;
        $surname = $request->surname;
        $email = $request->email;
        $loginId = $request->loginId;
        $cabinet = $request->cabinet;

        $checkEmail = TeacherTable::select('id')->where('email', '=', $email)->first();

        if ($checkEmail != "") {
            return response()->json([
                'error' => 'email-duplicate'
            ], 200);
        } else {
            TeacherTable::create([
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'cabinet' => $cabinet,
                'loginId' => $loginId,
            ]);

            return response()->json([
                'success' => 'true'
            ], 200);
        }
    }

    public function getTeacher(Request $request){
        $loginId = session('loginId');
        $teacher = TeacherTable::select(['name', 'surname', 'email', 'id'])->where('loginId', '=', $loginId)->first();
        session(['teacherId' => $teacher->id]);
        return response()->json($teacher);
    }

    public function getAllTeachers(Request $request){
        $search = $request->search;
        if ($search != null){
            $teachers = DB::table('TeacherTable')
                ->select('TeacherTable.*')
                ->where('name', 'LIKE', '%'.$search.'%')
                ->orWhere('surname', 'LIKE','%'.$search.'%')
                ->orderBy('TeacherTable.name', 'asc')
                ->orderBy('TeacherTable.surname', 'asc')
                ->get();
            return response()->json([
                'teachers' => $teachers
            ]);
        } else{
            $teachers = DB::table('TeacherTable')
                ->select('TeacherTable.*', )
                ->orderBy('TeacherTable.name', 'asc')
                ->orderBy('TeacherTable.surname', 'asc')
                ->get();
            return response()->json([
                'teachers' => $teachers
            ]);
        }
    }

    public function updateTeacher(Request $request)
    {
        $loginId = session('loginId');
        $name = $request->name;
        $surname = $request->surname;
        $email = $request->email;
        if ($loginId != null && $name != null && $surname != null && $email != null) {
            TeacherTable::where('loginId', '=', $loginId)->update(array(
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
            ));
            return response()->json([
                'success' => 'true'
            ]);
        } else {
            return response()->json([
                'error' => 'data'
            ]);
        }
    }
}
