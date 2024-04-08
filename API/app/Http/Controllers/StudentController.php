<?php

namespace App\Http\Controllers;

use App\Models\ClassTable;
use App\Models\StudentSubjectTable;
use App\Models\StudentTable;
use App\Models\UserLoginTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function createStudent(Request $request)
    {
        $name = $request->name;
        $surname = $request->surname;
        $email = $request->email;
        $loginId = $request->loginId;
        $classId = $request->class;
        $subjects = json_decode($request->subjects);
        $username = $request->username;
        $password = $request->password;

        if ($loginId != null) {
            $checkEmail = StudentTable::select('id')->where('email', '=', $email)->first();

            if ($checkEmail != "") {
                return response()->json([
                    'error' => 'email-duplicate'
                ], 200);
            } else {
                StudentTable::create([
                    'name' => $name,
                    'surname' => $surname,
                    'email' => $email,
                    'loginId' => $loginId,
                    'classId' => $classId
                ]);
                return response()->json([
                    'success' => 'true'
                ], 200);
            }
        } else if ($loginId == null) {

            $checkUsername = UserLoginTable::select('id')->where('username', '=', $username)->first();

            if ($checkUsername == null ) {

                UserLoginTable::create([
                    'username' => $username,
                    'password' => Hash::make($password),
                    'userType' => 'student'
                ]);

                $loginId = UserLoginTable::select('id')->where('username', '=', $username)->first();

                StudentTable::create([
                    'name' => $name,
                    'surname' => $surname,
                    'email' => $email,
                    'classId' => $classId,
                    'loginId' => $loginId->id,
                ]);

                if ($subjects != null) {
                $student = DB::table('StudentTable')
                    ->where('loginId', '=', $loginId->id)
                    ->first();

                foreach ($subjects as $subject) {
                    StudentSubjectTable::create([
                        'subjectId' => strval($subject),
                        'studentId' => $student->id
                    ]);
                }
            }
                return response()->json([
                    'success' => 'true'
                ], 200);
        }

    } else {
            return response()->json([
                'error' => 'data'
            ], 200);
        }

        return response()->json([
            'error' => 'data'
        ], 200);
}

public
function getStudent(Request $request)
{
    $loginId = session('loginId');
    $student = DB::table('StudentTable')
        ->join('ClassTable', 'StudentTable.classId', '=', 'ClassTable.id')
        ->select('name', 'surname', 'email', 'class', 'StudentTable.id', 'ClassTable.id AS classId')
        ->where('loginId', '=', $loginId)
        ->first();
    session(['studentId' => $student->id]);
    return response()->json($student);
}

public
function AdminGetStudent(Request $request)
{
    $studentId = $request->studentId;
    $student = DB::table('StudentTable')
        ->join('ClassTable', 'StudentTable.classId', '=', 'ClassTable.id')
        ->where('StudentTable.id', '=', $studentId)
        ->first();
    $subjects = DB::table('StudentSubjectTable')
        ->select('SubjectTable.*')
        ->join('SubjectTable', 'StudentSubjectTable.subjectId', '=', 'SubjectTable.id')
        ->where('studentId', '=', $studentId)
        ->get();
    return response()->json([
        'student' => $student,
        'subjects' => $subjects
    ]);
}

public
function updateStudent(Request $request)
{
    $loginId = session('loginId');
    $name = $request->name;
    $surname = $request->surname;
    $email = $request->email;
    $studentId = $request->studentId;
    $subjects = json_decode($request->subjects);
    $class = $request->class;
    if ($loginId != null && $name != null && $surname != null && $email != null && $studentId == null) {
        StudentTable::where('loginId', '=', $loginId)->update(array(
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
        ));
        return response()->json([
            'success' => 'true'
        ]);
    } else if ($studentId != null && $subjects != null) {
        StudentTable::where('loginId', '=', $loginId)->update(array(
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'classId' => $class
        ));

        foreach ($subjects as $subject) {
            $st = DB::table('StudentSubjectTable')
                ->where('subjectId', '=', $subject)
                ->where('studentId', '=', $studentId)
                ->first();

            if ($st == null) {
                StudentSubjectTable::create([
                    'studentId' => $studentId,
                    'subjectId' => $subject,
                    'enrolledAt' => date('Y-m-d H:i:s', time())
                ]);
            }
        }
        $deleteSub = DB::table('SubmissionTable')
            ->select('studSubjectId', 'file')
            ->join('StudentSubjectTable', 'studSubjectId', '=', 'StudentSubjectTable.id')
            ->where('StudentSubjectTable.studentId', '=', $studentId)
            ->whereNotIn('subjectId', $subjects)
            ->get();

        foreach ($deleteSub as $ds) {
            if ($ds != null) {
                Storage::delete('/public/ass_sub/' . $ds->file);
                DB::table('SubmissionTable')
                    ->where('file', '=', $ds->file)
                    ->delete();
            }
        }
        DB::table('StudentSubjectTable')
            ->where('studentId', '=', $studentId)
            ->whereNotIn('subjectId', $subjects)
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

public
function deleteStudent(Request $request)
{
    $studentId = $request->studentId;
    if ($studentId != null) {
        $getStudent = DB::table('StudentTable')
            ->select('loginId')
            ->where('id', '=', $studentId)
            ->first();
        $sts = DB::table('StudentSubjectTable')
            ->select('id')
            ->where('studentId', '=', $studentId)
            ->get();

        foreach ($sts as $st) {
            $material = DB::table('SubmissionTable')
                ->select('file')
                ->where('studSubjectId', '=', $st->id)
                ->get();

            foreach ($material as $item) {
                Storage::delete('/public/ass_sub/' . $item->file);
            }
            DB::table('SubmissionTable')
                ->where('studSubjectId', '=', $st->id)
                ->delete();
        }
        DB::table('StudentSubjectTable')
            ->where('studentId', '=', $studentId)
            ->delete();

        StudentTable::where('loginId', '=', $getStudent->loginId)->delete();
        UserLoginTable::where('id', '=', $getStudent->loginId)->delete();

        return response()->json([
            'success' => 'true'
        ]);
    } else {
        return response()->json([
            'error' => 'studentId'
        ]);
    }
}

public
function resetPassword(Request $request)
{
    $loginId = session('loginId');
    if ($request->password != null && $loginId != null) {
        $password = Hash::make($request->password);
        DB::table('UserLoginTable')
            ->where('id', '=', $loginId)
            ->update(array('password' => $password));
        return response()->json([
            'success' => 'true'
        ]);
    } else {
        return response()->json([
            'error' => 'loginId'
        ]);
    }
}

public
function getAllStudents(Request $request)
{
    $search = $request->search;
    if ($search != null) {
        $students = DB::table('StudentTable')
            ->select('StudentTable.*', 'ClassTable.class')
            ->join('ClassTable', 'StudentTable.classId', '=', 'ClassTable.id')
            ->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('surname', 'LIKE', '%' . $search . '%')
            ->orWhere('ClassTable.class', 'LIKE', '%' . $search . '%')
            ->orderBy('StudentTable.name', 'asc')
            ->orderBy('StudentTable.surname', 'asc')
            ->get();
        return response()->json([
            'students' => $students
        ]);
    } else {
        $students = DB::table('StudentTable')
            ->select('StudentTable.*', 'ClassTable.class')
            ->join('ClassTable', 'StudentTable.classId', '=', 'ClassTable.id')
            ->orderBy('StudentTable.name', 'asc')
            ->orderBy('StudentTable.surname', 'asc')
            ->get();
        return response()->json([
            'students' => $students
        ]);
    }

}
}
