<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\AuthToken;

class AuthController extends Controller
{
    public function CreateToken($user_id, $token_key, $token_id){
        date_default_timezone_set('Europe/Ljubljana');
        $iat = time();
        $exp = (time() + 30*60);
        $payload = [
            'token_id' => $token_id,
            'iss' => 'https://smv.usdd.company',
            'aud' => 'https://smv.usdd.company',
            'iat' => $iat,
            'exp' => $iat
        ];

        $save_token_data = new AuthToken();
        $save_token_data->token_id = $token_id;
        $save_token_data->token_key = $token_key;
        $save_token_data->user_id = $user_id;
        $save_token_data->issued_at = $iat;
        $save_token_data->expires_at = $exp;
        $save_token_data->save();

        $token = JWT::encode($payload, $token_key, 'HS256');
        return $token;
    }

    public function DecodeToken($token, $user_id){
        $token_data = AuthToken::where('user_id', '=', $user_id)->first();
        $decoded_id = "";
        $token_id = $token_data->token_id;
        if ($token_data != []){
            $token_key = $token_data->token_key;
            $decoded_token = JWT::decode($token, new Key($token_key, 'HS256'));
            $decoded_id = $decoded_token->token_id;
        }
        if ($token_id == $decoded_id) {
            return true;
        } else {
            return false;
        }
    }
}
