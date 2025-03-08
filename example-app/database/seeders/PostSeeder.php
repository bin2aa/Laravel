<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Đảm bảo có ít nhất một user với id=1
        if (!User::find(1)) {
            User::factory()->create(['id' => 1]);
        }
        
        // Đảm bảo có user với id=2
        if (!User::find(2)) {
            User::factory()->create(['id' => 2]);
        }

        Post::create([
            'user_id' => 1, // Thay thế bằng ID của user mà mình muốn
            'title' => 'Bài viết đầu tiên',
            'slug' => Str::slug('Bài viết đầu tiên'),
            'description' => 'Thịnh tạo thủ công.',
            'content' => 'Nội dung đầy đủ của bài viết đầu tiên.',
            'publish_date' => now(),
            'status' => Post::STATUS_PUBLISHED, // Đã xuất bản
        ]);
        
        // Tạo 5 bài viết
        Post::factory()->count(5)->create();
    }
}