<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showUsers()
    {
        $users = User::all(); // Lấy tất cả người dùng từ cơ sở dữ liệu
        return view('user.userList', compact('users')); // Trả về view và truyền danh sách người dùng
    }
}

//note:
//all() lấy tất cả người dùng từ cơ sở dữ liệu
//compact"nhỏ gọn" truyền danh sách người dùng vào view
