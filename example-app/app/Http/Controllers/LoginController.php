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
            $user = Auth::user(); // lấy thông tin user
            // Kiểm tra trạng thái tài khoản
            switch ($user->status) {
                case '0': // chờ phê duyệt (pending)
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['account_status0' => 'Tài khoản của bạn đang chờ phê duyệt.'])->withInput();
                case '1': // bị từ chối (refuse)
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['account_status1' => 'Tài khoản của bạn đã bị từ chối.'])->withInput();
                case '2': // bị khóa (lock)
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['account_status2' => 'Tài khoản của bạn đã bị khóa.'])->withInput();
                case '3': // đã duyệt ( chờ phê duyệt)
                    // if ($user->role === 'admin') {
                    return redirect()->intended('dashboard')->with('successLogin', 'Đăng nhập thành công'); // chuyển hướng đến trang dashboard
                    // } else {
                    //     return redirect()->intended('')->with('successLogin', 'Đăng nhập thành công'); 
                    // }
            }
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
