<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'auth.login')->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::group(["prefix" => "admin", "middleware" => ["auth", "adminCheck"], "as" => "admin."], function () {
    Route::view('/', 'layouts.layout');
});

Route::group(["prefix" => "employee", "middleware" => ["auth", "employeeCheck"], "as" => "employee."], function () {
    Route::view('/', 'layouts.layout');
});
