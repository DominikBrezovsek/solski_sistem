<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTable extends Model
{
    use HasFactory;
    protected $table = "StudentTable";
    protected $fillable = [
        'name',
        'surname',
        'email',
        'loginId',
        'classId'
    ];
}
