<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\checkValidToken;
use App\Http\Controllers\UserController;

// PAGE UI
Route::get('/', function () {return view('index');})->middleware('auth');
Route::get('/page_login', function () {return view('login');})->name('login');
Route::get('/page_signup', function () {return view('signup');})->name('signup');
Route::get('/page_account', function () {return view('account');})->name('account');
Route::get('/signup-success', function () { return view('signup_success'); });


// LOGIN/OUT HANDLE
Route::post('/handle_login', [AuthController::class, 'login']);
Route::post('/handle_signup', [AuthController::class, 'signup'])->name('register');
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout'])->name('logout');

// API ACCOUNT
Route::middleware('auth:api')->group(function () {
    Route::patch('/update_user/{id}', [UserController::class, 'updateUserData'])->name('updateUserData');
    Route::delete('/delete_account', [UserController::class, 'deleteUserAccount'])->name('deleteUserAccount');
});

// URL verify register
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verification.verify');