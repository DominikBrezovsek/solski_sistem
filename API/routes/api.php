<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SchoolControler;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DashboardController;

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

Route::prefix('login/')->group(function (){
    Route::post('check', [UserLoginController::class, 'checkLogin']);
});
Route::prefix('school/')->group(function (){
    Route::get('getAll', [SchoolControler::class, 'getSchool']);
    Route::post('classes', [SchoolControler::class, 'getClass']);
});
Route::post('register', [RegisterController::class, 'registerUser']);

Route::middleware(['tokenVerify'])->group(function (){
    Route::prefix('student/')->group(function (){
        Route::post('create', [StudentController::class, 'createStudent']);
        Route::post('get', [StudentController::class, 'getStudent']);
    });

    Route::prefix('teacher/')->group(function (){
        Route::post('get', [TeacherController::class, 'getTeacher']);
    });

    Route::prefix('dashboard/')->group(function (){
        Route::post('subjects', [DashboardController::class, 'userSubjects']);
        Route::post('assignments', [DashboardController::class, 'userAssignments']);
    });

});

