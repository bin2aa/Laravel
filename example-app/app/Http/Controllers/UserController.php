<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function showUsers(Request $request)
    {
        // $users = User::all(); // Lấy tất cả người dùng từ cơ sở dữ liệu
        // return view('user.userList', compact('users')); // Trả về view và truyền danh sách người dùng

        $user = Auth::user();
        if ($user->role !== 'admin') {
            return abort(404);
        }


        $query = User::query();

        if ($request->has('search') && $request->has('search_type')) { // has dùng để kiểm tra xem có tồn tại giá trị trong request không
            $search = $request->input('search'); // Lấy giá trị từ request
            $searchType = $request->input('search_type');

            if ($searchType == 'nameSearch') {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            } elseif ($searchType == 'emailSearch') {
                $query->where('email', 'like', "%{$search}%");
            }
        }

        $users = $query->get();

        return view('user.userList', compact('users'));
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return abort(404);
        }

        $user = User::findOrFail($id);
        $user->status = $request->input('statusChange');
        $user->save();
        return redirect()->route('users')->with('successUpdateUser', 'Cập nhật trạng thái thành công');
    }

    public function updateUser(Request $request, $id)
    {

        $user = Auth::user();
        if ($user->role !== 'admin') {
            return abort(404);
        }

        $user = User::findOrFail($id);
        $user->first_name = $request->input('first_nameUD');
        $user->last_name = $request->input('last_nameUD');
        $user->address = $request->input('addressUD');
        $user->save();

        return redirect()->route('users')->with('success', 'Cập nhật thông tin người dùng thành công.');
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:30',
            'last_name' => 'required|string|max:20',
            'address' => 'required|string|max:200',
        ]);

        $user = User::findOrFail($id);
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->address = $request->input('address');
        $user->save();

        return redirect()->route('profile')->with('success', 'Thông tin cá nhân đã được cập nhật.');
    }

    public function showProfile()
    {
        $userDetails = Auth::user(); // Lấy thông tin người dùng hiện tại
        return view('profile.userDetails', compact('userDetails')); // Trả về view và truyền thông tin người dùng
    }
}

//note:
//all() lấy tất cả người dùng từ cơ sở dữ liệu
//compact"nhỏ gọn" truyền danh sách người dùng vào view
//has dùng để kiểm tra xem có tồn tại giá trị trong request không