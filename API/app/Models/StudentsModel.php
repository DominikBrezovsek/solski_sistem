<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsModel extends Model
{
    use HasFactory;
    protected $table = 'Students';
    protected $fillable = [
        'ime',
        'priimek',
        'sola',
        'razred',
        'email',
    ];
}
