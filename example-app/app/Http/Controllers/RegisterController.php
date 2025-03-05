<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserRequest; // Import UserRequest
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;

class RegisterController extends Controller
{
    public function register(UserRequest $request) // Sử dụng UserRequest
    {
        // tạo tài khoản
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 0,
            'role' => 'user',
        ]);

        // gửi email thông báo đăng ký thành công
        try {
            Mail::to($user->email)->send(new RegisterMail());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Không thể gửi email: ' . $e->getMessage());
        }

        // chuyển hướng đến trang chủ và thông báo đăng ký thành công
        return redirect()->route('home')->with('successRegister', 'Đăng ký thành công!');
    }
}