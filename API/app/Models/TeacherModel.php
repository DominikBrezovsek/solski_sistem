<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherModel extends Model
{
    use HasFactory;
    protected $table = 'Teachers';
    protected $fillable = [
        'ime',
        'priimek',
        'kabinet',
        'email',
    ];
}
