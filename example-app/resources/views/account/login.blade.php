<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Form Đăng nhập</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required value="{{ old('email') }}">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">Đăng nhập</button>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('register') }}" class="text-decoration-none">Đăng ký</a>
                    </div>
                </form>
                <!-- Thêm thông báo riêng cho account status has-->
                <!-- Sau đó hiển thị thông báo first-->
                @if ($errors->has('account_status0'))
                <div class="alert alert-info mt-3">
                    {{ $errors->first('account_status0') }}
                </div>
                @endif
                @if ($errors->has('account_status1'))
                <div class="alert alert-warning  mt-3">
                    {{ $errors->first('account_status1') }}
                </div>
                @endif
                @if ($errors->has('account_status2'))
                <div class="alert alert-danger mt-3">
                    {{ $errors->first('account_status2') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>