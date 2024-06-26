<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginToken extends Model
{
    use HasFactory;
    protected $table = "LoginToken";
    protected $fillable = [
        'tokenId',
        'tokenKey',
        'loginId',
        'expiresAt'
    ];
}
