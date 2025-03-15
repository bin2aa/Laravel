<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description'
    ];


    // Tạo quan hệ n-n với bảng posts
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
