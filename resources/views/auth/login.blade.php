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
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Plus Jakarta Sans',sans-serif;min-height:100vh;display:flex;background:#0a1628;overflow:hidden}

        /* Panel Kiri */
        .panel-kiri{width:55%;position:relative;background:linear-gradient(135deg,#0a1628 0%,#0f3460 50%,#1a5276 100%);display:flex;flex-direction:column;justify-content:center;align-items:center;padding:3rem;overflow:hidden}
        .panel-kiri::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E")}
        .c{position:absolute;border-radius:50%}
        .c1{width:480px;height:480px;background:rgba(26,82,118,.25);top:-130px;right:-90px}
        .c2{width:280px;height:280px;background:rgba(41,128,185,.12);bottom:-60px;left:-50px}

        .logo-area{text-align:center;position:relative;z-index:2;margin-bottom:2.2rem}
        .logo-icon{width:86px;height:86px;background:linear-gradient(135deg,#e8a020,#f59e0b);border-radius:22px;display:flex;align-items:center;justify-content:center;font-size:38px;margin:0 auto 1rem;box-shadow:0 18px 55px rgba(232,160,32,.45)}
        .logo-area h1{font-size:2rem;font-weight:800;color:#fff;line-height:1.1}
        .logo-area .sub{font-size:.82rem;color:rgba(255,255,255,.55);margin-top:.35rem;font-weight:500}

        /* Tabel fitur di kiri */
        .fitur-grid{position:relative;z-index:2;display:grid;grid-template-columns:1fr 1fr;gap:.7rem;width:100%;max-width:440px;margin-bottom:1.5rem}
        .fitur-item{background:rgba(255,255,255,.07);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:.8rem .9rem;display:flex;align-items:center;gap:.65rem}
        .fitur-item .ic{font-size:1.2rem;flex-shrink:0}
        .fitur-item .title{font-size:.72rem;font-weight:700;color:#fff;line-height:1.2}
        .fitur-item .desc{font-size:.62rem;color:rgba(255,255,255,.45);margin-top:1px}

        /* Role tabel */
        .role-table{position:relative;z-index:2;width:100%;max-width:440px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:12px;overflow:hidden}
        .role-row{display:flex;align-items:center;gap:.75rem;padding:.6rem 1rem;border-bottom:1px solid rgba(255,255,255,.06)}
        .role-row:last-child{border-bottom:none}
        .role-icon{font-size:1.1rem;width:22px;text-align:center;flex-shrink:0}
        .role-name{font-weight:700;font-size:.75rem;color:#fff;min-width:90px}
        .role-desc{font-size:.65rem;color:rgba(255,255,255,.45)}

        .footer-kiri{position:relative;z-index:2;margin-top:1.2rem;color:rgba(255,255,255,.3);font-size:.68rem;text-align:center}

        /* Panel Kanan */
        .panel-kanan{width:45%;background:#fff;display:flex;flex-direction:column;justify-content:center;padding:2.5rem;border-radius:2rem 0 0 2rem;position:relative}
        .panel-kanan::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#e8a020,#f59e0b,#0f4c75);border-radius:2rem 0 0 0}

        .form-area{max-width:370px;width:100%;margin:0 auto}
        .form-area h2{font-size:1.6rem;font-weight:800;color:#0a1628;margin-bottom:.25rem}
        .form-area .sub{font-size:.8rem;color:#94a3b8;margin-bottom:1.8rem}

        .fl{font-size:.7rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.35rem}
        .iw{position:relative}
        .iw i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.95rem}
        .ic{width:100%;padding:.8rem 1rem .8rem 2.7rem;border:1.5px solid #e2e8f0;border-radius:12px;font-size:.85rem;font-family:inherit;background:#f8fafc;color:#1e293b;transition:all .2s;outline:none}
        .ic:focus{border-color:#0f4c75;background:#fff;box-shadow:0 0 0 4px rgba(15,76,117,.1)}
        .tpw{position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#94a3b8;background:none;border:none;padding:0;font-size:.95rem}

        .btn-login{width:100%;padding:.85rem;background:linear-gradient(135deg,#0f4c75,#1a5276);color:#fff;border:none;border-radius:12px;font-size:.9rem;font-weight:700;font-family:inherit;cursor:pointer;transition:all .2s;position:relative;overflow:hidden}
        .btn-login:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(15,76,117,.4)}
        .btn-login:disabled{opacity:.7;cursor:not-allowed;transform:none}
        .btn-login .sp{display:none;width:16px;height:16px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite}
        .btn-login.loading .bt{display:none}
        .btn-login.loading .sp{display:inline-block}
        @keyframes spin{to{transform:rotate(360deg)}}

        .err-box{background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;border-radius:10px;padding:.65rem .9rem;font-size:.8rem;margin-bottom:.9rem;display:none}

        /* Demo akun */
        .demo{margin-top:1.3rem;background:#f0f9ff;border:1px solid #bae6fd;border-radius:12px;padding:.85rem}
        .demo-title{font-size:.65rem;font-weight:700;color:#0369a1;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem}
        .demo-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:.3rem;gap:.5rem}
        .demo-row .rn{font-size:.72rem;font-weight:600;color:#0f172a;white-space:nowrap}
        .demo-row .rc{font-size:.68rem;color:#64748b;font-family:monospace;flex:1;text-align:center}
        .demo-row .ra{font-size:.65rem;padding:2px 7px;border:1px solid #0369a1;background:transparent;color:#0369a1;border-radius:6px;cursor:pointer;font-weight:600;white-space:nowrap}
        .demo-row .ra:hover{background:#0369a1;color:#fff}

        .ft{text-align:center;margin-top:1.3rem;font-size:.7rem;color:#94a3b8}

        /* Loading overlay */
        #lo{position:fixed;inset:0;z-index:9999;background:linear-gradient(135deg,#0a1628 0%,#0f3460 60%,#1a5276 100%);display:none;flex-direction:column;align-items:center;justify-content:center;opacity:0;transition:opacity .3s}
        #lo.show{display:flex}
        #lo.vis{opacity:1}
        .ll{text-align:center;margin-bottom:2.5rem;animation:fup .6s ease both}
        .ll .li{width:75px;height:75px;background:linear-gradient(135deg,#e8a020,#f59e0b);border-radius:20px;margin:0 auto 1rem;display:flex;align-items:center;justify-content:center;font-size:32px;animation:pulse 1.5s ease infinite}
        @keyframes pulse{0%,100%{transform:scale(1);box-shadow:0 16px 45px rgba(232,160,32,.45)}50%{transform:scale(1.06);box-shadow:0 22px 55px rgba(232,160,32,.65)}}
        .ll h2{color:#fff;font-size:1.7rem;font-weight:800;margin-bottom:.25rem}
        .ll p{color:rgba(255,255,255,.45);font-size:.75rem}
        .lu{color:rgba(255,255,255,.75);font-size:.88rem;margin-bottom:1.8rem;text-align:center;animation:fup .6s ease .2s both}
        .lu strong{color:#f59e0b}
        .pb-w{width:250px;animation:fup .6s ease .3s both}
        .pb-t{background:rgba(255,255,255,.1);border-radius:10px;height:6px;overflow:hidden;margin-bottom:.7rem}
        .pb-f{height:100%;width:0%;background:linear-gradient(90deg,#e8a020,#f59e0b);border-radius:10px;transition:width .3s ease;box-shadow:0 0 10px rgba(232,160,32,.6)}
        .pb-label{color:rgba(255,255,255,.4);font-size:.68rem;text-align:center}
        .steps{margin-top:1.3rem;display:flex;flex-direction:column;gap:.45rem;animation:fup .6s ease .4s both}
        .step{display:flex;align-items:center;gap:.55rem;color:rgba(255,255,255,.3);font-size:.75rem;transition:color .3s}
        .step.done{color:rgba(255,255,255,.9)}
        .step.done .si{color:#10b981}
        .si{font-size:.85rem;width:18px}
        @keyframes fup{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}

        /* Responsive */
        @media(max-width:880px){.panel-kiri{display:none}.panel-kanan{width:100%;border-radius:0}}
        @media(max-width:450px){.panel-kanan{padding:1.8rem 1.3rem}}
    </style>
</head>
<body>

<!-- Loading Overlay -->
<div id="lo">
    <div class="ll">
        <div class="li">🏫</div>
        <h2>SIMAS</h2>
        <p>SMKN 11 Kota Tangerang</p>
    </div>
    <div class="lu">
        Selamat datang, <strong id="ln">--</strong>!<br>
        <small style="opacity:.6" id="lr">--</small>
    </div>
    <div class="pb-w">
        <div class="pb-t"><div class="pb-f" id="pbf"></div></div>
        <div class="pb-label" id="pbl">Memuat sistem...</div>
    </div>
    <div class="steps">
        <div class="step" id="s1"><span class="si">⏳</span> Memverifikasi akun...</div>
        <div class="step" id="s2"><span class="si">⏳</span> Memuat data aset SMKN 11...</div>
        <div class="step" id="s3"><span class="si">⏳</span> Menyiapkan dashboard...</div>
        <div class="step" id="s4"><span class="si">⏳</span> Sistem siap!</div>
    </div>
</div>

<!-- Panel Kiri -->
<div class="panel-kiri">
    <div class="c c1"></div><div class="c c2"></div>

    <div class="logo-area">
        <div class="logo-icon">
    <img src="{{ asset('image/logo.jpg') }}" alt="Logo SMKN 11" style="width: 100%; height: 100%; object-fit: cover; border-radius: 22px;">
</div>
        <h1>SIMAS</h1>
        <div class="sub">Sistem Informasi Manajemen Aset Sekolah<br>SMKN 11 Kota Tangerang</div>
    </div>

    <div class="footer-kiri">&copy; {{ date('Y') }} SMKN 11 Kota Tangerang &mdash; Jl. Nusantara Raya, Tangerang</div>
</div>

<!-- Panel Kanan -->
<div class="panel-kanan">
    <div class="form-area">
        <h2>Selamat Datang 👋</h2>
        <p class="sub">Masuk ke SIMAS SMKN 11 Kota Tangerang</p>

        @if(session('sukses'))
        <div class="err-box" style="display:block;background:#d1fae5;border-color:#6ee7b7;color:#065f46">
            <i class="bi bi-check-circle me-1"></i>{{ session('sukses') }}
        </div>
        @endif
        <div class="err-box" id="ebox"><i class="bi bi-exclamation-circle me-1"></i><span id="emsg"></span></div>

        <div class="mb-3">
            <div class="fl">Username</div>
            <div class="iw">
                <i class="bi bi-person"></i>
                <input type="text" id="username" class="ic" placeholder="Masukkan username" autofocus autocomplete="username">
            </div>
        </div>
        <div class="mb-3">
            <div class="fl">Password</div>
            <div class="iw">
                <i class="bi bi-lock"></i>
                <input type="password" id="password" class="ic" placeholder="Masukkan password" autocomplete="current-password">
                <button type="button" class="tpw" onclick="tpw()"><i class="bi bi-eye" id="pwi"></i></button>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <label style="font-size:.78rem;color:#64748b;cursor:pointer;display:flex;align-items:center;gap:6px">
                <input type="checkbox" id="ingat" style="accent-color:#0f4c75"> Ingat saya
            </label>
        </div>

        <button class="btn-login" id="btn" onclick="doLogin()">
            <div class="d-flex align-items-center justify-content-center gap-2">
                <div class="sp" id="sp"></div>
                <span class="bt">Masuk</span>
            </div>
        </button>

        <div class="ft">SIMAS &mdash; SMKN 11 Kota Tangerang &copy; {{ date('Y') }}</div>
    </div>
</div>

<script>
function tpw(){const p=document.getElementById('password'),i=document.getElementById('pwi');p.type=p.type==='password'?'text':'password';i.className=p.type==='password'?'bi bi-eye':'bi bi-eye-slash'}
function fill(u,p){document.getElementById('username').value=u;document.getElementById('password').value=p}
document.addEventListener('keydown',e=>{if(e.key==='Enter')doLogin()})
function showErr(m){const b=document.getElementById('ebox');document.getElementById('emsg').textContent=m;b.style.display='block'}

function doLogin(){
    const u=document.getElementById('username').value.trim();
    const p=document.getElementById('password').value;
    const i=document.getElementById('ingat').checked;
    const btn=document.getElementById('btn');
    if(!u||!p){showErr('Username dan password wajib diisi.');return}
    btn.classList.add('loading');btn.disabled=true;
    document.getElementById('ebox').style.display='none';
    fetch('{{ route("login.post") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({username:u,password:p,ingat:i})})
    .then(r=>r.json())
    .then(d=>{
        if(d.sukses){showLoading(d.nama,d.role,d.redirect)}
        else{btn.classList.remove('loading');btn.disabled=false;showErr(d.pesan);document.getElementById('password').value=''}
    })
    .catch(()=>{btn.classList.remove('loading');btn.disabled=false;showErr('Terjadi kesalahan. Coba refresh halaman.')})
}

function showLoading(nama,role,redirect){
    const lo=document.getElementById('lo');
    document.getElementById('ln').textContent=nama;
    document.getElementById('lr').textContent=role;
    lo.classList.add('show');setTimeout(()=>lo.classList.add('vis'),10);
    const steps=[
        {id:'s1',delay:300,prog:25,text:'Memuat data aset...'},
        {id:'s2',delay:750,prog:55,text:'Memuat departemen & karyawan...'},
        {id:'s3',delay:1300,prog:82,text:'Menyiapkan dashboard...'},
        {id:'s4',delay:1900,prog:100,text:'Siap! Mengalihkan halaman...'},
    ];
    steps.forEach(s=>{
        setTimeout(()=>{
            const el=document.getElementById(s.id);
            el.classList.add('done');el.querySelector('.si').textContent='✅';
            document.getElementById('pbf').style.width=s.prog+'%';
            document.getElementById('pbl').textContent=s.text;
        },s.delay)
    });
    setTimeout(()=>window.location.href=redirect,2400);
}
</script>
</body>
</html>
