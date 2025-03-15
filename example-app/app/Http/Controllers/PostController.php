<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Requests\PostRequest;
use App\Jobs\SendPostStatusEmail;

class PostController extends Controller
{

    // public function listPost()
    // {
    //     $posts = Post::all();
    //     // $posts = Post::where('user_id', auth()->id())->get(); // Lấy ra tất cả bài viết của user đang đăng nhập
    //     return view('post.listPost', compact('posts'));
    // }

    public function listPost()
    {
        $posts = Post::with('categories')->get();
        $categories = Category::all();
        return view('post.listPost', compact('posts', 'categories'));
    }

    public function listAllPosts()
    {
        // $posts = Post::with('user')->get();
        return view('dashboard');
    }


    public function showCreatePostForm()
    {
        $categories = Category::all(); // Lấy ra tất cả danh mục
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
            'content' => $request->content, // Loại bỏ tất cả thẻ HTML
            'publish_date' => $request->publish_date,
            'status' => $request->status,
        ]);

        if ($request->hasFile('thumbnail')) {
            $post->addMedia($request->file('thumbnail'))->toMediaCollection('thumbnails');
        }

        if ($request->has('categories')) {
            $post->categories()->attach($request->categories);
        }

        return redirect()->route('listPosts')->with('success', 'Bài viết đã được tạo thành công.')->withInput();
    }

    public function listPublishedPosts()
    {
        $posts = Post::published()->get();
        return view('post.listPost', compact('posts'));
    }

    public function listDraftPosts()
    {
        // lấy ra tất cả bài viết chưa được duyệtf
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

        if ($request->has('categories')) {
            $post->categories()->sync($request->categories);
        } else {
            $post->categories()->detach();
        }

        return redirect()->route('listPosts')->with('success', 'Bài viết đã được cập nhật thành công');
    }



    public function editPost(Post $post)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này');
        }
        $categories = Category::all(); // Lấy tất cả danh mục
        $postCategories = $post->categories->pluck('id')->toArray(); // Lấy ID các danh mục của bài viết

        return view('post.editPost', compact('post', 'categories', 'postCategories'));
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

        $post->status = Post::STATUS_PUBLISHED; // 1
        $post->save();

        // Đưa job vào queue
        SendPostStatusEmail::dispatch($post, true);

        return redirect()->back()->with('success', 'Đã phê duyệt bài viết thành công');
    }

    public function unapprovePost(Post $post)
    {
        // Kiểm tra quyền
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền hủy phê duyệt bài viết');
        }

        $post->status = Post::STATUS_DRAFT; // 0
        $post->save();

        // Đưa job vào queue
        SendPostStatusEmail::dispatch($post, false);

        return redirect()->back()->with('success', 'Đã hủy phê duyệt bài viết thành công');
    }


    // ---------------- Client ----------------


    // public function index()
    // {
    //     $posts = Post::where('status', 1)
    //         ->orderBy('publish_date', 'desc') // Sắp xếp theo publish_date giảm dần
    //         ->paginate(6); // 1 là đã duyệt
    //     return view('client.news', compact('posts'));
    // }


    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm
        $search = $request->input('search');

        // Lấy id danh mục từ URL
        $categoryId = $request->input('category');

        // truy vấn bài viết
        $postsQuery = Post::where('status', Post::STATUS_PUBLISHED);

        // Thêm điều kiện tìm kiếm nếu có
        if ($search) {
            $postsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Lọc theo danh mục nếu có
        if ($categoryId) {
            $postsQuery->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        // Lấy danh sách bài viết đã lọc và phân trang
        $posts = $postsQuery->orderBy('publish_date', 'desc')
            ->paginate(6);

        // Lấy danh sách danh mục kèm số lượng bài viết đã xuất bản
        $categories = Category::withCount(['posts' => function ($query) {
            $query->where('posts.status', Post::STATUS_PUBLISHED);
        }])->get();

        return view('client.news', compact('posts', 'categories', 'search'));
    }

    public function createPostClient(PostRequest $request)
    {

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
            'content' => $request->content, // Loại bỏ tất cả thẻ HTML
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
        // Lấy tất cả danh mục
        $categories = Category::all();

        // Lấy ID các danh mục của bài viết
        $postCategories = $post->categories->pluck('id')->toArray();

        return view('client.editPosts', compact('post', 'categories', 'postCategories'));
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

        if ($request->has('categories')) {
            $post->categories()->sync($request->categories);
        } else {
            $post->categories()->detach();
        }

        return redirect()->route('client.myPosts')->with('success', 'Bài viết đã được cập nhật thành công.');
    }




    public function myPosts()
    {
        $posts = Post::where('user_id', auth()->id())->get();
        return view('client.myPosts', compact('posts'));
    }

    // Hiển thị chi tiết bài viết dùng binding model
    public function show($slug, Post $post)
    {
        // Kiểm tra xem slug có khớp hay không
        if ($post->slug !== $slug) {
            return redirect()->route('client.showPosts', ['slug' => $post->slug, 'post' => $post->id]);
        }

        $comments = $post->comments()->with('user')->latest()->get();

        return view('client.showPosts', compact('post', 'comments'));
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

    // Hiển thị chi tiết bài viết nhưng không dùng binding model
    public function showDetail($slug, $id)
    {
        $post = Post::where('id', $id)
            ->where('slug', $slug)
            ->where('status', Post::STATUS_PUBLISHED)
            ->firstOrFail();

        $comments = $post->comments()->with('user')->latest()->get();

        return view('client.showPosts', compact('post', 'comments'));
    }
}
