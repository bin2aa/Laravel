<!DOCTYPE html>
<html>

<head>
    <title>Cập nhật trạng thái bài viết</title>
</head>

<body>
    <h1>Thông báo trạng thái bài viết</h1>
    <p>
        @if($isApproved)
            Bài viết có tiêu đề: <strong>{{ $post->title }}</strong> của bạn đã được <span style="color: green;">phê
                duyệt</span>.
        @else
            Bài viết có tiêu đề: <strong>{{ $post->title }}</strong> của bạn đã bị <span style="color: red;">từ chối</span>.
        @endif
    </p>
    <p>
        Bạn có thể xem bài viết tại:
        <a href="{{ route('client.post.detail', ['slug' => $post->slug, 'id' => $post->id]) }}">{{ $post->title }}</a>
    </p>

</body>

</html>