@extends('client.index')

@section('main-content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1>Xem bài viết</h1>
                <div class="card">
                    @if($post->hasMedia('thumbnails'))
                        <img src="{{ $post->getFirstMediaUrl('thumbnails') }}" class="card-img-top" alt="{{ $post->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ $post->description }}</p>
                        <p class="card-text">Ngày đăng: {{ $post->publish_date }}</p>
                        <p class="card-text">Trạng thái: {{ $post->status == 1 ? 'Đã duyệt' : 'Chưa duyệt' }}</p>
                        <p class="card-text">{!! $post->content !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection