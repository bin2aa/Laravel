@extends('adminlte::page')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Bài viết đã xóa</h3>
            <a href="{{ route('listPosts') }}" class="btn btn-primary">Quay lại danh sách bài viết</a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Ngày xóa</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deletedPosts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->deleted_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('posts.restore', $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">Khôi phục</button>
                            </form>
                            <form action="{{ route('posts.force-delete', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn bài viết này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Xóa vĩnh viễn</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Không có bài viết nào trong thùng rác</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            {{ $deletedPosts->links() }}
        </div>
    </div>
</div>
@endsection