<?php

namespace App\Http\Controllers;

use App\Models\TeacherSubjectTable;
use App\Models\TeacherTable;
use App\Models\UserLoginTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function createTeacher(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $name = $request->name;
        $surname = $request->surname;
        $email = $request->email;
        $cabinet = $request->cabinet;
        $subjects = json_decode($request->subjects);

        $checkEmail = TeacherTable::select('id')->where('email', '=', $email)->first();
        $checkUsername = UserLoginTable::select('id')->where('username', '=', $username)->first();

        if ($checkEmail != null) {
            return response()->json([
                'error' => 'duplicate'
            ], 200);
        } else if ($checkUsername == null){
            UserLoginTable::create([
                'username' => $username,
                'password' => Hash::make($password),
                'userType' => 'teacher'
            ]);

            $loginId = UserLoginTable::select('id')->where('username', '=', $username)->first();

            TeacherTable::create([
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'cabinet' => $cabinet,
                'loginId' => $loginId->id,
            ]);
            $teacher = DB::table('TeacherTable')
                ->where('TeacherTable.loginId', '=', $loginId->id)
                ->first();
            if ($subjects != null) {
                foreach ($subjects as $subject){
                    TeacherSubjectTable::create([
                        'subjectId' => strval($subject),
                        'teacherId' => $teacher->id
                    ]);
                }
            }
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

    public function deleteTeacher(Request $request)
    {
        $teacherId = $request->teacherId;
        if ($teacherId != null) {
            $getTeacher = DB::table('TeacherTable')
                ->select('loginId')
                ->where('id', '=', $teacherId)
                ->first();
            $sts = DB::table('TeacherSubjectTable')
                ->select('id')
                ->where('teacherId', '=', $teacherId)
                ->get();

            foreach ($sts as $st){
                $material = DB::table('AssignmentMaterialTable')
                    ->select('AssignmentMaterialTable.material', 'id')
                    ->where('author', '=', $st->id)
                    ->get();

                foreach ($material as $item){
                    Storage::delete('/public/ass_mat/'.$item->material);
                }
                DB::table('SubjectAssignmentTable')
                    ->where('tsId', '=', $st->id)
                    ->delete();
            }


            TeacherTable::where('loginId', '=', $getTeacher->loginId)->delete();
            UserLoginTable::where('id', '=', $getTeacher->loginId)->delete();

            return response()->json([
                'success' => 'true'
            ]);
        } else {
            return response()->json([
                'error' => 'teacherId'
            ]);
        }
    }
}
