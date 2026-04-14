<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan | SIMAS Aset SMKN 11</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f4f8; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .box { background: #fff; border-radius: 20px; padding: 3rem 2.5rem; text-align: center; box-shadow: 0 4px 24px rgba(0,0,0,.08); max-width: 440px; width: 100%; }
        .num { font-size: 5rem; font-weight: 800; color: #dbeafe; line-height: 1; }
        .icon { font-size: 3.5rem; margin-bottom: .5rem; }
        h1 { font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: .5rem; }
        p  { color: #64748b; font-size: .88rem; margin-bottom: 1.5rem; }
        .btn-back { background: #0f4c75; color: #fff; border: none; padding: .75rem 1.5rem; border-radius: 12px; font-size: .88rem; font-weight: 700; font-family: inherit; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-back:hover { background: #0a3352; color: #fff; }
        .footer { margin-top: 1.5rem; font-size: .72rem; color: #94a3b8; }
    </style>
</head>
<body>
<div class="box">
    <div class="num">404</div>
    <div class="icon">🔍</div>
    <h1>Halaman Tidak Ditemukan</h1>
    <p>Halaman yang kamu cari tidak ada atau sudah dipindahkan.<br>Coba kembali ke dashboard.</p>
    <div class="d-flex gap-2 justify-content-center flex-wrap">
        <a href="{{ url()->previous() }}" class="btn-back">← Kembali</a>
        <a href="{{ route('dashboard') }}" class="btn-back" style="background: #10b981">🏠 Dashboard</a>
    </div>
    <div class="footer">🏫 SIMAS Aset — SMKN 11 Kota Tangerang &copy; {{ date('Y') }}</div>
</div>
</body>
</html>
