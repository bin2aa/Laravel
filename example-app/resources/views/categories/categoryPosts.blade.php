{{-- @extends('adminlte::page')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="category-title">Danh mục: {{ $category->name }}</h1>
            @if($category->description)
            <div class="category-description">
                {{ $category->description }}
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        @forelse($posts as $post)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($post->thumbnail)
                <img src="{{ $post->thumbnail }}" class="card-img-top" alt="{{ $post->title }}">
                @else
                <div class="placeholder-img card-img-top d-flex align-items-center justify-content-center bg-light">
                    <span class="text-muted">No Image</span>
                </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text text-truncate">{{ $post->description }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">{{ date('d/m/Y', strtotime($post->publish_date)) }}</small>
                        <a href="{{ route('client.showPosts', ['slug' => $post->slug, 'post' => $post->id]) }}" class="btn btn-primary btn-sm">Đọc thêm</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-md-12">
            <div class="alert alert-info">
                Không có bài viết nào trong danh mục này.
            </div>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $posts->links() }}
    </div>
</div>
@endsection --}}