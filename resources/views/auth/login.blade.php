<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — SIMAS SMKN 11 Kota Tangerang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0a1f3d 0%, #0f3460 50%, #1a5276 100%);
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Dekorasi background */
        body::before {
            content: '';
            position: fixed;
            width: 600px; height: 600px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            top: -200px; right: -150px;
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            width: 400px; height: 400px;
            background: rgba(232,160,32,0.06);
            border-radius: 50%;
            bottom: -100px; left: -100px;
            pointer-events: none;
        }

        /* Card login utama */
        .login-card {
            background: #fff;
            border-radius: 24px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.4);
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        /* Header card */
        .login-header {
            background: linear-gradient(135deg, #0a3352, #0f4c75);
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .login-header::before {
            content: '';
            position: absolute;
            right: -30px; top: -30px;
            width: 120px; height: 120px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .login-header::after {
            content: '';
            position: absolute;
            left: -20px; bottom: -20px;
            width: 90px; height: 90px;
            background: rgba(232,160,32,0.1);
            border-radius: 50%;
        }

        /* Logo */
        .logo-wrap {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, #e8a020, #f59e0b);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 0.9rem;
            font-size: 2rem;
            box-shadow: 0 8px 30px rgba(232,160,32,0.5);
            position: relative; z-index: 1;
        }
        .login-header h1 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 0.2rem;
            position: relative; z-index: 1;
        }
        .login-header .sub {
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
            font-weight: 500;
            position: relative; z-index: 1;
        }

        /* Body form */
        .login-body { padding: 2rem; }

        /* Role pills */
        .role-pills {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            justify-content: center;
        }
        .role-pill {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 0.7rem;
            font-weight: 600;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .role-pill.admin  { background: #fee2e2; border-color: #fca5a5; color: #991b1b; }
        .role-pill.kepsek { background: #fef3c7; border-color: #fde68a; color: #92400e; }
        .role-pill.staff  { background: #f1f5f9; border-color: #cbd5e1; color: #475569; }

        /* Label */
        .form-label-up {
            font-size: 0.72rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.4rem;
        }

        /* Input */
        .input-wrap { position: relative; }
        .input-wrap .icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8; font-size: 1rem;
            pointer-events: none;
        }
        .form-input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.75rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.875rem;
            font-family: inherit;
            background: #f8fafc;
            color: #1e293b;
            transition: all 0.2s;
            outline: none;
        }
        .form-input:focus {
            border-color: #0f4c75;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(15,76,117,0.1);
        }
        .toggle-pw {
            position: absolute;
            right: 13px; top: 50%;
            transform: translateY(-50%);
            cursor: pointer; color: #94a3b8;
            background: none; border: none;
            font-size: 1rem; padding: 0;
        }

        /* Tombol login */
        .btn-masuk {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #0f4c75, #1a5276);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-masuk:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(15,76,117,0.4);
        }
        .btn-masuk:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .btn-masuk .spinner {
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }
        .btn-masuk.loading .teks { display: none; }
        .btn-masuk.loading .spinner { display: block; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Error */
        .error-box {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            border-radius: 10px;
            padding: 0.65rem 0.9rem;
            font-size: 0.8rem;
            color: #991b1b;
            display: none;
            margin-bottom: 1rem;
        }

        /* Akun demo */
        .demo-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.875rem;
            margin-top: 1.25rem;
        }
        .demo-title {
            font-size: 0.68rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 0.6rem;
        }
        .demo-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            margin-bottom: 0.35rem;
        }
        .demo-row:last-child { margin-bottom: 0; }
        .demo-role { font-size: 0.72rem; font-weight: 700; color: #1e293b; min-width: 80px; }
        .demo-cred { font-size: 0.7rem; color: #64748b; font-family: monospace; flex: 1; text-align: center; }
        .demo-btn  {
            font-size: 0.65rem; padding: 2px 10px;
            border: 1px solid #0369a1; background: transparent;
            color: #0369a1; border-radius: 6px;
            cursor: pointer; font-weight: 700;
            transition: all 0.15s;
        }
        .demo-btn:hover { background: #0369a1; color: #fff; }

        /* Footer */
        .login-footer {
            text-align: center;
            padding: 1rem 2rem;
            border-top: 1px solid #f1f5f9;
            font-size: 0.7rem;
            color: #94a3b8;
        }

        /* ===== LOADING OVERLAY ===== */
        #loading-overlay {
            position: fixed; inset: 0; z-index: 9999;
            background: linear-gradient(135deg, #0a1f3d 0%, #0f3460 60%, #1a5276 100%);
            display: none; flex-direction: column;
            align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.3s;
        }
        #loading-overlay.show  { display: flex; }
        #loading-overlay.vis   { opacity: 1; }

        .load-logo {
            text-align: center;
            margin-bottom: 2.5rem;
            animation: fadeUp 0.6s ease both;
        }
        .load-icon {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, #e8a020, #f59e0b);
            border-radius: 22px; margin: 0 auto 1rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 36px;
            animation: pulse 1.5s ease infinite;
        }
        @keyframes pulse {
            0%,100% { transform: scale(1); box-shadow: 0 16px 45px rgba(232,160,32,.45); }
            50%      { transform: scale(1.06); box-shadow: 0 22px 55px rgba(232,160,32,.65); }
        }
        .load-logo h2 { color: #fff; font-size: 1.8rem; font-weight: 800; margin-bottom: .25rem; }
        .load-logo p  { color: rgba(255,255,255,.45); font-size: .75rem; }

        .load-user {
            color: rgba(255,255,255,.75); font-size: .88rem;
            margin-bottom: 2rem; text-align: center;
            animation: fadeUp 0.6s ease 0.2s both;
        }
        .load-user strong { color: #f59e0b; }

        .progress-wrap { width: 250px; animation: fadeUp 0.6s ease 0.3s both; }
        .progress-track { background: rgba(255,255,255,.1); border-radius: 10px; height: 6px; overflow: hidden; margin-bottom: .7rem; }
        .progress-fill  { height: 100%; width: 0%; background: linear-gradient(90deg, #e8a020, #f59e0b); border-radius: 10px; transition: width .35s ease; }
        .progress-label { color: rgba(255,255,255,.4); font-size: .68rem; text-align: center; }

        .load-steps { margin-top: 1.5rem; display: flex; flex-direction: column; gap: .45rem; animation: fadeUp 0.6s ease 0.4s both; }
        .load-step  { display: flex; align-items: center; gap: .55rem; color: rgba(255,255,255,.3); font-size: .76rem; transition: color .3s; }
        .load-step.done { color: rgba(255,255,255,.9); }
        .load-step.done .si { color: #10b981; }
        .si { font-size: .85rem; width: 18px; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:translateY(0); } }
    </style>
</head>
<body>

<!-- Loading Overlay -->
<div id="loading-overlay">
    <div class="load-logo">
        <div class="load-icon">🏫</div>
        <h2>SIMAS</h2>
        <p>SMKN 11 Kota Tangerang</p>
    </div>
    <div class="load-user">
        Selamat datang, <strong id="load-nama">--</strong>!<br>
        <small style="opacity:.6" id="load-role">--</small>
    </div>
    <div class="progress-wrap">
        <div class="progress-track">
            <div class="progress-fill" id="prog-fill"></div>
        </div>
        <div class="progress-label" id="prog-label">Memuat sistem...</div>
    </div>
    <div class="load-steps">
        <div class="load-step" id="s1"><span class="si">⏳</span> Memverifikasi akun...</div>
        <div class="load-step" id="s2"><span class="si">⏳</span> Memuat data aset SMKN 11...</div>
        <div class="load-step" id="s3"><span class="si">⏳</span> Menyiapkan dashboard...</div>
        <div class="load-step" id="s4"><span class="si">⏳</span> Sistem siap digunakan!</div>
    </div>
</div>

<!-- Card Login Tengah -->
<div class="login-card">

    <!-- Header -->
    <div class="login-header">
        
        <h1>SIMAS</h1>
        
        <div class="logo-wrap">🏫</div>
        <div class="sub">Sistem Informasi Manajemen Aset</div>
    </div>

    <!-- Body Form -->
    <div class="login-body">

        @if(session('sukses'))
        <div class="error-box" style="display:block;background:#d1fae5;border-color:#6ee7b7;color:#065f46">
            <i class="bi bi-check-circle me-1"></i>{{ session('sukses') }}
        </div>
        @endif

        <!-- Error box -->
        <div class="error-box" id="error-box">
            <i class="bi bi-exclamation-circle me-1"></i>
            <span id="error-msg"></span>
        </div>

        <!-- Username -->
        <div class="mb-3">
            <div class="form-label-up">Username</div>
            <div class="input-wrap">
                <i class="bi bi-person icon"></i>
                <input type="text" id="username" class="form-input"
                    placeholder="Masukkan username" autofocus autocomplete="username">
            </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="form-label-up">Password</div>
            <div class="input-wrap">
                <i class="bi bi-lock icon"></i>
                <input type="password" id="password" class="form-input"
                    placeholder="Masukkan password" autocomplete="current-password">
                <button type="button" class="toggle-pw" onclick="togglePw()">
                    <i class="bi bi-eye" id="pw-icon"></i>
                </button>
            </div>
        </div>

        <!-- Ingat saya -->
        <div class="d-flex align-items-center mb-4">
            <label style="font-size:.8rem;color:#64748b;cursor:pointer;display:flex;align-items:center;gap:6px">
                <input type="checkbox" id="ingat" style="accent-color:#0f4c75"> Ingat saya
            </label>
        </div>

        <!-- Tombol Masuk -->
        <button class="btn-masuk" id="btn-login" onclick="doLogin()">
            <div class="spinner" id="spinner"></div>
            <span class="teks"><i class="bi bi-box-arrow-in-right me-1"></i>Masuk ke Sistem</span>
        </button>

    <!-- Footer -->
    <div class="login-footer">
        SMKN 11 Kota Tangerang &copy; {{ date('Y') }} 
    </div>
</div>

<script>
function togglePw() {
    const p = document.getElementById('password');
    const i = document.getElementById('pw-icon');
    if (p.type === 'password') { p.type = 'text'; i.className = 'bi bi-eye-slash'; }
    else { p.type = 'password'; i.className = 'bi bi-eye'; }
}

function isi(u, p) {
    document.getElementById('username').value = u;
    document.getElementById('password').value = p;
}

function showErr(msg) {
    const b = document.getElementById('error-box');
    document.getElementById('error-msg').textContent = msg;
    b.style.display = 'block';
}

document.addEventListener('keydown', e => { if (e.key === 'Enter') doLogin(); });

function doLogin() {
    const u   = document.getElementById('username').value.trim();
    const p   = document.getElementById('password').value;
    const ing = document.getElementById('ingat').checked;
    const btn = document.getElementById('btn-login');

    if (!u || !p) { showErr('Username dan password wajib diisi.'); return; }

    btn.classList.add('loading');
    btn.disabled = true;
    document.getElementById('error-box').style.display = 'none';

    fetch('{{ route("login.post") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ username: u, password: p, ingat: ing })
    })
    .then(r => r.json())
    .then(d => {
        if (d.sukses) {
            tampilLoading(d.nama, d.role, d.redirect);
        } else {
            btn.classList.remove('loading');
            btn.disabled = false;
            showErr(d.pesan);
            document.getElementById('password').value = '';
        }
    })
    .catch(() => {
        btn.classList.remove('loading');
        btn.disabled = false;
        showErr('Terjadi kesalahan. Coba refresh halaman.');
    });
}

function tampilLoading(nama, role, redirect) {
    const ov = document.getElementById('loading-overlay');
    document.getElementById('load-nama').textContent = nama;
    document.getElementById('load-role').textContent = role;
    ov.classList.add('show');
    setTimeout(() => ov.classList.add('vis'), 10);

    const steps = [
        { id:'s1', delay:300,  prog:25,  label:'Memuat data aset...' },
        { id:'s2', delay:750,  prog:55,  label:'Memuat departemen & karyawan...' },
        { id:'s3', delay:1300, prog:82,  label:'Menyiapkan dashboard...' },
        { id:'s4', delay:1900, prog:100, label:'Siap! Mengalihkan...' },
    ];

    steps.forEach(s => {
        setTimeout(() => {
            const el = document.getElementById(s.id);
            el.classList.add('done');
            el.querySelector('.si').textContent = '✅';
            document.getElementById('prog-fill').style.width  = s.prog + '%';
            document.getElementById('prog-label').textContent = s.label;
        }, s.delay);
    });

    setTimeout(() => window.location.href = redirect, 2400);
}
</script>
</body>
</html>
