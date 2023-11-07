<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AdminTable;
use App\Models\AssignmentMaterialTable;
use App\Models\ClassTable;
use App\Models\SchoolTable;
use App\Models\StudentSubjectTable;
use App\Models\StudentTable;
use App\Models\SubjectAssignmentTable;
use App\Models\SubjectTable;
use App\Models\TeacherSubjectTable;
use App\Models\TeacherTable;
use App\Models\UserLoginTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
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

        $classes = ['R1A', 'R1B', 'R2A', 'R2B', 'R3A', 'R3B','R4A', 'R4B'];
        foreach ($classes as $class) {
            ClassTable::create([
                'class' => $class,
                'schoolId' => $school_id[0]->id,
            ]);
        }


        $class_id = ClassTable::select('id')->where('class', '=', 'R4B')->first();

        StudentTable::create([
            'name' => "Dominik",
            'surname' => 'Brezovšek',
            'email' => 'dominikbe25@gmail.com',
            'loginId' => $user_id[0]->id,
            'classId' => $class_id->id,
        ]);

        SubjectTable::create([
            'subject' => 'Slovenščina 4',
            'description' => 'Slovenščina za 4.letnik programa računalniški tehnik',
        ]);

        SubjectTable::create([
            'subject' => 'Matematika 4',
            'description' => 'Matematika za 4.letnik programa računalniški tehnik',
        ]);

        $subject_id_slo = SubjectTable::select('id')->where('subject', '=', 'Slovenščina 4')->first();

        StudentSubjectTable::create([
            'studentId' => $user_id[0]->id,
            'subjectId' => $subject_id_slo->id,
            'enrolledAt' => '2023-09-01 08:00:00'
        ]);

        $subject_id_mat = SubjectTable::select('id')->where('subject', '=', 'Matematika 4')->first();

        StudentSubjectTable::create([
            'studentId' => $user_id[0]->id,
            'subjectId' => $subject_id_mat->id,
            'enrolledAt' => '2023-09-01 08:00:00'
        ]);

        TeacherSubjectTable::create([
            'subjectId' => $subject_id_slo->id,
            'teacherId' => $teacher_id->id,
        ]);

        TeacherSubjectTable::create([
            'subjectId' => $subject_id_mat->id,
            'teacherId' => $teacher_id->id,
        ]);

        UserLoginTable::create([
            'username' => 'daddyLubej4pet',
            'password' => Hash::make('admin'),
            'userType' => 'admin'
        ]);
        $admin_login_id = UserLoginTable::where('username', '=', 'daddyLubej4pet')->first();
        AdminTable::create([
            'name' => 'Boštjan',
            'surname' => 'Lubej',
            'email' => 'uzelagamagla@magla.com',
            'loginId' => $admin_login_id->id,
        ]);


    }
}
