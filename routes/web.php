<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'auth.login')->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::group(["prefix" => "admin", "middleware" => ["auth", "adminCheck"], "as" => "admin."], function () {
    Route::get('/', [AttendanceController::class, 'index']);
    Route::resource('/employees', UserController::class)->only('index', 'store', 'update');
    Route::put('/employees', [UserController::class, 'update']);
    Route::get('/employees/delete/{id}', [UserController::class, 'destroy']);
    Route::view('/payments', 'admin.payments');
    Route::view('/settings', 'settings');
    Route::put('/settings', [UserController::class, 'adminUpdate']);
});

Route::group(["prefix" => "employee", "middleware" => ["auth", "employeeCheck"], "as" => "employee."], function () {
    Route::view('/', 'employee.dashboard');
    Route::view('/payments', 'employee.payments');
    Route::view('/attendance', 'employee.attendance');
    Route::view('/settings', 'settings');
    Route::put('/settings', [UserController::class, 'update']);
});
