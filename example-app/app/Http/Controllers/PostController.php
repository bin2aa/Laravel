<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;

class PostController extends Controller
{

    public function listPost()
    {
        $posts = Post::all();
        return view('post.listPost', compact('posts'));
    }

    public function showCreatePostForm()
    {
        return view('post.createPost');
    }

    public function createPost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'publish_date' => 'required|date',
            'status' => 'required|integer',
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
            'content' => $request->content,
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

        return redirect()->route('listPosts')->with('success', 'Bài viết đã được tạo thành công.');
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

}
