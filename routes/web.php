<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\checkValidToken;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\accessUserProfile;
use App\Http\Controllers\FollowUserController;
use Illuminate\Http\Request;

// PAGE UI
Route::get('/', function () {return view('index');})->middleware('auth');
Route::get('/page_login', function () {return view('login');})->name('login');
Route::get('/page_signup', function () {return view('signup');})->name('signup');
Route::get('/page_account', function () {return view('account');})->name('account')->middleware('auth');
Route::get('/signup-success', function () { return view('signup_success'); });
Route::get('/my-profile', function () { return view('myProfile');})->name('myProfile')->middleware('auth');
Route::get('/writing', function () { return view('writing');})->name('writing')->middleware('auth');
Route::get('/post-content-viewer/{id}', [App\Http\Controllers\PostController::class, 'viewContentJson'])->name('post.content.viewer');
Route::get('/user-profile/{id}', [UserController::class, 'userProfile'])->name('userProfile')->middleware(accessUserProfile::class);// User profile
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
Route::get('/content-of-users', [PostController::class, 'contentOfUsers'])->name('contentOfUsers')->middleware('auth'); //  content of users ám chỉ bài viết của người dùng đăng nhập
Route::delete('/delete-post/{id}', [PostController::class, 'deletePost'])->name('deletePost')->middleware('auth');
Route::patch('/update-status/{id}', [PostController::class, 'updateStatus'])->name('updateStatus')->middleware('auth');
Route::get('/categories', [CategoryController::class, 'getAllCategories'])->name('getAllCategories')->middleware('auth'); // API CATEGORY
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verification.verify');// URL verify register
Route::get('/search-suggest', [UserController::class, 'searchSuggest'])->name('search.suggest');// suggest search
Route::get('/content-of-author/{id}', [PostController::class, 'contentOfAuthor'])->name('contentOfAuthor'); //  content of author là bài viết của người dùng khác
// FOLLOW/UNFOLLOW USER
Route::get('/follow_user/{id}',[FollowUserController::class, 'followUser'])->name('followUser')->middleware('auth');
Route::delete('/delete_follow/{id}', [FollowUserController::class, 'deleteFollow'])->name('deleteFollow')->middleware('auth');
Route::delete('/revoke_follow_request/{id}', [App\Http\Controllers\FollowUserController::class, 'revokeFollowRequest'])->name('revokeFollowRequest');

Route::match(['get', 'post'], '/fetchUrl', function(Request $request) {
    $url = $request->input('url') ?? $request->query('url');
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return response()->json(['success' => 0, 'message' => 'Invalid URL']);
    }
    try {
        $html = @file_get_contents($url);
        if (!$html) {
            return response()->json(['success' => 0, 'message' => 'Cannot fetch URL']);
        }
        preg_match('/<title>(.*?)<\\/title>/si', $html, $title);
        preg_match('/<meta name="description" content="(.*?)"/si', $html, $desc);
        preg_match('/<meta property="og:image" content="(.*?)"/si', $html, $img);
        return response()->json([
            'success' => 1,
            'meta' => [
                'title' => $title[1] ?? $url,
                'description' => $desc[1] ?? '',
                'image' => $img[1] ?? '',
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => 0, 'message' => 'Error: ' . $e->getMessage()]);
    }
});