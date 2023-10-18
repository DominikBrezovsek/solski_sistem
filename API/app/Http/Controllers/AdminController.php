<?php

namespace App\Http\Controllers;

use App\Models\AdminTable;
use App\Models\TeacherTable;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getAdmin(Request $request)
    {
        $loginId = session('loginId');
        if ($loginId != null) {
            $admin = AdminTable::select('name', 'surname', 'email')->where('loginId', '=', $loginId)->first();
            return response()->json($admin);
        } else {
            return response()->json([
                'error' => 'session'
            ]);
        }
    }
    public function updateAdmin(Request $request)
    {
        $loginId = session('loginId');
        $name = $request->name;
        $surname = $request->surname;
        $email = $request->email;
        if ($loginId != null && $name != null && $surname != null && $email != null) {
            AdminTable::where('loginId', '=', $loginId)->update(array(
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
