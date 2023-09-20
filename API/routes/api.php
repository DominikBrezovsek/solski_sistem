<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\LoginController;
use \App\Http\Controllers\SchoolsController;
use App\Http\Controllers\ClassesController;
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
Route::prefix('student')->group(function () {
    Route::post("/get", [StudentController::class, "returnStudent"]);
    Route::post("/create", [StudentController::class, "addStudent"]);
    Route::post("/update/class", [StudentController::class, "updateStudentClass"]);
});

Route::prefix('teacher')->group(function (){
    Route::post("/get", [TeacherController::class, "returnTeacher"]);
    Route::post("/create", [TeacherController::class, "addTeacher"]);
});

Route::prefix('admin')->group(function (){
    Route::post("/get", [AdminController::class, "returnAdmin"]);
    Route::post("/create", [AdminController::class, "addAdmin"]);
    Route::post("/delete", [AdminController::class, "removeAdmin"]);
});

Route::prefix('subject')->group(function (){
    Route::post('/get', [SubjectController::class, 'returnSubject']);
    Route::post('/create', [SubjectController::class, 'addSubject']);
    Route::post('/delete', [SubjectController::class, 'removeSubject']);
    Route::prefix('/update')->group(function (){
        Route::post('/name', [SubjectController::class, 'updateSubjectName']);
        Route::post('/desc', [SubjectController::class, 'updateSubjectDescription']);
        Route::post('/category', [SubjectController::class, 'updateSubjectCategory']);
    });
});

Route::prefix('materials')->group(function (){
    Route::post('/get', [MaterialController::class, 'returnMaterial']);
    Route::post('/create', [MaterialController::class, 'createMaterial']);
    Route::post('/delete', [MaterialController::class, 'removeMaterial']);
    Route::prefix('/update')->group(function (){
        Route::post('/name', [MaterialController::class, 'updateMaterialName']);
        Route::post('/document', [MaterialController::class, 'updateMaterialDocument']);
        Route::post('/category', [MaterialController::class, 'updateMaterialCategory']);
    });
});

Route::prefix('login/')->group(function (){
    Route::post('check', [LoginController::class, 'checkLogin']);
    Route::post('create', [LoginController::class, 'createLogin']);
});

Route::prefix('schools/')->group(function (){
    Route::get('get', [SchoolsController::class, 'getSchools']);
    Route::post('create', [SchoolsController::class, 'createSchool']);
});

Route::prefix('class/')->group(function (){
    Route::post('get', [ClassesController::class, 'getClasses']);
});






