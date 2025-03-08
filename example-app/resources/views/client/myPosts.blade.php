@extends('client.index')

@section('main-content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h4>Bài viết của tôi</h4>
            @if(count($posts) > 0)
                <form action="{{ route('client.destroyAllPosts') }}" method="POST" id="delete-all-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger" onclick="confirmDeleteAll()">
                        <i class="fas fa-trash-alt"></i> Xóa tất cả
                    </button>
                </form>
            @endif
        </div>

        <div class="row">
            @foreach($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        @if($post->hasMedia('thumbnails'))
                            <img src="{{ $post->getFirstMediaUrl('thumbnails') }}" class="card-img-top" alt="{{ $post->title }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ $post->description }}</p>
                            <p class="card-text">Ngày đăng: {{ $post->publish_date }}</p>
                            <p class="card-text">Trạng thái: {{ $post->status == 1 ? 'Đã duyệt' : 'Chưa duyệt' }}</p>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('client.showPosts', ['slug' => $post->slug, 'post' => $post->id]) }}"
                                    class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                                <a href="{{ route('client.editPosts', $post->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('client.destroyPosts', $post->id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function confirmLogout() {
            if (confirm('Bạn có chắc chắn muốn đăng xuất không?')) {
                document.getElementById('logout-form').submit();
            }
        }

        function confirmDeleteAll() {
            if (confirm('Bạn có chắc chắn muốn xóa tất cả bài viết của mình không? Hành động này không thể hoàn tác!')) {
                document.getElementById('delete-all-form').submit();
            }
        }
    </script>

    @if(session('success'))
        <script>
            // Show success message alert if needed
            // alert("{{ session('success') }}");
        </script>
    @endif
@endsection