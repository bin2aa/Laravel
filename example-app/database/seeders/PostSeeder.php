<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::create([
            'user_id' => 2, // Thay thế bằng ID của user bạn muốn
            'title' => 'Bài viết đầu tiên',
            'slug' => Str::slug('Bài viết đầu tiên'),
            'description' => 'Thịnh tạo thủ công.',
            'content' => 'Nội dung đầy đủ của bài viết đầu tiên.',
            'publish_date' => now(),
            'status' => Post::STATUS_PUBLISHED, // Đã xuất bản
        ]);
    }
}