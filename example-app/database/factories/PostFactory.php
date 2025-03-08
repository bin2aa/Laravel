<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            // Lấy ID người dùng hiện có, hoặc tạo mới nếu cần
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'slug' => Str::slug($this->faker->sentence),
            'description' => $this->faker->paragraph,
            'content' => $this->faker->text,
            'publish_date' => $this->faker->date,
            'status' => $this->faker->randomElement([1, 0]),
        ];
    }
}