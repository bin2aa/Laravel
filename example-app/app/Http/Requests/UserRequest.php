<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:30',
            'last_name' => 'required|string|max:30',
            'email' => 'required|string|email|max:100|unique:users,email,' . $this->route('id'),
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // phải chứa ít nhất một chữ cái thường
                'regex:/[A-Z]/',      // phải chứa ít nhất một chữ cái hoa
                'regex:/[0-9]/',      // phải chứa ít nhất một chữ số
                'regex:/[@$!%*?&#]/', // phải chứa ít nhất một ký tự đặc biệt
                'confirmed',          // kiểm tra mật khẩu nhập lại
            ],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Tên là bắt buộc.',
            'last_name.required' => 'Họ là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.regex' => 'Mật khẩu phải chứa ít nhất một chữ cái thường, một chữ cái hoa, một chữ số và một ký tự đặc biệt.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ];
    }

    
}
