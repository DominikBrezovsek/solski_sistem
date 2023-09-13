<?php

namespace Database\Seeders;

use App\Models\SubjectModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 10; $i++) {
            SubjectModel::create([
                'ime_predmeta' => $faker->word,
                'opis' => $faker->word,
                'kategorija' => $faker->word
            ]);
        }
    }
}

