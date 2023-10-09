<?php

namespace App\Http\Controllers;

use App\Models\LoginToken;
use App\Models\UserLoginTable;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use PHLAK\StrGen;

class TokenController extends Controller
{
    public function generateToken($loginId): string
    {
        $tokenExists = LoginToken::select('tokenId')->where('loginId', '=', $loginId)->first();
        if ($tokenExists != null) {
            DB::table('LoginToken')->where('loginId', '=', $loginId)->delete();
        }

        $currentTime = time();
        $expiresAt = $currentTime + ( 60 * 5);

        $generator = new StrGen\Generator();

        $tokenId = $generator->length(16)->generate();
        $tokenKey = $generator->length(32)->generate();

        $payload = [
            'iss' => 'https://smv.usdd.company',
            'aud' => 'https://smv.usdd.company',
            'iat' => $currentTime,
            'exp' => $expiresAt,
            'loginId' => $loginId,
            'tokenId' => $tokenId,
        ];

        LoginToken::create([
            'tokenId' => $tokenId,
            'tokenKey' => $tokenKey,
            'loginId' => $loginId,
            'expiresAt' => $expiresAt,
        ]);

        return JWT::encode($payload, $tokenKey, 'HS256');
    }

    public function verifyToken($token): bool
    {
        $loginId = session('loginId');
        if ($token && $loginId) {

            $tokenKey = LoginToken::select('tokenKey')->where('loginId', '=', $loginId)->first();
            if ($tokenKey->tokenKey != null) {
                $decoded_token = JWT::decode($token, new Key($tokenKey->tokenKey, 'HS256'));
                $verify = UserLoginTable::select(['id'])->where('id', '=', $decoded_token->loginId)->first();

                if ($verify->id == $decoded_token->loginId) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
