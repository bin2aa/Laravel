<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password'); // lấy thông tin từ request

        if (Auth::attempt($credentials)) { // kiểm tra thông tin đăng nhập
            
            return redirect()->intended('dashboard')->with('successLogin','Đăng nhập thành công'); // chuyển hướng đến trang dashboard
        }

        return back()->withErrors([
            'email' => 'Tài khoản hoặc mật khẩu sai.',
        ])->withInput(); //withInput để giữ lại dữ liệu cũ từ old('email') 
    }

    public function logout()
    {
        Auth::logout(); // đăng xuất

        return redirect()->route('home')->with('successLogout', 'Đăng xuất thành công!');; // chuyển hướng về trang chủ
    }
}

//note
//only chỉ lấy giá chị cụ thể từ request "email" và "password"
//attempt kiểm tra thông tin đăng nhập
//intended chuyển hướng đến trang dashboard
