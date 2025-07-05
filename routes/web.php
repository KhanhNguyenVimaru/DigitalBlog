<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\checkValidToken;

// PAGE UI
Route::get('/', function () {return view('index');})->middleware('auth');
Route::get('/page_login', function () {return view('login');})->name('login');
Route::get('/page_signup', function () {return view('signup');})->name('signup');
Route::get('/page_account', function () {return view('account');})->name('account');


// LOGIN/OUT HANDLE
Route::post('/handle_login', [AuthController::class, 'login']);
Route::post('/handle_signup', [AuthController::class, 'signup']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout'])->name('logout');

