@extends('client.index')

@section('main-content')
    <div class="container mt-4">
        <div class="row">
            @foreach($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">

                            @if($post->hasMedia('thumbnails'))
                                <img src="{{ $post->getFirstMediaUrl('thumbnails') }}" class="card-img-top" alt="{{ $post->title }}"
                                    style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light text-center py-5">
                                    <i class="fas fa-image fa-3x text-secondary"></i>
                                </div>
                            @endif


                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ $post->description }}</p>


                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt"></i> Ngày đăng:
                                    {{ date('d/m/Y', strtotime($post->publish_date)) }}
                                </small>
                            </p>

                            <div class="mt-3">
                                <a href="{{ route('client.post.detail', ['id' => $post->id, 'slug' => $post->slug]) }}"
                                    class="btn btn-primary btn-sm">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
            {{ $posts->links() }}
        </div>
    </div>
@endsection