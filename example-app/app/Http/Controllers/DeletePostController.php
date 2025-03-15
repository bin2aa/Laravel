<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Post;

class DeletePostController extends Controller
{

    // Hiển thị danh sách bài viết đã xóa
    public function index()
    {
        $deletedPosts = Post::onlyTrashed()->paginate(10);
        return view('post.trash', compact('deletedPosts'));
    }

    // khôi phục bài viết đã xóa
    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();

        return redirect()->route('posts.trash')
            ->with('success', 'Bài viết đã được khôi phục thành công.');
    }


    // xóa vĩnh viễn bài viết
    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->forceDelete();

        return redirect()->route('posts.trash')
            ->with('success', 'Bài viết đã được xóa vĩnh viễn.');
    }
}
