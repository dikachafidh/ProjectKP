<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Dashboard') — SIMAS Aset SMKN 11 Tangerang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 255px;
            --primary:   #0f4c75;
            --primary-dk:#0a3352;
            --primary-lt:#1b6ca8;
            --accent:    #e8a020;
            --accent-lt: #fbbf24;
            --bg:        #f0f4f8;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans','Segoe UI',sans-serif; background: var(--bg); margin: 0; font-size: .875rem; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: linear-gradient(170deg, var(--primary-dk) 0%, var(--primary) 60%, var(--primary-lt) 100%);
            display: flex; flex-direction: column; z-index: 1100;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            overflow: hidden;
        }
        .sidebar::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.025'%3E%3Cpath d='M20 20.5V18H0v5h5v5H0v5h20v-2.5h-5v-5h5zm-5-10V5H0v5h5V5h10v5.5H0v5h15V10.5zm0-10h5V0h-5v.5zM20 0H0v5h20V0z'/%3E%3C/g%3E%3C/svg%3E");
        }
        .sb-brand {
            padding: 1rem 1.1rem; border-bottom: 1px solid rgba(255,255,255,.08);
            position: relative; z-index: 1; display: flex; align-items: center; gap: .7rem;
        }
        .sb-logo {
            width: 38px; height: 38px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--accent), var(--accent-lt));
            border-radius: 10px; display: flex; align-items: center;
            justify-content: center; font-size: 1.1rem;
            box-shadow: 0 4px 14px rgba(232,160,32,.4);
        }
        .sb-brand-name  { color: #fff; font-weight: 800; font-size: .88rem; line-height: 1.1; }
        .sb-brand-school{ color: rgba(255,255,255,.5); font-size: .6rem; font-weight: 600; }

        .sb-scroll { flex: 1; overflow-y: auto; padding: .5rem 0; }
        .sb-scroll::-webkit-scrollbar { width: 3px; }
        .sb-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 3px; }
        .sb-section {
            padding: .55rem 1.1rem .2rem; font-size: .58rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .12em; color: rgba(255,255,255,.3);
        }
        .sb-link {
            display: flex; align-items: center; gap: .55rem;
            padding: .55rem 1.1rem; color: rgba(255,255,255,.72); font-size: .8rem; font-weight: 500;
            text-decoration: none; border-left: 3px solid transparent; transition: all .18s;
            position: relative; z-index: 1;
        }
        .sb-link:hover { background: rgba(255,255,255,.08); color: #fff; padding-left: 1.3rem; }
        .sb-link.active { background: rgba(255,255,255,.14); color: #fff; font-weight: 700; border-left-color: var(--accent); }
        .sb-link .sb-icon { font-size: .95rem; width: 18px; text-align: center; flex-shrink: 0; }
        .sb-badge { margin-left: auto; background: #ef4444; color: #fff; font-size: .58rem; font-weight: 700; padding: 1px 6px; border-radius: 10px; }

        /* View-only label di sidebar */
        .sb-viewonly {
            margin: .4rem 1.1rem;
            background: rgba(232,160,32,.15); border: 1px solid rgba(232,160,32,.3);
            border-radius: 8px; padding: .5rem .75rem;
            font-size: .68rem; color: rgba(255,255,255,.65); text-align: center;
        }
        .sb-viewonly .role-name { color: var(--accent-lt); font-weight: 700; font-size: .72rem; }

        .sb-user {
            border-top: 1px solid rgba(255,255,255,.08); padding: .85rem 1.1rem;
            display: flex; align-items: center; gap: .65rem; position: relative; z-index: 1;
        }
        .sb-avatar {
            width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: .78rem; font-weight: 800; color: #fff;
            background: linear-gradient(135deg, var(--accent), var(--accent-lt));
        }
        .sb-user-name { color: #fff; font-size: .76rem; font-weight: 600; line-height: 1.2; }
        .sb-user-role { color: rgba(255,255,255,.4); font-size: .62rem; }
        .sb-logout { margin-left: auto; background: rgba(255,255,255,.1); border: none; color: rgba(255,255,255,.6); border-radius: 8px; padding: 4px 7px; cursor: pointer; font-size: .78rem; transition: all .2s; }
        .sb-logout:hover { background: rgba(239,68,68,.3); color: #fff; }

        /* ===== OVERLAY MOBILE ===== */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 1050; opacity: 0; transition: opacity .3s; }
        .sidebar-overlay.active { display: block; opacity: 1; }

        /* ===== MAIN ===== */
        .main-wrapper { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; transition: margin-left .3s; }
        .topbar { height: 58px; background: #fff; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; padding: 0 1.1rem; position: sticky; top: 0; z-index: 1000; gap: .75rem; }
        .topbar-left { display: flex; align-items: center; gap: .65rem; flex: 1; }
        .topbar-right { display: flex; align-items: center; gap: .65rem; }
        .hamburger { display: none; border: none; background: none; padding: 5px; border-radius: 8px; cursor: pointer; color: #475569; font-size: 1.2rem; }
        .hamburger:hover { background: #f1f5f9; }
        .breadcrumb { margin: 0; padding: 0; background: none; }
        .breadcrumb-item, .breadcrumb-item a { font-size: .75rem; color: #94a3b8; text-decoration: none; }
        .breadcrumb-item.active { color: #1e293b; font-weight: 600; }
        .topbar-badge { background: #fee2e2; color: #991b1b; padding: 3px 9px; border-radius: 20px; font-size: .68rem; font-weight: 700; display: flex; align-items: center; gap: 4px; }
        .topbar-date { font-size: .72rem; color: #94a3b8; white-space: nowrap; }

        /* ===== CONTENT ===== */
        .content { padding: 1.15rem; flex: 1; }
        .card { border: none; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,.07); }
        .card-header { background: #fff; font-weight: 700; font-size: .83rem; border-bottom: 1px solid #f1f5f9; padding: .85rem 1.05rem; border-radius: 14px 14px 0 0 !important; }
        .stat-card { border-radius: 14px; padding: 1.1rem; color: #fff; position: relative; overflow: hidden; border: none; }
        .stat-card::after { content:''; position:absolute; right:-15px; top:-15px; width:100px; height:100px; background:rgba(255,255,255,.08); border-radius:50%; }
        .stat-card .s-icon { font-size: 1.7rem; opacity: .85; margin-bottom: .4rem; }
        .stat-card .s-val { font-size: 1.55rem; font-weight: 800; line-height: 1; }
        .stat-card .s-lbl { font-size: .72rem; font-weight: 600; opacity: .85; margin-top: .2rem; }
        .stat-card .s-sub { font-size: .62rem; opacity: .6; margin-top: .15rem; }
        .table thead th { font-size: .68rem; text-transform: uppercase; letter-spacing: .05em; color: #64748b; font-weight: 700; background: #f8fafc; border-top: none; padding: .6rem .85rem; }
        .table tbody td { padding: .6rem .85rem; vertical-align: middle; }
        .table-hover tbody tr:hover { background: #f8fafc; }
        .badge { font-size: .66rem; font-weight: 600; padding: 3px 8px; border-radius: 20px; }
        .alert { border-radius: 10px; font-size: .82rem; border: none; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger  { background: #fee2e2; color: #991b1b; }
        .form-control, .form-select { border-radius: 10px; font-size: .82rem; border-color: #e2e8f0; font-family: inherit; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(15,76,117,.1); }
        .form-label { font-size: .72rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: .04em; }
        .btn { border-radius: 10px; font-size: .82rem; font-weight: 600; font-family: inherit; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dk); border-color: var(--primary-dk); }

        /* ===== RESPONSIVE ===== */
        @media(max-width:992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 8px 0 30px rgba(0,0,0,.25); }
            .main-wrapper { margin-left: 0; }
            .hamburger { display: flex; }
            .topbar-date { display: none; }
        }
        @media(max-width:576px) {
            .content { padding: .85rem; }
            .stat-card .s-val { font-size: 1.3rem; }
            .topbar-badge { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar()"></div>

<nav class="sidebar" id="sidebar">
    <div class="sb-brand">
        <div class="sb-logo">🏫</div>
        <div>
            <div class="sb-brand-name">SIMAS ASET</div>
            <div class="sb-brand-school">SMKN 11 KOTA TANGERANG</div>
        </div>
    </div>

    <div class="sb-scroll">
        {{-- Info role untuk view-only --}}
        @if(auth()->user()->isViewOnly())
        <div class="sb-viewonly mt-2">
            <div class="role-name">{{ auth()->user()->icon_role }} {{ auth()->user()->label_role }}</div>
            <div style="margin-top:2px">Mode: Lihat Data Saja</div>
        </div>
        @endif

        <div class="sb-section">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="sb-icon">📊</span> Dashboard
        </a>

        <div class="sb-section">Fitur 1 — Aset</div>
        <a href="{{ route('aset.index') }}" class="sb-link {{ request()->routeIs('aset.index','aset.show') ? 'active' : '' }}">
            <span class="sb-icon">📦</span> Data Aset
        </a>
        @if(auth()->user()->canCreate())
        <a href="{{ route('aset.create') }}" class="sb-link {{ request()->routeIs('aset.create','aset.edit') ? 'active' : '' }}">
            <span class="sb-icon">➕</span> Tambah Aset
        </a>
        @endif

        {{-- Fitur 6 — QR Code --}}
        <a href="{{ route('aset.scan') }}" class="sb-link {{ request()->routeIs('aset.scan') ? 'active' : '' }}">
            <span class="sb-icon">📷</span>
            Fitur 6 — Scan QR Code
        </a>

        <div class="sb-section">Fitur 3 & 4 — Tracking</div>
        <a href="{{ route('mutasi.index') }}" class="sb-link {{ request()->routeIs('mutasi.*') ? 'active' : '' }}">
            <span class="sb-icon">🔄</span> Mutasi Aset
        </a>
        <a href="{{ route('pemeliharaan.index') }}" class="sb-link {{ request()->routeIs('pemeliharaan.*') ? 'active' : '' }}">
            <span class="sb-icon">🔧</span> Pemeliharaan
        </a>

        <div class="sb-section">Fitur 7 & 8 — Laporan</div>
        <a href="{{ route('laporan.inventaris') }}" class="sb-link {{ request()->routeIs('laporan.inventaris') ? 'active' : '' }}">
            <span class="sb-icon">📋</span> Lap. Inventaris
        </a>
        <a href="{{ route('laporan.notifikasi') }}" class="sb-link {{ request()->routeIs('laporan.notifikasi') ? 'active' : '' }}">
            <span class="sb-icon">🔔</span> Notifikasi
            @php $n = \App\Models\Aset::whereBetween('masa_garansi',[now(),now()->addDays(30)])->count() + \App\Models\Pemeliharaan::where('status','terjadwal')->whereDate('tanggal_jadwal','<=',now()->addDays(30))->count(); @endphp
            @if($n > 0)<span class="sb-badge">{{ $n }}</span>@endif
        </a>

        {{-- Fitur 2 — Kategorisasi (Master Data) --}}
        <div class="sb-section">Fitur 2 — Master Data</div>
        <a href="{{ route('kategori.index') }}" class="sb-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            <span class="sb-icon">🏷️</span> Kategori
        </a>
        <a href="{{ route('departemen.index') }}" class="sb-link {{ request()->routeIs('departemen.*') ? 'active' : '' }}">
            <span class="sb-icon">🏢</span> Departemen
        </a>
        <a href="{{ route('karyawan.index') }}" class="sb-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
            <span class="sb-icon">👥</span> Karyawan
        </a>

        {{-- Manajemen User — HANYA ADMIN --}}
        @if(auth()->user()->canManageUsers())
        <div class="sb-section">Admin</div>
        <a href="{{ route('users.index') }}" class="sb-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <span class="sb-icon">🔐</span> Manajemen User
        </a>
        @endif
    </div>

    <div class="sb-user">
        <div class="sb-avatar">{{ strtoupper(substr(auth()->user()->nama,0,1)) }}</div>
        <div>
            <div class="sb-user-name">{{ Str::limit(auth()->user()->nama, 20) }}</div>
            <div class="sb-user-role">{{ auth()->user()->icon_role }} {{ auth()->user()->label_role }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin-left:auto">
            @csrf
            <button type="submit" class="sb-logout" title="Keluar"><i class="bi bi-box-arrow-right"></i></button>
        </form>
    </div>
</nav>

<div class="main-wrapper" id="main-wrapper">
    <div class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">SIMAS Aset</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
        <div class="topbar-right">
            @if(auth()->user()->isViewOnly())
            <span class="badge bg-warning text-dark">👁 View Only</span>
            @endif
            @php $notifG = \App\Models\Aset::whereBetween('masa_garansi',[now(),now()->addDays(30)])->count(); @endphp
            @if($notifG > 0)
            <a href="{{ route('laporan.notifikasi') }}" class="topbar-badge text-decoration-none">
                <i class="bi bi-bell-fill"></i> {{ $notifG }} Peringatan
            </a>
            @endif
            <div class="topbar-date">{{ now()->translatedFormat('D, d M Y') }}</div>
        </div>
    </div>

    <div class="content">
        @if(session('sukses'))
        <div class="alert alert-success alert-dismissible fade show mb-3">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('sukses') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('gagal'))
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('gagal') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebar-overlay').classList.toggle('active');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('active');
}
document.querySelectorAll('.sb-link').forEach(l => {
    l.addEventListener('click', () => { if(window.innerWidth <= 992) closeSidebar(); });
});
</script>
@stack('scripts')
</body>
</html>
