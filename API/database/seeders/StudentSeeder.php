<?php

namespace Database\Seeders;

use App\Models\ClassTable;
use App\Models\SchoolTable;
use App\Models\StudentTable;
use App\Models\UserLoginTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i = 0; $i < 269; $i++){
            $faker = Factory::create();
            $username = $faker->userName().$i;
            UserLoginTable::create([
                'username' => $username,
                'password' => Hash::make('admin'),
                'userType' => 'student'
            ]);

            $userId = DB::table('UserLoginTable')
                ->select('id')
                ->where('username', '=', $username)
                ->where('userType', '=', 'student')
                ->first();
            $schoolId = SchoolTable::select('id')->first();
            $classId = ClassTable::select('id')->where('schoolId', '=', $schoolId->id)->get();
            $class = [];
            for ($j = 1; $j < count($classId); $j++){
                array_push($class, $classId[$j]->id);
            }
            $cId = array_rand($class);
            StudentTable::create([
                'name' => $faker->firstName(),
                'surname' => $faker->lastName(),
                'email' => $faker->email(),
                'loginId' => $userId->id,
                'classId' => $class[$cId]
            ]);
        }
    }
}
