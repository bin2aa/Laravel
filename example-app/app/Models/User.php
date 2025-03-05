<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [ // thêm dòng này để định nghĩa các trường có thể gán
        // 'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'address',
        'status',
        'role',
    ];


    protected $hidden = [ // thêm dòng này để ẩn mật khẩu
        'password',
        // 'remember_token',
    ];


    protected $casts = [ // thêm dòng này để định dạng dữ liệu
        'password' => 'hashed', // thêm dòng này để mã hóa mật khẩu
    ];

    public function getNameAttribute(): string // thêm dòng này để lấy tên đầy đủ
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
