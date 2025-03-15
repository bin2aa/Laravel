@extends('layouts.app')

@section('title', 'Client Page')

@section('styles')
@endsection

@section('content')
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-info shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="/">Nguyễn Thanh Thịnh</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Trang chủ</a>
                        </li>
                        @auth
                            <li class="nav-item mx-2">
                                <!-- Nút mở modal tạo bài viết -->
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#createPostModal">
                                    <i class="fas fa-plus-circle me-1"></i> Tạo bài viết
                                </button>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('client.myPosts') }}">Bài viết của tôi</a>
                            </li>
                        @endauth
                    </ul>
                    <div class="d-flex align-items-center">
                        @auth
                            <div class="dropdown">
                                <a class="btn btn-light dropdown-toggle" href="#" role="button" id="userDropdown"
                                    data-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ route('profile') }}">Thông tin cá nhân</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form id="logout-form" action="{{ route('logout') }}" method="post">
                                            @csrf
                                            <button type="button" class="dropdown-item text-danger" onclick="confirmLogout()">
                                                Đăng xuất
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="/login" class="btn btn-outline-primary me-2">Đăng nhập</a>
                            <a href="/register" class="btn btn-primary">Đăng ký</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content & Sidebar -->
    <div class="container mt-4 content">
        <div class="row">
            <!-- Main Content -->
            <main class="col-12">
                @yield('main-content')
            </main>
        </div>
    </div>


    <!-- Modal tạo bài viết -->
    <div class="modal fade" id="createPostModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo bài viết mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('client.createPostClient') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                required>{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung</label>
                            <textarea class="editor form-control" id="content" name="content"
                                rows="10">{{ old('content') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="categories" class="form-label">Danh mục</label>
                            <select class="form-control" id="categories" name="categories[]" multiple>
                                @foreach ($categories ?? [] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Giữ Ctrl để chọn nhiều danh mục</small>
                        </div>

                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                        </div>
                        
                        <input type="hidden" id="publish_date" name="publish_date" value="{{ now()->toDateString() }}">
                        <input type="hidden" id="status" name="status" value="{{ old('status', 0) }}">
                        <button type="submit" class="btn btn-primary">Lưu bài viết</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast (Bootstrap 4) -->
    <div aria-live="polite" aria-atomic="true"
        style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
            <div class="toast-header">
                <strong class="mr-auto">Thông báo</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Hàm chuyển đổi slug (có thể dùng chung)
        function convertToSlug(text) {
            return text
                .toLowerCase()
                .normalize('NFD') // loại bỏ dấu
                .replace(/[\u0300-\u036f]/g, '') // loại bỏ dấu tiếng Việt
                .replace(/[^\w\s-]/g, '') // loại bỏ ký tự đặc biệt
                .replace(/\s+/g, '-') // thay dấu cách bằng dấu gạch ngang
                .replace(/-+/g, '-') // loại bỏ gạch ngang liên tiếp
                .trim(); // loại bỏ khoảng trắng đầu và cuối
        }

        $(document).ready(function () {

            var toast = {
                "success": "{{ session('success') }}"
            };
            if (toast.success) {
                $('.toast').toast('show');
            }

            // Xử lý khởi tạo CKEditor cho modal khi modal được mở
            // show.bs.modal là sự kiện xảy ra khi modal được mở
            $('#createPostModal').on('shown.bs.modal', function () {
                // Khởi tạo CKEditor trong modal sau khi modal hiển thị
                if (!window.editorInitialized) {
                    ClassicEditor
                        .create(document.querySelector('#createPostModal #content'))
                    window.editorInitialized = true;
                }

                $('#main-content').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "language": {
                        "paginate": {
                            "first": "Đầu",
                            "last": "Cuối",
                            "next": "Sau",
                            "previous": "Trước"
                        },
                    }
                });

            });



            // Xử lý modal form slug 
            const modalTitleInput = document.querySelector('#createPostModal #title');
            const modalSlugInput = document.querySelector('#createPostModal #slug');
            // Nếu tồn tại cả 2 input title và slug thì thêm sự kiện input cho title để tự động tạo slug
            if (modalTitleInput && modalSlugInput) {
                modalTitleInput.addEventListener('input', function () {
                    modalSlugInput.value = convertToSlug(modalTitleInput.value);
                });
            }
        });

        // Hàm xác nhận logout
        function confirmLogout() {
            if (confirm('Bạn có chắc chắn muốn đăng xuất không?')) {
                document.getElementById('logout-form').submit();
            }
        }

        //CKEditor



    </script>
@endpush