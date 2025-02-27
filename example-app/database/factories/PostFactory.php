<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'user_id' => 1, // Hoặc bạn có thể tạo user_id ngẫu nhiên
            'title' => $this->faker->sentence,
            'slug' => Str::slug($this->faker->sentence),
            'description' => $this->faker->paragraph,
            'content' => $this->faker->text,
            'publish_date' => $this->faker->date,
            'status' => $this->faker->randomElement([1, 0]),
        ];
    }
}