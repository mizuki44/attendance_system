<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RestController;
use App\Http\Controllers\LoginController;



Route::get('/', [RestController::class, 'index'])->name('rest.index')->middleware('auth');
Route::get('/', [AttendanceController::class, 'index'])->middleware('verified');

Route::get('/login', [LoginController::class, 'getIndex'])->name('login');
Route::post('/login', [LoginController::class, 'postIndex'])->name('login');

Route::post('/workStart', [AttendanceController::class, 'workStart']);
Route::post('/workEnd', [AttendanceController::class, 'workEnd']);
Route::post('/restStart', [AttendanceController::class, 'restStart']);
Route::post('/restEnd', [AttendanceController::class, 'restEnd']);

Route::get('/attendance_list', [AttendanceController::class, 'getAttendances']);
Route::get('/attendance_list/{num}', [AttendanceController::class, 'getAttendances']);

Route::get('/user_list', [AttendanceController::class, 'listbyUser']);
Route::get('/user_page', [AttendanceController::class, 'getUserList']);
Route::get('/user_list?name={$username}', [AttendanceController::class, 'listbyUser']);

