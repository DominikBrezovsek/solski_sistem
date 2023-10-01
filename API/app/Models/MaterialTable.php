<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialTable extends Model
{
    use HasFactory;
    protected $table = "MaterialTable";
    protected $fillable = [
        'material',
        'addedAt'
    ];
}
