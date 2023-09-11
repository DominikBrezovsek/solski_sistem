<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $table = "Students";
    protected $primaryKey = "email";
    protected $fillable = [
        'ime', 
        'priimek', 
        'sola',
        'oddelek', 
        'naslov', 
        'posta', 
        'postna_st', 
        'telefonska', 
        'email',
    ];
}
