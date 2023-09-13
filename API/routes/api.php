<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

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
Route::post("/students/student", [StudentController::class, "returnStudent"]);
Route::post("/student/create", [StudentController::class, "addStudent"]);
Route::post("/student/update/class", [StudentController::class, "updateStudentClass"]);

Route::post("/teacher/teacher", [TeacherController::class, "returnTeacher"]);
Route::post("/teacher/create", [TeacherController::class, "addTeacher"]);

