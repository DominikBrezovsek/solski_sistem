<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentMaterialTable extends Model
{
    use HasFactory;
    protected $table = "AssignmentMaterialTable";
    protected $fillable = [
        'material',
        'addedAt',
        'author'
    ];
}
