<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionTable extends Model
{
    use HasFactory;
    protected $table = "SubmissionTable";
    protected $fillable = [
        'assignmentId',
        'studSubjectId',
        'handedInAt',
        'file',
    ];
}
