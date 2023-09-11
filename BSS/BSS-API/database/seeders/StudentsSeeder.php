<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 100; $i++){
            Student::create([
                'ime' => $faker->firstName,
                'priimek' => $faker->lastName,
                'sola' => $faker->company,
                'oddelek' => $faker->jobTitle,
                'naslov' => $faker->streetAddress,
                'posta' => $faker->city,
                'postna_st' => $faker->postcode,
                'telefonska' => $faker->phoneNumber,
                'email' => $faker->email
            ]);
        }
    }
}
