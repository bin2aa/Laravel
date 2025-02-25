@extends('layouts.app')

@section('title', 'Danh sách người dùng')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Danh sách người dùng</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Ngày tạo</th>
                <th>Ngày cập nhật</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at }}</td>
                <td>{{ $user->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection