<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Add this import

class LoginController extends Controller
{
    public function login(Request $request)
    {
        Log::info('Login attempt', ['email' => $request->email]); // Log login attempt
        
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            Log::info('User authenticated', ['user_id' => $user->id, 'role' => $user->role, 'status' => $user->status]);
            
            // Kiểm tra trạng thái tài khoản
            switch ($user->status) {
                case '0': // chờ phê duyệt (pending)
                    Log::info('Login rejected: account pending approval', ['user_id' => $user->id]);
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['account_status0' => 'Tài khoản của bạn đang chờ phê duyệt.'])->withInput();
                case '1': // bị từ chối (refuse)
                    Log::info('Login rejected: account refused', ['user_id' => $user->id]);
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['account_status1' => 'Tài khoản của bạn đã bị từ chối.'])->withInput();
                case '2': // bị khóa (lock)
                    Log::info('Login rejected: account locked', ['user_id' => $user->id]);
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['account_status2' => 'Tài khoản của bạn đã bị khóa.'])->withInput();
                case '3': // đã duyệt (chờ phê duyệt)
                    if ($user->role === 'admin') {
                        Log::info('Admin login successful', ['user_id' => $user->id]);
                        return redirect()->intended('dashboard')->with('successLogin', 'Đăng nhập thành công');
                    } else {
                        Log::info('User login successful', ['user_id' => $user->id]);
                        return redirect()->route('home')->with('successLogin', 'Đăng nhập thành công');
                    }
            }
        }

        Log::warning('Failed login attempt', ['email' => $request->email]);
        return back()->withErrors([
            'email' => 'Tài khoản hoặc mật khẩu sai.',
        ])->withInput();
    }

    public function logout()
    {
        if (Auth::check()) {
            Log::info('User logout', ['user_id' => Auth::id()]);
        }
        Auth::logout();

        return redirect()->route('home')->with('successLogout', 'Đăng xuất thành công!');
    }
}

//note
//only chỉ lấy giá chị cụ thể từ request "email" và "password"
//attempt kiểm tra thông tin đăng nhập
//intended chuyển hướng đến trang dashboard
