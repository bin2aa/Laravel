@extends('client.index')

@section('main-content')
    <div class="container mt-4">

        <!-- Thêm form tìm kiếm -->
        <div class="row mb-4">
            <div class="col-md-8 mx-auto">
                <form id="search-form" action="{{ route('client.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" id="search-input" class="form-control"
                        placeholder="Tìm kiếm bài viết..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary ms-2">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </form>
            </div>
        </div>

        {{-- thêm danh sách các danh mục --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Danh mục</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('client.index') }}" method="GET" id="category-form">
                            <!-- Giữ lại giá trị tìm kiếm nếu có -->
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <select name="category" class="form-select"
                                onchange="document.getElementById('category-form').submit()">
                                <option value="" {{ !request('category') ? 'selected' : '' }}>Tất cả</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->posts_count }})
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <div class="row" id="posts-container">
            @foreach($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">

                            @if($post->hasMedia('thumbnails'))
                                <img src="{{ $post->getFirstMediaUrl('thumbnails') }}" class="card-img-top" alt="{{ $post->title }}"
                                    style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light text-center py-5">
                                    <i class="fas fa-image fa-3x text-secondary"></i>
                                </div>
                            @endif


                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ $post->description }}</p>


                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt"></i> Ngày đăng:
                                    {{ date('d/m/Y', strtotime($post->publish_date)) }}
                                </small>
                            </p>

                            <div class="mt-3">
                                <a href="{{ route('client.post.detail', ['id' => $post->id, 'slug' => $post->slug]) }}"
                                    class="btn btn-primary btn-sm">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4" id="pagination-container">
            {{ $posts->links() }}
        </div>

        {{-- <div class="d-flex justify-content-center mt-4" id="pagination-container">
            {{ $posts->appends(request()->query())->links() }}
        </div> --}}

    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@push('scripts')
@endpush