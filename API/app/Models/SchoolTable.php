<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolTable extends Model
{
    use HasFactory;
    protected $table = "SchoolTable";
    protected $fillable = [
        'name'
    ];
}
