<?php

namespace App\Http\Controllers;

use App\Models\LoginToken;
use App\Models\UserLoginTable;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use PHLAK\StrGen;

class TokenController extends Controller
{
    public function generateToken($loginId): string
    {
        $currentTime = time();
        $expiresAt = $currentTime * 60 * 5;

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
        ]);

        return JWT::encode($payload, $tokenKey, 'HS256');
    }

    public function verifyToken($token): bool
    {
        $tokenKey = LoginToken::select('tokenKey')->where('loginId', '=', session('loginId'));
        if ($tokenKey) {
            $decoded_token = JWT::decode($token, new Key($tokenKey, 'HS256'));
            $verify = UserLoginTable::select(['loginId'])->where('tokenId', '=', $decoded_token->tokenId)->first();

            if ($verify->loginId == $decoded_token->loginId) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
