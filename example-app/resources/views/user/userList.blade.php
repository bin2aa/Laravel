@extends('adminlte::page')

@section('title', 'Danh sách người dùng')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Danh sách người dùng</h1>

    <!-- Form tìm kiếm -->
    <form action="{{ route('users') }}" method="GET" class="mb-4">
        <div class="input-group">
            <select name="search_type" class="form-select">
                <option value="nameSearch" {{ request('search_type') == 'nameSearch' ? 'selected' : '' }}>Tìm kiếm theo tên</option>
                <option value="emailSearch" {{ request('search_type') == 'emailSearch' ? 'selected' : '' }}>Tìm kiếm theo email</option>
            </select>
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </div>
    </form>

    <!-- Bảng danh sách người dùng -->
    <table id='users-table' class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Địa chỉ</th>
                <th>Ngày tạo</th>
                <th>Ngày cập nhật</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->address }}</td>
                <td>{{ $user->created_at }}</td>
                <td>{{ $user->updated_at }}</td>
                <td>
                    <form action="{{ route('updateUserStatus', $user->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <select name="statusChange" class="form-select">
                            <option value="0" {{ $user->status == '0' ? 'selected' : '' }}>Đang chờ phê duyệt</option>
                            <option value="1" {{ $user->status == '1' ? 'selected' : '' }}>Bị từ chối</option>
                            <option value="2" {{ $user->status == '2' ? 'selected' : '' }}>Khóa</option>
                            <option value="3" {{ $user->status == '3' ? 'selected' : '' }}>Được duyệt</option>
                        </select>
                        <button type="submit" class="btn btn-primary mt-2">Cập nhật</button>
                    </form>
                </td>
                <td>

                    <div>
                        <a href="{{ route('users.posts', $user->id) }}" class="btn btn-primary">Xem bài viết</a>
                    </div>

                    <!-- Nút mở modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateUserModal{{ $user->id }}">
                        Cập nhật
                    </button>

                    <!-- Modal riêng biệt cho từng user -->
                    <div class="modal fade" id="updateUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Chỉnh sửa thông tin người dùng</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('updateUser', $user->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="first_name{{ $user->id }}" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="first_name{{ $user->id }}" name="first_nameUD" value="{{ $user->first_name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="last_name{{ $user->id }}" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="last_name{{ $user->id }}" name="last_nameUD" value="{{ $user->last_name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="address{{ $user->id }}" class="form-label">Address</label>
                                            <input type="text" class="form-control" id="address{{ $user->id }}" name="addressUD" value="{{ $user->address }}" maxlength="200" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>



            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed" style="bottom: 1rem; right: 1rem;">
        <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
            <div class="toast-header">
                <strong class="mr-auto">Thông báo</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                Cập nhật Thành công
            </div>
        </div>
    </div>
</div>
@endsection



@section('js')
<script>
    $(function() {
        // Khởi tạo DataTable
        $('#users-table').DataTable({
            "paging": true, // Hiển thị phân trang
            "lengthChange": true, // Hiển thị số bản ghi trên mỗi trang
            "searching": false, // Tìm kiếm
            "ordering": true, // Sắp xếp
            "info": true, // Hiển thị thông tin bảng
            "autoWidth": false, // Tự động điều chỉnh độ rộng cột
            "responsive": true, // Tự động điều chỉnh giao diện
            "language": {
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Sau",
                    "previous": "Trước"
                }
            }
        });

        // Hiển thị toast khi có thông báo thành công
        if ("{{ session('success') }}" || "{{ session('successUpdateUser') }}") {
            $('#successToast').toast('show');
        }
    });
</script>
@stop