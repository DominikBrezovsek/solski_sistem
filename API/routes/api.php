<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubjectController;

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




