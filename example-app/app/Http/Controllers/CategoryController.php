<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // $categories = Category::all();
        // $categories = Category::withCount('posts')->get();
        $categories = Category::withCount('posts')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.createCategory');
    }

    public function show(Category $category)
    {

        $posts = $category->posts()->paginate(10);
        return view('categories.showCategory', compact('category', 'posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Tạo slug từ tên danh mục 
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Danh mục đã được tạo thành công!');
    }

    public function edit(Category $category)
    {

        return view('categories.editCategory', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Danh mục đã được xóa thành công!');
    }

    public function addPost(Request $request, Category $category)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id'
        ]);

        $category->posts()->attach($request->post_id);

        return redirect()->route('categories.show', $category)
            ->with('success', 'Bài viết đã được thêm vào danh mục thành công!');
    }
}
