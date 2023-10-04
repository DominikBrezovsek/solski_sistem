<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectAssignmentTable extends Model
{
    use HasFactory;
    protected $table = "SubjectAssignmentTable";
    protected $fillable = [
        'subjectId',
        'tsId',
        'amId',
        'description',
        'givenAt',
        'deadline'
    ];
}
