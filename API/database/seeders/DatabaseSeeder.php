<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Classes;
use App\Models\School;
use App\Models\StudentsModel;
use App\Models\SubjectModel;
use App\Models\TeacherModel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $school = new School();
        $school->naziv = "Srednja šola za kemijo, elektrotehniko in računalništvo Celje";
        $school->save();

        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 10; $i++) {
            SubjectModel::create([
                'ime_predmeta' => $faker->word,
                'opis' => $faker->word,
                'kategorija' => $faker->word
            ]);
        }

        $teacher = new TeacherModel();
        $teacher->ime = "Admin";
        $teacher->priimek = "KER";
        $teacher->kabinet = "BE08";
        $teacher->email = "admin@ker.sc-celje.si";
        $teacher->save();

        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 10; $i++) {
            TeacherModel::create([
                'ime' => $faker->firstName,
                'priimek' => $faker->lastName,
                'kabinet' => $faker->numberBetween(1, 9),
                'email' => $faker->email,
            ]);
        }
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

        StudentsModel::create([
            'ime' => "Domen",
            'priimek' => "Bezgovšek",
            'sola' => 'SŠ KER',
            'razred' => "R4B",
            'email' => "debil@debil.com"
        ]);
    }
}
