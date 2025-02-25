<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->status != 3) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Dùng session flash với key "error" hoặc "accountLocked"
                return redirect()->route('login')
                    ->with('accountLocked', 'Tài khoản của bạn đã bị vô hiệu hóa hoặc chưa được phê duyệt.');
            }
        }

        return $next($request);
    }
}