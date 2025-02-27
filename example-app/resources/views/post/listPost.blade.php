@extends('layouts.app')

@section('title', 'Danh sách bài viết')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Danh sách bài viết</h1>

    <!-- hiển thị ảnh test ở public/thumbnails -->
    <img src="{{ asset('storage/thumbnails/logon3.png') }}" alt="Thumbnail" width="100"> <br>
    <img src="{{ asset('storage/43/logon3.png') }}" alt="Thumbnail" width="100"> <br>

    <!-- Nút mở modal tạo bài viết -->
    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#createPostModal">
        Tạo bài viết
    </button>

    <!-- Modal tạo bài viết -->
    <div class="modal fade" id="createPostModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo bài viết mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('posts.createPost') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung</label>
                            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                        </div>
                        <input type="hidden" id="publish_date" name="publish_date" value="{{ now()->toDateString() }}">
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="0">Bản nháp</option>
                                <option value="1">Đã xuất bản</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu bài viết</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng danh sách bài viết -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Slug</th>
                <th>Mô tả</th>
                <th>Ngày đăng</th>
                <th>Trạng thái</th>
                <th>Thumbnail</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->slug }}</td>
                <td>{{ $post->description }}</td>
                <td>{{ $post->publish_date }}</td>
                <td>{{ $post->status }}</td>

                <!-- <td>
                    @if($post->thumbnail)
                    <img src="{{ Storage::url($post->thumbnail) }}" alt="Thumbnail" width="100">
                    @else
                    <span>Không có ảnh</span>
                    @endif
                </td> -->

                <!-- <td>
                    @if($post->hasMedia('thumbnails'))
                    <img src="{{ asset('storage/' . $post->getFirstMedia('thumbnails')->id . '/' . $post->getFirstMedia('thumbnails')->file_name) }}" alt="{{ $post->title }}" width="100">
                    <br>
                    <span>{{ asset('storage/' . $post->getFirstMedia('thumbnails')->id . '/' . $post->getFirstMedia('thumbnails')->file_name) }}</span>
                    @else
                    <span>Không có ảnh</span>
                    @endif
                </td> -->
                
                <!-- Cột hiển thị ảnh -->
                <td>
                    @if($post->hasMedia('thumbnails'))
                    <img src="{{ $post->getFirstMediaUrl('thumbnails') }}" alt="{{ $post->title }}" width="100">
                    @else
                    <span>Không có ảnh</span>
                    @endif
                </td>

                <!-- <td>
                    @if($post->hasMedia('thumbnails'))
                    {{ $post->getFirstMedia('thumbnails')->file_name }}
                    @else
                    <span>Không có ảnh</span>
                    @endif
                </td> -->
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Thông báo</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Cập nhật Thành công
            </div>
        </div>
    </div>

    @if(session('success'))
    <script>
        var toastEl = document.getElementById('successToast');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    </script>
    @endif

    <!-- Quay lại Dashboard -->
    <a href="{{ route('dashboardssss') }}" class="btn btn-primary">Quay lại</a>
</div>
@endsection