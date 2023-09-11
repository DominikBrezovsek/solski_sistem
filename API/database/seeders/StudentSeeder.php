<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudentsModel;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 100; $i++) {
            StudentsModel::create([
                'ime' => $faker->firstName,
                'priimek' => $faker->lastName,
                'sola' => $faker->company,
                'razred' => $faker->numberBetween(1, 9),
                'email' => $faker->email,
            ]);
        }
    }
}
