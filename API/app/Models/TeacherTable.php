<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherTable extends Model
{
    use HasFactory;
    protected $table = "TeacherTable";
    protected $fillable = [
        'name',
        'surname',
        'email',
        'cabinet',
        'loginId'
    ];
}
