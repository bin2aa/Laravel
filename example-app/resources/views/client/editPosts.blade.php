@extends('client.index')

@section('main-content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Chỉnh sửa bài viết</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('client.updatePosts', $post->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}" required>
                            @error('title')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ $post->slug }}" required>
                            @error('slug')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required>{{ $post->description }}</textarea>
                            @error('description')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung</label>
                            <textarea class="editor form-control" id="content" name="content" rows="10">{{ $post->content }}</textarea>
                            @error('content')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            @if($post->getFirstMediaUrl('thumbnails'))
                            <div class="mb-2">
                                <img src="{{ $post->getFirstMediaUrl('thumbnails') }}" alt="Current Thumbnail" style="max-width: 200px; max-height: 150px;" class="img-thumbnail">
                            </div>
                            @endif
                            <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                            @error('thumbnail')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" id="publish_date" name="publish_date" value="{{ $post->getRawOriginal('publish_date') }}">

                        <input type="hidden" id="status" name="status" value="{{ $post->getRawOriginal('status') }}">

                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Khởi tạo CKEditor cho trang edit
        if (document.querySelector('#content')) {
            ClassicEditor
                .create(document.querySelector('#content'))
        }
        // Tự động chuyển tiêu đề thành slug trên trang edit
        const titleInput = document.querySelector('#title');
        const slugInput = document.querySelector('#slug');

        if (titleInput && slugInput) {
            titleInput.addEventListener('input', function() {
                slugInput.value = convertToSlug(titleInput.value);
            });
        }
    });
</script>
@endpush