<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\checkValidToken;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;


// PAGE UI
Route::get('/', function () {return view('index');})->middleware('auth');
Route::get('/page_login', function () {return view('login');})->name('login');
Route::get('/page_signup', function () {return view('signup');})->name('signup');
Route::get('/page_account', function () {return view('account');})->name('account')->middleware('auth');
Route::get('/signup-success', function () { return view('signup_success'); });
Route::get('/my-profile', function () { return view('myProfile');})->name('myProfile')->middleware('auth');
Route::get('/writing', function () { return view('writing');})->name('writing')->middleware('auth');
// LOGIN/OUT HANDLE
Route::post('/handle_login', [AuthController::class, 'login']);
Route::post('/handle_signup', [AuthController::class, 'signup'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
// API ACCOUNT
Route::patch('/update_user/{id}', [UserController::class, 'updateUserData'])->name('updateUserData')->middleware('auth');
Route::delete('/delete_account', [UserController::class, 'deleteUserAccount'])->name('deleteUserAccount')->middleware('auth');
Route::middleware('auth:api')->post('/change_password', [UserController::class, 'changePassword'])->name('changePassword');
Route::post('/update-avatar', [UserController::class, 'updateAvatar'])->middleware('auth');
// API POST
Route::post('/insert-post', [PostController::class, 'storeContent'])->name('insertPost')->middleware('auth'); 
Route::post('/uploadFile', [PostController::class, 'uploadFile'])->name('uploadFile')->middleware('auth');
Route::get('/content-of-users', [PostController::class, 'contentOfUsers'])->name('contentOfUsers')->middleware('auth');
Route::delete('/delete-post/{id}', [PostController::class, 'deletePost'])->name('deletePost')->middleware('auth');
Route::patch('/update-status/{id}', [PostController::class, 'updateStatus'])->name('updateStatus')->middleware('auth');
Route::get('/post-content-viewer/{id}', [App\Http\Controllers\PostController::class, 'viewContentJson'])->name('post.content.viewer');
// API CATEGORY
Route::get('/categories', [CategoryController::class, 'getAllCategories'])->name('getAllCategories')->middleware('auth');
// URL verify register
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
