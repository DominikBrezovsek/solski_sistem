<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\UserLoginTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\TokenController;
use function Laravel\Prompts\error;

class UserLoginController extends TokenController {
    public function checkLogin( Request $request ) {
        $username = $request->username;
        $password = $request->password;

        if ( $username != "" & $password != "" ) {
            $user = UserLoginTable::select( [ 'id', 'password', 'userType' ] )->where( [
                'username' => $username,
            ] )->first();

            if ( $user == null ) {
                return response()->json( [
                    'error' => 'credentials'
                ] );
            }
            if ( Hash::check( $password, $user->password ) ) {
                session( [ 'loginId' => $user->id ] );

                return response()->json( [
                    'jwt'     => $this->generateToken( $user->id ),
                    'success' => 'true',
                    'type'    => $user->userType
                ], 200 );
            } else {
                return response()->json( [
                    'error' => 'credentials'
                ] );
            }
        }

        return response()->json( [
            'error' => 'credentials'
        ] );
    }

    public function passwordReset( Request $request ) {
        $email    = $request->email;
        $password = $request->password;

        $student = DB::table( 'StudentTable' )
                     ->select( 'loginId' )
                     ->where( 'email', '=', $email )
                     ->first();

        $teacher = DB::table( 'TeacherTable' )
                     ->select( 'loginId' )
                     ->where( 'email', '=', $email )
                     ->first();
        $admin   = DB::table( 'AdminTable' )
                     ->select( 'loginId' )
                     ->where( 'email', '=', $email )
                     ->first();

        if ( $student != null ) {
            PasswordReset::create( [
                'email'       => $email,
                'loginId'     => $student->loginId,
                'newPassword' => Hash::make( $password )
            ] );
            $loginId = $student->loginId;
            $uuid    = uniqid();
            $link    = 'https://smv.usdd.company/API/public/api/confirmReset?user=' . $loginId . '&uuid=' . $uuid;
            mail( $email, 'Ponastavitev gesla', 'Pozdravljeni. Nekdo je zahteval ponastavitev gesla za vaš račun. Če želite geslo ponastaviti, kliknite ta link:' . $link );

            return response()->json( [
                'success' => 'true'
            ] );
        } else if ( $teacher != null ) {
            PasswordReset::create( [
                'email'       => $email,
                'loginId'     => $teacher->loginId,
                'newPassword' => Hash::make( $password )
            ] );
            $loginId = $teacher->loginId;
            $uuid    = uniqid();
            $link    = 'https://smv.usdd.company/API/public/api/confirmReset?user=' . $loginId . '&uuid=' . $uuid;
            mail( $email, 'Ponastavitev gesla', 'Pozdravljeni. Nekdo je zahteval ponastavitev gesla za vaš račun. Če želite geslo ponastaviti, kliknite ta link:' . $link );

            return response()->json( [
                'success' => 'true'
            ] );
        } else if ( $admin != null ) {
            PasswordReset::create( [
                'email'       => $email,
                'loginId'     => $admin->loginId,
                'newPassword' => Hash::make( $password )
            ] );
            $loginId = $admin->loginId;
            $uuid    = uniqid();
            $link    = 'https://smv.usdd.company/API/public/api/confirmReset?user=' . $loginId . '&uuid=' . $uuid;
            mail( $email, 'Ponastavitev gesla', 'Pozdravljeni. Nekdo je zahteval ponastavitev gesla za vaš račun. Če želite geslo ponastaviti, kliknite ta link:' . $link );

            return response()->json( [
                'success' => 'true'
            ] );
        }
        return response()->json( [
            'error' => 'ne vem'
        ] );
    }

    public function confirmReset(){
        $url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $parts = parse_url($url);
        parse_str($parts['query'], $query);

        $loginId = $query['user'];

        $password = DB::table('password_resets')
            ->select('newPassword')
            ->where('loginId', '=', $loginId)
            ->first();

        DB::table('UserLoginTable')
            ->where('loginId', '=', $loginId)
            ->update(array('password' => $password->newPassword));

        DB::table('password_resets')
          ->where('loginId', '=', $loginId)
          ->delete();

        return response()->json([
            'success' => 'true'
        ]);
    }

}
