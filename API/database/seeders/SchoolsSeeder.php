<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = new School();
        $school->naziv = "Srednja šola za kemijo, elektrotehniko in računalništvo Celje";
        $school->save();
    }
}
