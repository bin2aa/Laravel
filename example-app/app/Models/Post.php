<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'content',
        'publish_date',
        'status',
    ];

    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFT = 0;

    public function getStatusAttribute($value)
    {
        return $value == self::STATUS_PUBLISHED ? 'Đã xuất bản' : 'Bản nháp';
    }

    // Một bài viết thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class); //hasMany ngược lại
    }

    // phạm vi truy vấn để lấy ra bài viết đã xuất bản
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    // phạm vi truy vấn để lấy ra bài viết là bản nháp
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    // Dùng để xử lý ảnh đại diện của bài viết (thumbnail)
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10);

        $this->addMediaConversion('medium')
            ->width(800)
            ->height(600);

        $this->addMediaConversion('responsive')
            ->withResponsiveImages();
    }

    // Accessor cho thuộc tính thumbnail
    public function getThumbnailAttribute()
    {
        if ($this->hasMedia('thumbnails')) {
            return $this->getFirstMediaUrl('thumbnails');
        }
        return null;
    }

    // Accessor để lấy nội dung không có HTML
    // public function getPlainContentAttribute()
    // {
    //     return strip_tags($this->content);
    // }

    // Một bài viết có nhiều bình luận
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
