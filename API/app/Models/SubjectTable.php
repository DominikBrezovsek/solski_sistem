<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTable extends Model
{
    use HasFactory;
    protected $table = "SubjectTable";
    protected $fillable = [
        'subject',
        'key',
        'description'
    ];
}
