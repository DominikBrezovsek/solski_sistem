<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post("/students/student", [StudentController::class, "returnStudent"]);
Route::post("/student/create", [StudentController::class, "addStudent"]);
Route::post("/student/update/class", [StudentController::class, "updateStudentClass"]);