<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // dùng để kế thừa controller
use App\Models\User; // dùng để sử dụng model User
use Illuminate\Http\Request; // dùng để sử dụng request
use Illuminate\Support\Facades\Hash; // dùng để mã hóa mật khẩu
use Illuminate\Support\Facades\Validator; // dùng để kiểm tra dữ liệu
use Illuminate\Support\Facades\Mail; // dùng để gửi email
use App\Mail\RegisterMail; // dùng để sử dụng mẫu email
class RegisterController extends Controller
{

    public function register(Request $request) // hàm đăng ký
    {
        // tạo validator để kiểm tra dữ liệu
        $validator = Validator::make(
            $request->all(),
            [ // kiểm tra dữ liệu
                'first_name' => 'required|string|max:30', //
                'last_name' => 'required|string|max:30',
                // 'email' => 'required|string|email|max:100|unique:users',

                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:100',
                    'unique:users',
                    function ($attribute, $value, $fail) {
                        $domain = explode('@', $value)[1];
                        if (strtolower($domain) !== 'gmail.com') {
                            $fail('Chỉ được phép sử dụng email có domain @gmail.com.');
                        }
                    },
                ],

                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',      // phải chứa ít nhất một chữ cái thường
                    'regex:/[A-Z]/',      // phải chứa ít nhất một chữ cái hoa
                    'regex:/[0-9]/',      // phải chứa ít nhất một chữ số
                    'regex:/[@$!%*?&#]/', // phải chứa ít nhất một ký tự đặc biệt
                    'confirmed', // kiểm tra mật khẩu nhập lại "Cái này hoạt động tìm hậu tố _confirmation"
                ],
            ],
            [
                //THông báo lỗi email
                'email.required' => 'Email là bắt buộc.',
                'email.unique' => 'Email đã tồn tại.',
                //thông báo lỗi password
                'password.required' => 'Mật khẩu là bắt buộc.',
                'password.string' => 'Mật khẩu phải là một chuỗi ký tự.',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
                'password.regex' => 'Mật khẩu phải chứa ít nhất một chữ cái thường, một chữ cái hoa, một chữ số và một ký tự đặc biệt.',
                'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            ],


        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); // nếu dữ liệu không hợp lệ thì trả về trang trước và thông báo lỗi
        }                                                                              //withInput để giữ lại dữ liệu cũ

        // tạo tài khoản
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 0,
            'role' => 'user',
        ]);

        // gửi email thông báo đăng ký thành công MailTrap
        try {
            Mail::to($user->email)->send(new RegisterMail()); // gửi email thông báo đăng ký thành công 
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Không thể gửi email: ' . $e->getMessage());
        }

        // chuyển hướng đến trang chủ và thông báo đăng ký thành công
        return redirect()->route('home')->with('successRegister', 'Dăng ký thành công!');
    }
}
