<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSubjectTable extends Model
{
    use HasFactory;
    protected $table = "TeacherSubjectTable";
    protected $fillable = [
        'subjectId',
        'teacherId'
    ];
}
