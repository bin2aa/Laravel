<?php

use Illuminate\Support\Facades\Route;

use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/send-mail', function () {
    Mail::to('NguyenThanhThinhTestEmail@example.com')->send(new TestMail());
    return 'Email đã được Nguyễn Thanh Thịnh';
});

Route::get('/dashboard', function() {
    return view('dashboard');
});

Route::get('/login', function() {
    return view('account.login');
})->name('login');

Route::get('/register', function() {
    return view('account.register');
})->name('register');

// đăng ký tài khoản 
Route::post('/register', [RegisterController::class, 'register'])->name('register'); 

// đăng nhập
Route::post('/login', [LoginController::class, 'login'])->name('login');

// đăng xuất
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// hiển thị danh sách người dùng
Route::get('/users', [UserController::class, 'showUsers'])->name('users');
