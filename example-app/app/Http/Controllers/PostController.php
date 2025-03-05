<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{

    public function listPost()
    {
        $posts = Post::all();
        // $posts = Post::where('user_id', auth()->id())->get(); // Lấy ra tất cả bài viết của user đang đăng nhập
        return view('post.listPost', compact('posts'));
    }

    public function listAllPosts()
    {
        $posts = Post::with('user')->get();
        return view('dashboard', compact('posts'));
    }

    public function showCreatePostForm()
    {
        return view('post.createPost');
    }

    public function createPost(PostRequest $request)
    {
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'slug' => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'content' => 'required|string',
        //     'publish_date' => 'required|date',
        //     'status' => 'required|integer',
        // ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
            // 'content' => $request->content,
            'content' => strip_tags($request->content), // Loại bỏ tất cả thẻ HTML
            'publish_date' => $request->publish_date,
            'status' => $request->status,
        ]);

        if ($request->hasFile('thumbnail')) {
            $post->addMedia($request->file('thumbnail'))->toMediaCollection('thumbnails');
        }


        // if ($request->hasFile('thumbnail')) {
        //     $path = $request->file('thumbnail')->store('public/thumbnails');
        //     $post->thumbnail = $path;
        //     $post->save();
        // }

        return redirect()->route('listPosts')->with('success', 'Bài viết đã được tạo thành công.')->withInput();
    }

    public function listPublishedPosts()
    {
        $posts = Post::published()->get();
        return view('post.listPost', compact('posts'));
    }

    public function listDraftPosts()
    {
        $posts = Post::draft()->get();
        return view('post.listPost', compact('posts'));
    }

    public function updatePost(PostRequest $request, Post $post)
    {
        // Kiểm tra quyền admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền cập nhật bài viết này');
        }

        $post->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
            'content' => $request->content,
            'status' => $request->status,
        ]);

        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ trước khi thêm ảnh mới
            $post->clearMediaCollection('thumbnails');
            $post->addMedia($request->file('thumbnail'))->toMediaCollection('thumbnails');
        }

        return redirect()->route('listPosts')->with('success', 'Bài viết đã được cập nhật thành công');
    }



    public function editPost(Post $post)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này');
        }

        return view('post.editPost', compact('post'));
    }

    public function deletePost(Post $post)
    {
        // Kiểm tra quyền admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền xóa bài viết này');
        }

        // Xóa media trước khi xóa bài viết
        $post->clearMediaCollection('thumbnails');
        $post->delete();

        return redirect()->route('listPosts')->with('success', 'Bài viết đã được xóa thành công');
    }


    public function approvePost(Post $post)
    {
        // Kiểm tra quyền
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền phê duyệt bài viết');
        }

        $post->status = Post::STATUS_PUBLISHED;
        $post->save();

        return redirect()->back()->with('success', 'Đã phê duyệt bài viết thành công');
    }

    public function unapprovePost(Post $post)
    {
        // Kiểm tra quyền
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền hủy phê duyệt bài viết');
        }

        $post->status = Post::STATUS_DRAFT;
        $post->save();

        return redirect()->back()->with('success', 'Đã hủy phê duyệt bài viết thành công');
    }


    // ---------------- Client ----------------


    public function index()
    {
        $posts = Post::where('status', 1)->get(); // 1 là đã duyệt
        return view('client.home', compact('posts'));
    }

    public function createPostClient(PostRequest $request)
    {

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
            'content' => strip_tags($request->content), // Loại bỏ tất cả thẻ HTML
            'publish_date' => $request->publish_date,
            'status' => $request->status,
        ]);

        if ($request->hasFile('thumbnail')) {
            $post->addMedia($request->file('thumbnail'))->toMediaCollection('thumbnails');
        }


        return redirect()->route('client.index')->with('success', 'Bài viết đã được tạo thành công.')->withInput();
    }


    public function editClient(Post $post)
    {
        // Kiểm tra quyền truy cập (chỉ chủ sở hữu mới có thể sửa)
        if ($post->user_id !== auth()->id()) {
            abort(404);
        }

        return view('client.editPosts', compact('post'));
    }

    public function updateClient(PostRequest $request, Post $post)
    {
        // Kiểm tra quyền truy cập (chỉ chủ sở hữu mới có thể sửa)
        if ($post->user_id !== auth()->id()) {
            abort(404);
        }


        $post->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
            'content' => strip_tags($request->content),
            'publish_date' => $request->publish_date,
            'status' => $request->status,
        ]);

        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ trước khi thêm ảnh mới
            $post->clearMediaCollection('thumbnails');
            $post->addMedia($request->file('thumbnail'))->toMediaCollection('thumbnails');
        }

        return redirect()->route('client.myPosts')->with('success', 'Bài viết đã được cập nhật thành công.');
    }




    public function myPosts()
    {
        $posts = Post::where('user_id', auth()->id())->get();
        return view('client.myPosts', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('client.showPosts', compact('post'));
    }





    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('client.myPosts')->with('success', 'Bài viết đã được xóa thành công.');
    }

    public function destroyAll()
    {
        Post::where('user_id', auth()->id())->delete();
        return redirect()->route('client.myPosts')->with('success', 'Tất cả bài viết đã được xóa thành công.');
    }
}
