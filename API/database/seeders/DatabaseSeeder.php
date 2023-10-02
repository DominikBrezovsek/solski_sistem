<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ClassTable;
use App\Models\SchoolTable;
use App\Models\StudentTable;
use App\Models\TeacherTable;
use App\Models\UserLoginTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        UserLoginTable::create([
            'username' => 'DomBrez02',
            'password' => Hash::make('Dominik123'),
            'userType' => 'student'
        ]);

        $user_id = UserLoginTable::select('id')->where([
            'username' => 'DomBrez02',
            'userType' => 'student'
        ])->get();

        UserLoginTable::create([
            'username' => 'MejakN21',
            'password' => Hash::make('Nik123'),
            'userType' => 'teacher'
        ]);

        $teacher_login_id = UserLoginTable::select('id')->where([
            'username' => 'MejakN21',
            'userType' => 'teacher'
        ])->get();

        TeacherTable::create([
            'name' => 'Nik',
            'surname' => 'Mejak',
            'email' => 'mejaknik@gmail.com',
            'cabinet' => 'BE08',
            'loginId' => $teacher_login_id[0]->id
        ]);

        $teacher_id = TeacherTable::select('id')->where('email', '=', 'mejaknik@gmail.com')->first();

        SchoolTable::create([
            'name' => "Šolski center Celje, SŠ KER"
        ]);

        $school_id = SchoolTable::select('id')->where('name', '=', 'Šolski center Celje, SŠ KER')->get();

        ClassTable::create([
            'class' => 'R4B',
            'schoolId' => $school_id[0]->id,
            'teacherId' => $teacher_id-> id
        ]);

        $class_id = ClassTable::select('id')->where('class', '=', 'R4B')->first();







        StudentTable::create([
            'name' => "Dominik",
            'surname' => 'Brezovšek',
            'email' => 'dominikbe25@gmail.com',
            'loginId' => $user_id[0]->id,
            'classId' => $class_id->id,
        ]);
    }
}
