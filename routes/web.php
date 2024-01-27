<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PaymentController;
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
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::resource('/expenses', ExpenseController::class)->only('index', 'store');
    Route::get('/expenses/delete/{id}', [ExpenseController::class, 'destroy']);
    Route::view('/settings', 'settings');
    Route::put('/settings', [UserController::class, 'adminUpdate']);
    Route::get('/report/expenses', [ExpenseController::class, 'report']);
    Route::post('/report/payments', [PaymentController::class, 'report']);
});

Route::group(["prefix" => "employee", "middleware" => ["auth", "employeeCheck"], "as" => "employee."], function () {
    Route::view('/', 'employee.dashboard');
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::view('/settings', 'settings');
    Route::put('/settings', [UserController::class, 'update']);
});
