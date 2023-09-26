<?php

namespace App\Http\Controllers;

use App\Models\AdminModel;
use Illuminate\Http\Request;
class AdminController extends Controller
{
    public function addAdmin(Request $request)
    {
        $email_exists = AdminModel::where('email', '=', $request->email)->get();

        if ($email_exists != '[]') {
            return response()->json([
                "email_exists" => $email_exists,
                "message" => "Admin with this email already exists!",
                "error" => "Duplicate"
            ], '406');
        } else {

            $admin = new AdminModel();
            $admin->ime = $request->ime;
            $admin->priimek = $request->priimek;
            $admin->email = $request->email;
            $admin->save();
            return response()->json([
                "message" => "Admin added successfully"
            ], '201');
        }
    }

    public function removeAdmin(Request $request) {
        $admin = AdminModel::where('email', '=', $request->email )->delete();
        return response()->json([
            "message" => "Admin" . $request->email . "has been removed!"
        ], '201');
    }

    public function returnAdmin(Request $request) {
        $admin = AdminModel::where('id', '=', $request->id)->first();
        return response()->json($admin);
    }
}
