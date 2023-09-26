<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atendees extends Model
{
    use HasFactory;
    protected $table = "Atendees";
    protected $fillable = [
        'predmet',
        'dijak',
        'enrolled_at',
    ];
}
