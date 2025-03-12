<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Http\Requests\CommentRequest;


class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);
        return redirect()->back()->with('success', 'Bình luận của bạn đã được gửi!');
    }

    public function destroy(Comment $comment)
    {

        // Kiểm tra quyền xóa bình luận
        if ($comment->user_id != auth()->id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa bình luận này!');
        }

        $comment->delete();
        return redirect()->back()->with('success', 'Xóa bình luận thành công!');
    }
}
