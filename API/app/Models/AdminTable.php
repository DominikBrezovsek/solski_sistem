<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminTable extends Model
{
    use HasFactory;
    protected $table = "AdminTable";
    protected $fillable = [
        'name',
        'surname',
        'email',
        'loginId'
    ];
}
