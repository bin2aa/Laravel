<?php

use Illuminate\Support\Facades\Route;

use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/send-mail', function () {
    Mail::to('NguyenThanhThinhTestEmail@example.com')->send(new TestMail());
    return 'Email đã được Nguyễn Thanh Thịnh';
});


Route::get('/login', function () {
    return view('account.login');
})->name('login');

Route::get('/register', function () {
    return view('account.register');
})->name('register');

// đăng ký tài khoản 
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// đăng nhập
Route::post('/login', [LoginController::class, 'login'])->name('login');

// đăng xuất
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// quên mật khẩu
Route::get('/forgot-password', function () {
    return view('account.forgot-password');
})->name('forgot-password');

// Quên mật khẩu
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('forgot-password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Đặt lại mật khẩu
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');



Route::middleware(['auth', 'check.account.status'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboardssss');

    // hiển thị danh sách người dùng
    Route::get('/users', [UserController::class, 'showUsers'])->name('users');

    // cập nhật trạng thái người dùng
    Route::put('/users/{id}/status', [UserController::class, 'updateStatus'])->name('updateUserStatus');

    //Cập nhật người dùng
    Route::put('/users/{id}', [UserController::class, 'updateUser'])->name('updateUser');

    //xem thông tin người dùng
    // Route::get('/profile', function () {
    //     return view('profile.userDetails');
    // })->name('profile');
    // Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');

    Route::prefix('profile')->group(function () {
        Route::get('/', [UserController::class, 'showProfile'])->name('profile');
        Route::put('/{id}', [UserController::class, 'updateProfile'])->name('profile.updateProfile');
    });


    // tạo bài viết
    Route::prefix('posts')->group(function () {

        Route::get('/', [PostController::class, 'listPost'])->name('listPosts');
        Route::get('/create', [PostController::class, 'showCreatePostForm'])->name('posts.showCreatePostForm');
        Route::post('/posts/create', [PostController::class, 'createPost'])->name('posts.createPost');
        Route::get('/published', [PostController::class, 'listPublishedPosts'])->name('listPublishedPosts');
        Route::get('/draft', [PostController::class, 'listDraftPosts'])->name('listDraftPosts');
    });
});
