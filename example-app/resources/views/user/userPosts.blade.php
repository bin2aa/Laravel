@extends('adminlte::page')

@section('title', 'Bài viết của ' . $user->name)

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Bài viết của {{ $user->first_name }} {{ $user->last_name }}</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Mô tả</th>
                <th>Ngày xuất bản</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->description }}</td>
                <td>{{ $post->publish_date }}</td>
                <td>{{ $post->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Quay lại danh sách người dùng -->
    <a href="{{ route('users') }}" class="btn btn-primary">Quay lại</a>
</div>
@endsection