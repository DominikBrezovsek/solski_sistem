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

        SubjectTable::create([
            'subject' => 'Slovenščina 4',
            'key' => 'SloR4B',
            'description' => 'Slovenščina za 4.letnik programa računalniški tehnik',
        ]);

        SubjectTable::create([
            'subject' => 'Matematika 4',
            'key' => 'MatR4B',
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

        AssignmentMaterialTable::create([
            'material' => 'Polinomi',
            'addedAt' => '2023-10-01 00:00:00'
        ]);

        AssignmentMaterialTable::create([
            'material' => 'Strahovi',
            'addedAt' => '2023-10-01 00:00:00'
        ]);

        $tsIdSlo = TeacherSubjectTable::select('id')->where('subjectId', '=', $subject_id_slo->id)->first();
        $tsIdMat = TeacherSubjectTable::select('id')->where('subjectId', '=', $subject_id_mat->id)->first();
        $amIdMat = AssignmentMaterialTable::select('id')->where('material', '=', 'Polinomi')->first();
        $amIdSlo = AssignmentMaterialTable::select('id')->where('material', '=', 'Strahovi')->first();

        SubjectAssignmentTable::create([
            'subjectId' => $subject_id_mat->id,
            'tsId' => $tsIdMat->id,
            'amId' => $amIdMat->id,
            'tittle' => 'Polinomi 1',
            'description' => 'Vaje iz polinomov',
            'givenAt' => '2023-10-01 00:00:00',
            'deadline' => '2023-10-15 23:59:59',
        ]);

        SubjectAssignmentTable::create([
            'subjectId' => $subject_id_slo->id,
            'tsId' => $tsIdSlo->id,
            'amId' => $amIdSlo->id,
            'tittle' => 'Ibsen, Strahovi - razlagalni spis',
            'description' => 'Razlagalni spis o Strahovih',
            'givenAt' => '2023-10-01 00:00:00',
            'deadline' => '2023-10-8 23:59:59',
        ]);

        UserLoginTable::create([
            'username' => 'daddySlemi2m',
            'password' => Hash::make('admin'),
            'userType' => 'admin'
        ]);
        $admin_login_id = UserLoginTable::where('username', '=', 'daddySlemi2m')->first();
        AdminTable::create([
            'name' => 'Daddy',
            'surname' => 'Slemi',
            'email' => 'uzelagamagla@magla.com',
            'loginId' => $admin_login_id->id,
        ]);


    }
}
