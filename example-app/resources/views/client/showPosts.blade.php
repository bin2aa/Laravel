@extends('client.index')

@section('main-content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1>Xem bài viết</h1>
                <div class="card mb-4">
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

                <!-- Comments Section -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5>Bình luận ({{ isset($comments) ? $comments->count() : 0 }})</h5>
                    </div>
                    <div class="card-body">
                        @if(Auth::check())
                            <!-- Comment Form -->
                            <form action="{{ route('comments.store', $post->id) }}" method="post" class="mb-4">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                    <textarea class="form-control @error('content') is-invalid @enderror" name="content"
                                        rows="3" placeholder="Viết bình luận của bạn...">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                                    @enderror
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary mt-2">Gửi bình luận</button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-info">
                                <a href="{{ route('login') }}">Đăng nhập</a> để bình luận về bài viết này.
                            </div>
                        @endif

                        <!-- Display Comments -->
                        @if(isset($comments) && $comments->count() > 0)
                            <div class="comments-list">
                                @foreach($comments as $comment)
                                    <div class="comment-item card mb-2">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="comment-user">{{ $comment->user->name }}
                                                </h6>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="comment-content">{{ $comment->content }}</p>

                                            @if(Auth::check() && (Auth::id() == $comment->user_id || Auth::user()->role == 'admin'))
                                                <form action="{{ route('comments.destroy', $comment->id) }}" method="post"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa bình luận này?')">Xóa</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-muted">Chưa có bình luận nào. Hãy là người bình luận đầu tiên!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection