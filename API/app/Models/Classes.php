<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $table = "Classes";
    protected $fillable = [
        'naziv',
        'razrednik',
        'naziv_sole',
    ];
}
