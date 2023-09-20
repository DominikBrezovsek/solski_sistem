<?php

namespace Database\Seeders;

use App\Models\Classes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $class_names = [
            'R1A',
            'R1B',
            'R2A',
            'R2B',
            'R3A',
            'R3B',
            'R4A',
            'R4B',
        ];
        foreach ($class_names as $class){
            $new_class = new Classes();
            $new_class->naziv = $class;
            $new_class->razrednik = "admin@ker.sc-celje.si";
            $new_class->naziv_sole = "Srednja šola za kemijo, elektrotehniko in računalništvo Celje";
            $new_class->save();
        }
    }
}
