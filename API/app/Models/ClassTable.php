<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTable extends Model
{
    use HasFactory;
    protected $table = "ClassTable";
    protected $fillable = [
        'class',
        'teacherId',
        'schoolId'
    ];
}
