<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    use HasFactory;
    protected $table = "AuthToken";
    protected $fillable = [
        'token_id',
        'token_key',
        'user_id',
        'issued_at',
        'expires_at'
    ];
}
