@extends('adminlte::page')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Chi tiết danh mục: {{ $category->name }}</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Thông tin danh mục</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">ID:</dt>
                        <dd class="col-sm-8">{{ $category->id }}</dd>
                        
                        <dt class="col-sm-4">Tên:</dt>
                        <dd class="col-sm-8">{{ $category->name }}</dd>
                        
                        <dt class="col-sm-4">Slug:</dt>
                        <dd class="col-sm-8">{{ $category->slug }}</dd>
                        
                        <dt class="col-sm-4">Ngày tạo:</dt>
                        <dd class="col-sm-8">{{ $category->created_at->format('d/m/Y H:i') }}</dd>
                        
                        <dt class="col-sm-4">Cập nhật:</dt>
                        <dd class="col-sm-8">{{ $category->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5>Mô tả</h5>
                </div>
                <div class="card-body">
                    {{ $category->description ?? 'Không có mô tả' }}
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Bài viết trong danh mục ({{ $posts->total() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tiêu đề</th>
                                    <th>Tác giả</th>
                                    <th>Ngày xuất bản</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->user->name ?? 'N/A' }}</td>
                                    <td>{{ $post->publish_date ? date('d/m/Y', strtotime($post->publish_date)) : 'N/A' }}</td>
                                    <td>
                                        <form action="{{ route('categories.removePost', ['category' => $category->id, 'post' => $post->id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này khỏi danh mục?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-unlink"></i> Xóa khỏi danh mục
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Chưa có bài viết nào trong danh mục này</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection