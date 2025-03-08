<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Styles -->
    <!-- @vite(['resources/css/style.css']) -->
</head>

<body>




    <nav>

        <!-- test session thông báo alert cho logout "LoginController"-->
        @if (session('successLogout'))
            <div class="alert alert-success" role="alert">
                <span>{{ session('successLogout') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- nếu đăng ký thành công -->
        @if (session('successRegister'))
            <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                {{ session('successRegister') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('successLogin'))
            <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                {{ session('successLogin') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        @if (session('messageLoggedin'))
            <div class="alert alert-info">
                {{ session('messageLoggedin') }}
            </div>
        @endif




    </nav>

    <div class="container">
        <h2 class="text-center text-warning">Nguyễn Thanh Thịnh</h2>

        <div class="text-center mt-4">
            <a href="{{ url('/send-mail') }}" class="btn btn-primary">Send Test Mail</a>
        </div>

        <!-- <div class="text-center mt-4">
            <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
        </div> -->

        <!-- Nếu đã đăng nhập thì hiển thị nút dashboard, ngược lại hiển thị nút đăng nhập -->
        <div class="text-center mt-4">
            @if (Auth::check())
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
            @else
                <a href="{{ url('/login') }}" class="btn btn-primary">Đăng nhập</a>
            @endif
        </div>

        <!-- Nút đăng nhập và đăng ký -->
        <!-- <div class="text-center mt-4">
            <a href="{{ url('/login') }}" class="btn btn-primary">Đăng nhập</a>
        </div> -->

        <!-- client -->

        <div class="text-center mt-4">
            {{-- <a href="{{ url('/client/news') }}" class="btn btn-primary">Client</a> --}}
            <a href="{{ route('client.index') }}" class="btn btn-primary">Client</a>
        </div>


    </div>



</body>

</html>