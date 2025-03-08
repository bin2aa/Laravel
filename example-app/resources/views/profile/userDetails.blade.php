@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Thông tin cá nhân</h1>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Họ và tên: {{ $userDetails->first_name }} {{ $userDetails->last_name }}</h5>
                        <p class="card-text">Email: {{ $userDetails->email }}</p>
                        <p class="card-text">Địa chỉ: {{ $userDetails->address }}</p>
                        <p class="card-text">Chức vụ: {{ $userDetails->role }}</p>
                        <!-- quay lại -->
                      <a href="javascript:void(0)" onclick="window.history.back()" class="btn btn-primary">Quay lại</a>
                        <!-- nút chỉnh sửa -->
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#editProfileModal">
                            Chỉnh sửa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal chỉnh sửa thông tin -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Chỉnh sửa thông tin cá nhân</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('profile.updateProfile', $userDetails->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                value="{{ $userDetails->first_name }}" maxlength="30" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                value="{{ $userDetails->last_name }}" maxlength="20" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address"
                                value="{{ $userDetails->address }}" maxlength="200" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="position-fixed" style="right: 15px; bottom: 15px; z-index: 1050;">
        <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
            <div class="toast-header">
                <strong class="mr-auto">Thông báo</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                Cập nhật hồ sơ thành công!
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    @if(session('success'))
        <script>
            $(document).ready(function () {
                $('#successToast').toast('show');
            });
        </script>
    @endif
@endsection