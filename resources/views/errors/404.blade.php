<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>404 — Halaman Tidak Ditemukan | RS Medika Service</title>
    <meta name="robots" content="noindex">
    <link rel="icon" type="image/png" href="{{ asset('generated/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Manrope', Arial, sans-serif; background: #f4f9fc; color: #1e293b; min-height: 100vh; display: flex; flex-direction: column; }
        header { background: #0b2b3a; padding: 16px 0; }
        header .inner { max-width: 1560px; margin: 0 auto; padding: 0 24px; }
        header img { height: 44px; }
        .hero { flex: 1; display: flex; align-items: center; justify-content: center; text-align: center; padding: 60px 20px; background-image: linear-gradient(rgba(244,249,252,.86), rgba(244,249,252,.86)), url('{{ asset('generated/hero-hospital.jpg') }}'); background-size: cover; background-position: center 30%; }
        .code { font-size: 6rem; font-weight: 800; color: #0d9488; line-height: 1; }
        h1 { font-size: 1.6rem; margin: 10px 0 14px; }
        p { color: #475569; max-width: 480px; margin: 0 auto 26px; }
        .btn { display: inline-flex; align-items: center; gap: 10px; background: #0d6efd; color: #fff; text-decoration: none; font-weight: 700; padding: 14px 28px; border-radius: 100px; }
        .btn:hover { background: #0b5ed7; }
    </style>
</head>
<body>
    <header><div class="inner"><a href="{{ url('/') }}"><img src="{{ asset('generated/logo-light.png') }}" alt="RS Medika Service"></a></div></header>
    <main class="hero">
        <div>
            <div class="code">404</div>
            <h1>Halaman Tidak Ditemukan</h1>
            <p>Maaf, halaman yang Anda cari tidak tersedia atau sudah dipindahkan. Mari kembali ke beranda dan temukan layanan kami.</p>
            <a class="btn" href="{{ url('/') }}">&larr; Kembali ke Beranda</a>
        </div>
    </main>
</body>
</html>
