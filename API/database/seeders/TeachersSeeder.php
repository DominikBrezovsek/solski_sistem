<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeacherModel;

class TeachersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 100; $i++) {
            TeacherModel::create([
                'ime' => $faker->firstName,
                'priimek' => $faker->lastName,
                'kabinet' => $faker->numberBetween(1, 9),
                'email' => $faker->email,
            ]);
        }
    }
}
