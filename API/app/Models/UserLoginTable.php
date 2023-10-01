<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginTable extends Model
{
    use HasFactory;
    protected $table = "UserLoginTable";
    protected $fillable = [
        'username',
        'password',
        'userType'
    ];
}
