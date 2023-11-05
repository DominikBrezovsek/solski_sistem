<?php

namespace Database\Seeders;

use App\Models\TeacherTable;
use App\Models\UserLoginTable;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 500; $i < 530; $i++){
            $faker = Factory::create();
            $username = $faker->userName().$i;
            UserLoginTable::create([
                'username' => $username,
                'password' => Hash::make('teacher'),
                'userType' => 'teacher'
            ]);
            $userId = UserLoginTable::select('id')->where('username', '=', $username)->where('userType', '=', 'teacher')->first();
            TeacherTable::create([
                'name' => $faker->firstName(),
                'surname' => $faker->lastName(),
                'email' => $faker->email(),
                'cabinet' => $faker->randomDigitNotZero(),
                'loginId' =>$userId->id
            ]);
        }
    }
}
