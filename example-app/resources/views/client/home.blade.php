@extends('client.index')

@section('main-content')
<div class="container mt-4">
    <div class="row">
        @foreach($posts as $post)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text">{{ $post->description }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection