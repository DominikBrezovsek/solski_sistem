<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\StudentSubjectController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\TeacherSubjectController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::prefix('login/')->group(function (){
    Route::post('check', [UserLoginController::class, 'checkLogin']);
});
Route::prefix('school/')->group(function (){
    Route::get('getAll', [SchoolController::class, 'getSchool']);
    Route::post('classes', [SchoolController::class, 'getClass']);

});
Route::post('student/create', [StudentController::class, 'createStudent']);

Route::post('register', [RegisterController::class, 'registerUser']);

Route::middleware(['tokenVerify'])->group(function (){
    Route::prefix('student/')->group(function (){
        Route::post('get', [StudentController::class, 'getStudent']);
        Route::post('admin-get', [StudentController::class, 'AdminGetStudent']);
        Route::post('update', [StudentController::class, 'updateStudent']);
        Route::post('delete', [StudentController::class, 'deleteStudent']);
        Route::post('password', [StudentController::class, 'resetPassword']);
    });

    Route::prefix('school/manage')->group(function (){
        Route::post('/create', [SchoolController::class, 'createSchool']);
        Route::post('/delete', [SchoolController::class, 'deleteSchool']);
        Route::post('/update', [SchoolController::class, 'updateSchool']);
    });
    Route::prefix('class/manage')->group(function () {
        Route::post('/get', [SchoolController::class, 'getSpecificClass']);
        Route::post('/create', [SchoolController::class, 'createClass']);
        Route::post('/delete', [SchoolController::class, 'deleteClass']);
        Route::post('/update', [SchoolController::class, 'updateClass']);
    });
    Route::prefix('teacher/')->group(function (){
        Route::post('get', [TeacherController::class, 'getTeacher']);
        Route::post('create', [TeacherController::class, 'createTeacher']);
        Route::post('delete', [TeacherController::class, 'getTeacher']);
        Route::post('update', [TeacherController::class, 'getTeacher']);
    });

    Route::prefix('admin/')->group(function () {
        Route::post('get', [AdminController::class, 'getAdmin']);
    });

    Route::prefix('user/')->group(function (){
        Route::post('create', [RegisterController::class, 'registerUser']);
        Route::post('get', [RegisterController::class, 'deleteUser']);
        Route::post('update', [RegisterController::class, 'updateUser']);
    });

    Route::prefix('dashboard/')->group(function (){
        Route::post('subjects', [DashboardController::class, 'userSubjects']);
        Route::post('assignments', [DashboardController::class, 'userAssignments']);
    });

    Route::prefix('subjects/')->group(function (){
        Route::post('get-all', [SubjectController::class, 'getSubjects']);
        Route::post('get', [SubjectController::class, 'getSubject']);
        Route::post('create', [SubjectController::class, 'createSubject']);
        Route::post('delete', [SubjectController::class, 'deleteSubject']);
    });

    Route::prefix('sts/')->group(function (){
        Route::post('add', [StudentSubjectController::class, 'enrollStudent']);
        Route::post('get', [StudentSubjectController::class, 'getSubjects']);
    });

    Route::prefix('ts/')->group(function (){
        Route::post('get', [TeacherSubjectController::class, 'getSubjects']);
    });

    Route::prefix('assignment/')->group(function (){
        Route::post('get', [AssignmentController::class, 'getAssignment']);
        Route::post('get-all', [AssignmentController::class, 'getAssignments']);
        Route::post('submit', [AssignmentController::class, 'submitAssignment']);
        Route::post('submitted', [AssignmentController::class, 'getSubmission']);
        Route::post('deleteSubmission', [AssignmentController::class, 'deleteSubmission']);
        Route::post('create', [AssignmentController::class, 'addAssignment']);
        Route::post('delete', [AssignmentController::class, 'deleteAssignment']);
        Route::post('update', [AssignmentController::class, 'updateAssignment']);
        Route::post('file', [AssignmentController::class, 'returnFile']);
        Route::post('subFile', [AssignmentController::class, 'returnSubFile']);
        Route::post('submissions', [AssignmentController::class, 'getSubmissions']);
        Route::post('allSubs', [AssignmentController::class, 'getAllSubmissions']);

    });

});

