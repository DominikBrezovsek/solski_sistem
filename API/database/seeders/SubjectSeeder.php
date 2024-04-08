<?php

namespace Database\Seeders;

use App\Models\SubjectTable;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            'Slovenščina 1',
            'Slovenščina 2',
            'Slovenščina 3',
            'Matematika 1',
            'Matematika 2',
            'Matematika 3',
            'Angleščina 1',
            'Angleščina 2',
            'Angleščina 3',
            'Angleščina 4',
            'Programiranje 1',
            'Programiranje 2',
            'Programiranje 3',
            'Programiranje 4',
            'Podatkovne baze 1',
            'Podatkovne baze 2',
            'Podatkovne baze 3',
            'Podatkovne baze 4',
        ];

        foreach ($subjects as $subject){
            $faker = Factory::create();
            SubjectTable::create([
                'subject' => $subject,
                'description' => $faker->realText(40)
            ]);
        }
    }
}
