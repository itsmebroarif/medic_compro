<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Admin | {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="128x128" href="{{ asset('generated/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('generated/vendor/overlayscrollbars.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('generated/vendor/bootstrap-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('template_admin/css/adminlte.css') }}" />
</head>
<body class="login-page bg-body-secondary">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ route('home') }}"><img src="{{ asset('generated/logo-dark.png') }}" alt="{{ config('app.name') }}" style="height: 64px; width: auto;"></a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Silakan masuk ke panel admin</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2" role="alert">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.store') }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus autocomplete="email" />
                    <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required autocomplete="current-password" />
                    <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                    </div>
                </div>
            </form>

            <hr>
            <p class="mb-1 text-center"><a href="{{ route('home') }}">&larr; Kembali ke situs</a></p>
            <p class="mb-0 text-center text-secondary small">Default: admin@medicservice.co.id / password</p>
        </div>
    </div>
</div>
</body>
</html>
