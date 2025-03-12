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
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/send-mail', function () {
    Mail::to('NguyenThanhThinhTestEmail@example.com')->send(new TestMail());
    return 'Email đã được Nguyễn Thanh Thịnh';
});


Route::get('/login', function () {
    return view('account.login');
})->middleware('loggedin')->name('login');

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


//xem thông tin người dùng
Route::prefix('profile')->group(function () {
    Route::get('/', [UserController::class, 'showProfile'])->name('profile');
    Route::put('/{id}', [UserController::class, 'updateProfile'])->name('profile.updateProfile');
});



Route::middleware(['auth', 'check.account.status', 'admin'])->group(function () {

    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboardssss');

    // hiển thị danh sách người dùng
    Route::get('/users', [UserController::class, 'showUsers'])->name('users');

    // cập nhật trạng thái người dùng
    Route::put('/users/{id}/status', [UserController::class, 'updateStatus'])->name('updateUserStatus');

    //Cập nhật người dùng
    Route::put('/users/{id}', [UserController::class, 'updateUser'])->name('updateUser');

    Route::get('/dashboard', [PostController::class, 'listAllPosts'])->name('dashboardssss');




    // tạo bài viết
    Route::prefix('posts')->group(function () {

        Route::get('/', [PostController::class, 'listPost'])->name('listPosts');
        Route::get('/create', [PostController::class, 'showCreatePostForm'])->name('posts.showCreatePostForm');
        Route::post('/create', [PostController::class, 'createPost'])->name('posts.createPost');
        Route::get('/published', [PostController::class, 'listPublishedPosts'])->name('listPublishedPosts');
        Route::get('/draft', [PostController::class, 'listDraftPosts'])->name('listDraftPosts');

        Route::put('/{post}', [PostController::class, 'updatePost'])->name('posts.updatePost');
        Route::delete('/{post}', [PostController::class, 'deletePost'])->name('posts.deletePost');
        Route::patch('/approve/{post}', [PostController::class, 'approvePost'])->name('posts.approvePost');
        Route::patch('/unapprove/{post}', [PostController::class, 'unapprovePost'])->name('posts.unapprovePost');
    });


    // xem bài viết của người dùng
    Route::get('/users/{id}/posts', [UserController::class, 'showUserPosts'])->name('users.posts');
});


// Route::get('/client', function () {
//     return view('client.index');
// });
// Route::get('/client', [PostController::class, 'listApprovedPosts']);

Route::prefix('client')->group(function () {

    //Route không cần đăng nhập
    Route::get('/news', action: [PostController::class, 'index'])->name('client.index');
    Route::get('/news/{slug}/{id}', [PostController::class, 'showDetail'])->name('client.post.detail');

    //Route cần đăng nhập
    Route::middleware(['auth', 'check.account.status'])->group(function () {
        Route::post('/create', [PostController::class, 'createPostClient'])->name('client.createPostClient');
        Route::get('/my-posts', [PostController::class, 'myPosts'])->name('client.myPosts');
        Route::get('/posts/edit/{post}', [PostController::class, 'editClient'])->name('client.editPosts');
        Route::get('/posts/{slug}/{post}', [PostController::class, 'show'])->name('client.showPosts');
        Route::put('/posts/{post}', [PostController::class, 'updateClient'])->name('client.updatePosts');
        Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('client.destroyPosts');
        Route::delete('/my-posts/destroy-all', [PostController::class, 'destroyAll'])->name('client.destroyAllPosts');
    });
});


// Route bình luận 
Route::middleware(['auth', 'check.account.status'])->group(function () {
    Route::post('/posts/{postId}/comment', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});