<?php

namespace App\Http\Controllers;

use App\Models\AdminTable;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getAdmin(Request $request)
    {
        $loginId = session('loginId');
        if ($loginId != null) {
            $admin = AdminTable::select('name', 'surname')->where('loginId', '=', $loginId)->first();
            return response()->json($admin);
        } else {
            return response()->json([
                'error' => 'session'
            ]);
        }
    }
}
