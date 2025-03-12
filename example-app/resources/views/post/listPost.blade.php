@extends('adminlte::page')
@section('plugins.Datatables', true)

@section('title', 'Danh sách bài viết')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="text-center mb-4">Danh sách bài viết</h1>

            <!-- Nút mở modal tạo bài viết -->
            <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#createPostModal">
                Tạo bài viết
            </button>

            <div class="mb-3">
                <button type="button" class="btn btn-outline-secondary filter-button active" data-filter="all">Tất
                    cả</button>
                <button type="button" class="btn btn-outline-success filter-button" data-filter="Đã xuất bản">Đã duyệt
                    bản</button>
                <button type="button" class="btn btn-outline-warning filter-button" data-filter="Bản nháp">Chưa duyệt</button>
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
                        <form action="{{ route('posts.createPost') }}" method="POST" enctype="multipart/form-data">
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
                                <label for="thumbnail" class="form-label">Thumbnail</label>
                                <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                            </div>
                            <input type="hidden" id="publish_date" name="publish_date" value="{{ now() }}">
                            <input type="hidden" id="status" name="status" value="{{ old('status', 0) }}">
                            <button type="submit" class="btn btn-primary">Lưu bài viết</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal sửa bài viết -->
        @foreach ($posts as $post)
            <div class="modal fade" id="editPostModal{{ $post->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Chỉnh sửa bài viết</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('posts.updatePost', $post->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="title{{ $post->id }}" class="form-label">Tiêu đề</label>
                                    <input type="text" class="form-control" id="title{{ $post->id }}" name="title"
                                        value="{{ $post->title }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="slug{{ $post->id }}" class="form-label">Slug</label>
                                    <input type="text" class="form-control" id="slug{{ $post->id }}" name="slug"
                                        value="{{ $post->slug }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description{{ $post->id }}" class="form-label">Mô tả</label>
                                    <textarea class="form-control" id="description{{ $post->id }}" name="description" rows="3"
                                        required>{{ $post->description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="content{{ $post->id }}" class="form-label">Nội dung</label>
                                    <textarea class="editor-edit form-control" id="content{{ $post->id }}" name="content"
                                        rows="10">{{ $post->content }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="status{{ $post->id }}" class="form-label">Trạng thái</label>
                                    <select class="form-control" id="status{{ $post->id }}" name="status">
                                        <option value="0" {{ $post->getRawOriginal('status') == 0 ? 'selected' : '' }}>Bản nháp
                                        </option>
                                        <option value="1" {{ $post->getRawOriginal('status') == 1 ? 'selected' : '' }}>Đã xuất bản
                                        </option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="thumbnail{{ $post->id }}" class="form-label">Thumbnail</label>
                                    <input type="file" class="form-control" id="thumbnail{{ $post->id }}" name="thumbnail">
                                    @if($post->thumbnail)
                                        <div class="mt-2">
                                            <img src="{{ $post->thumbnail }}" width="100" alt="Current thumbnail">
                                            <p class="small">Ảnh hiện tại</p>
                                        </div>
                                    @endif
                                </div>
                                <input type="hidden" name="publish_date" value="{{ $post->publish_date }}">
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach


        <div class="card-body">
            <!-- Bảng danh sách bài viết -->
            <table id="posts-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người đăng</th>
                        <th>Tiêu đề</th>
                        <th>Slug</th>
                        <th>Mô tả</th>
                        <th>Ngày đăng</th>
                        <th>Nội dung</th>
                        <th>Trạng thái</th>
                        <th>Thumbnail</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td> {{ $post->user->name }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->slug }}</td>
                            <td>{{ $post->description }}</td>
                            <td>{{ $post->publish_date }}</td>
                            <td>{{ $post->content }}</td>
                            <td>{{ $post->status }}</td>

                            <!-- Cột hiển thị ảnh -->
                            <td>
                                @if($post->thumbnail)
                                    <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" width="100">
                                @else
                                    <span>Không có ảnh</span>
                                @endif
                            </td>

                            <td>


                                @if($post->getRawOriginal('status') == 0)
                                    <form action="{{ route('posts.approvePost', $post->id) }}" method="POST" class="d-inline ml-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Phê duyệt
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('posts.unapprovePost', $post->id) }}" method="POST"
                                        class="d-inline ml-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="fas fa-times"></i> Hủy duyệt
                                        </button>
                                    </form>
                                @endif

                                <div class="btn-group">
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                        data-target="#editPostModal{{ $post->id }}">
                                        <i class="fas fa-edit"></i> Sửa
                                    </button>

                                    <form action="{{ route('posts.deletePost', $post->id) }}" method="POST"
                                        class="d-inline ml-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>




                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Toast Notification -->
            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">Thông báo</strong>
                        <button type="button" class="close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">
                        Cập nhật Thành công
                    </div>
                </div>
            </div>


            @if(session('success'))
                <script>
                    $(document).ready(function () {
                        $('#successToast').toast('show');
                    });
                </script>
            @endif
        </div>
    </div>
@endsection


<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

@section('js')
<script>
    $(document).ready(function () {
        // Khởi tạo DataTable
        var table = $('#posts-table').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            // "pageLength": 2,  // Thiết lập số dòng mỗi trang
            // "lengthMenu": [2, 5, 10, 25, 50],  // Tùy chọn số dòng cho người dùng
            "language": {
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Sau",
                    "previous": "Trước"
                },
            }
        });

        // XỬ LÝ BỘ LỌC TRẠNG THÁI
        $('.filter-button').on('click', function() {
            // Xóa class active khỏi tất cả các nút
            $('.filter-button').removeClass('active');
            
            // Thêm class active vào nút được click
            $(this).addClass('active');
            
            // Lấy giá trị filter
            var filterValue = $(this).data('filter');
            
            // Áp dụng filter
            if (filterValue === 'all') {
                table.column(7).search('').draw(); // Xóa bộ lọc, hiển thị tất cả
            } else {
                table.column(7).search(filterValue).draw(); // Lọc theo cột trạng thái (cột 7)
            }
        });

        // Khởi tạo CKEditor trên content
        ClassicEditor
            .create(document.querySelector('#content'))
            .catch(error => {
                console.error(error);
            });

        // Khởi tạo CKEditor cho mỗi form chỉnh sửa bài viết
        document.querySelectorAll('.editor-edit').forEach(aa => {
            ClassicEditor
                .create(aa)
        });

        // Tự động chuyển tiêu đề thành slug
        const titleInput = document.querySelector('#title');
        const slugInput = document.querySelector('#slug');

        titleInput.addEventListener('input', function () {
            slugInput.value = convertToSlug(titleInput.value);
        });

        function convertToSlug(text) {
            return text
                .toLowerCase()
                .normalize('NFD') // loại bỏ dấu
                .replace(/[\u0300-\u036f]/g, '') // loại bỏ dấu tiếng Việt
                .replace(/ +/g, '-'); // thay dấu cách bằng dấu gạch ngang
        }
    });
</script>
@stop