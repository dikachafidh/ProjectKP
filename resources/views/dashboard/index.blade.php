@extends('layouts.app')
@section('title','Dashboard')
@section('breadcrumb') <li class="breadcrumb-item active">Dashboard</li> @endsection

@section('content')

{{-- Greeting + Info Bar --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h4 class="fw-bold mb-1" style="color:#0f172a">
            Selamat Datang, {{ explode(' ', auth()->user()->nama)[0] }}! 👋
        </h4>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-muted" style="font-size:.82rem">
                <i class="bi bi-building me-1"></i>SMKN 11 Kota Tangerang
            </span>
            <span class="text-muted">·</span>
            {!! auth()->user()->badge_role !!}
            <span class="text-muted">·</span>
            <span style="font-size:.75rem;color:#94a3b8">
                <span id="live-dot" class="me-1" style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#10b981;animation:blink 1.5s infinite"></span>
                Live · Update: <span id="last-update">--:--:--</span>
            </span>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <div class="text-end">
            <div style="font-size:.65rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em">Tahun Anggaran</div>
            <div style="font-size:1.4rem;font-weight:800;color:#0f4c75;line-height:1">{{ date('Y') }}</div>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    {{-- Card 1: Total Aset --}}
    <div class="col-6 col-xl-4">
        <div class="card border-0 h-100" style="border-left:4px solid #0f4c75 !important;border-radius:14px">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:12px;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0">📦</div>
                <div>
                    <div class="fw-bold" style="font-size:1.6rem;color:#0f172a;line-height:1" id="stat-total">{{ $totalAset }}</div>
                    <div style="font-size:.75rem;color:#64748b;font-weight:600">Total Aset</div>
                    <div style="font-size:.68rem;color:#94a3b8" id="stat-kategori">{{ $totalKategori }} kategori</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Kondisi Baik --}}
    <div class="col-6 col-xl-4">
        <div class="card border-0 h-100" style="border-left:4px solid #10b981 !important;border-radius:14px">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:12px;background:#d1fae5;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0">✅</div>
                <div>
                    <div class="fw-bold" style="font-size:1.6rem;color:#0f172a;line-height:1" id="stat-baik">{{ $asetBaik }}</div>
                    <div style="font-size:.75rem;color:#64748b;font-weight:600">Kondisi Baik</div>
                    <div style="font-size:.68rem;color:#94a3b8" id="stat-persen">{{ $totalAset > 0 ? round($asetBaik/$totalAset*100) : 0 }}% dari total</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Aset Rusak --}}
    <div class="col-6 col-xl-4">
        <div class="card border-0 h-100" style="border-left:4px solid #ef4444 !important;border-radius:14px">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:12px;background:#fee2e2;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0">⚠️</div>
                <div>
                    <div class="fw-bold" style="font-size:1.6rem;color:#0f172a;line-height:1" id="stat-rusak">{{ $asetRusak }}</div>
                    <div style="font-size:.75rem;color:#64748b;font-weight:600">Aset Rusak</div>
                    <div style="font-size:.68rem;color:#94a3b8" id="stat-tdk-aktif">{{ $asetTidakAktif }} tidak aktif</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Total Nilai Aset (HANYA ADMIN) --}}
    @if(auth()->user()->role !== 'staff')
    <div class="col-6 col-xl-3">
        <div class="card border-0 h-100" style="border-left:4px solid #6366f1 !important;border-radius:14px">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:12px;background:#ede9fe;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0">💰</div>
                <div>
                    <div class="fw-bold" style="font-size:1.25rem;color:#0f172a;line-height:1" id="stat-nilai">Rp {{ number_format($totalNilai/1000000,1,',','.') }}jt</div>
                    <div style="font-size:.75rem;color:#64748b;font-weight:600">Total Nilai Aset</div>
                    <div style="font-size:.68rem;color:#94a3b8" id="stat-karyawan">{{ $totalKaryawan }} karyawan</div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Charts + Quick Info (HANYA ADMIN) --}}
@if(auth()->user()->role !== 'staff')
<div class="row g-3 mb-4">
    {{-- Donut Chart Kategori --}}
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Aset per Kategori</span>
                <span class="badge bg-primary bg-opacity-10 text-primary" id="badge-kategori">{{ $totalAset }} aset</span>
            </div>
            <div class="card-body" style="position:relative;height:200px">
                <canvas id="chartKategori"></canvas>
            </div>
        </div>
    </div>

    {{-- Bar Chart Kondisi --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bar-chart-fill me-2 text-primary"></i>Kondisi Aset</span>
                <span class="badge bg-success bg-opacity-10 text-success" id="badge-kondisi">{{ $asetBaik }} baik</span>
            </div>
            <div class="card-body" style="position:relative;height:200px">
                <canvas id="chartKondisi"></canvas>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-lightning-fill me-2 text-warning"></i>Ringkasan</div>
            <div class="card-body p-0">
                @php
                    $totalNilaiSekarang = \App\Models\Aset::all()->sum('nilai_sekarang');
                    $asetBaru = \App\Models\Aset::whereMonth('tanggal_beli', now()->month)->count();
                @endphp
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <span style="font-size:.78rem;color:#475569">Nilai Sekarang</span>
                    <span style="font-size:.82rem;font-weight:700;color:#10b981">Rp {{ number_format($totalNilaiSekarang/1000000,1,',','.') }}jt</span>
                </div>
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <span style="font-size:.78rem;color:#475569">Aset Beli Bulan Ini</span>
                    <span class="badge bg-primary">{{ $asetBaru }} unit</span>
                </div>
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <span style="font-size:.78rem;color:#475569">Total Departemen</span>
                    <span style="font-size:.82rem;font-weight:700;color:#0f4c75">{{ $totalDepartemen }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center px-3 py-2">
                    <span style="font-size:.78rem;color:#475569">Garansi Bermasalah</span>
                    @php $garansiMasalah = \App\Models\Aset::where('masa_garansi','<=',now()->addDays(30))->whereNotNull('masa_garansi')->count(); @endphp
                    <span class="badge {{ $garansiMasalah > 0 ? 'bg-danger' : 'bg-success' }}">{{ $garansiMasalah }} aset</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Tabel Bawah --}}
<div class="row g-3">

    {{-- Maintenance Mendatang (HANYA ADMIN) --}}
    @if(auth()->user()->role !== 'staff')
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-wrench-adjustable me-2 text-warning"></i>Maintenance Mendatang</span>
                <a href="{{ route('pemeliharaan.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:.7rem;padding:3px 10px">Lihat Semua</a>
            </div>
            <div class="card-body p-0" id="list-maintenance">
                @forelse($maintenanceMendatang as $m)
                <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:36px;height:36px;background:{{ $m->tanggal_jadwal->isToday() ? '#fee2e2' : '#fef3c7' }}">
                        <i class="bi bi-tools" style="color:{{ $m->tanggal_jadwal->isToday() ? '#ef4444' : '#f59e0b' }}"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold text-truncate" style="font-size:.83rem">{{ $m->aset->nama ?? '-' }}</div>
                        <div style="font-size:.73rem;color:#94a3b8">{{ $m->tanggal_jadwal->format('d M Y') }} · {{ $m->label_jenis }}</div>
                    </div>
                    @if($m->tanggal_jadwal->isToday())
                        <span class="badge bg-danger">Hari Ini!</span>
                    @else
                        <span class="badge bg-warning text-dark">{{ $m->tanggal_jadwal->diffForHumans() }}</span>
                    @endif
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-calendar-check fs-4 d-block mb-1 opacity-25"></i>
                    <small>Tidak ada maintenance mendatang</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    {{-- Garansi Hampir Habis (HANYA ADMIN) --}}
    @if(auth()->user()->role !== 'staff')
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-shield-exclamation me-2 text-danger"></i>Peringatan Garansi</span>
                <a href="{{ route('laporan.notifikasi') }}" class="btn btn-sm btn-outline-danger" style="font-size:.7rem;padding:3px 10px">Lihat Semua</a>
            </div>
            <div class="card-body p-0" id="list-garansi">
                @forelse($garansiHampirHabis as $a)
                <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:36px;height:36px;background:{{ $a->masa_garansi->isPast() ? '#fee2e2' : '#fef3c7' }}">
                        <i class="bi bi-shield-x" style="color:{{ $a->masa_garansi->isPast() ? '#ef4444' : '#f59e0b' }}"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold text-truncate" style="font-size:.83rem">{{ $a->nama }}</div>
                        <div style="font-size:.73rem;color:#94a3b8">
                            <code style="font-size:.68rem">{{ $a->kode_aset }}</code> · {{ $a->masa_garansi->format('d M Y') }}
                        </div>
                    </div>
                    <span class="badge {{ $a->masa_garansi->isPast() ? 'bg-danger' : 'bg-warning text-dark' }} flex-shrink-0">
                        {{ $a->masa_garansi->diffForHumans() }}
                    </span>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-shield-check fs-4 d-block mb-1 text-success opacity-50"></i>
                    <small>Semua garansi aman</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    {{-- Mutasi Terbaru --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-arrow-left-right me-2 text-primary"></i>Mutasi Aset Terbaru</span>
                <div class="d-flex align-items-center gap-2">
                    <small class="text-muted" id="badge-mutasi" style="font-size:.7rem"></small>
                    <a href="{{ route('mutasi.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:.7rem;padding:3px 10px">Lihat Semua</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr>
                        <th>Aset</th>
                        <th class="d-none d-md-table-cell">Dari</th>
                        <th>Ke</th>
                        <th class="d-none d-sm-table-cell">Tanggal</th>
                    </tr></thead>
                    <tbody id="tbody-mutasi">
                        @forelse($mutasiTerbaru as $m)
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size:.83rem">{{ $m->aset->nama ?? '-' }}</div>
                                <code style="font-size:.7rem;color:#94a3b8">{{ $m->aset->kode_aset ?? '' }}</code>
                            </td>
                            <td class="text-muted d-none d-md-table-cell" style="font-size:.82rem">{{ Str::limit($m->dari_lokasi,25) }}</td>
                            <td style="font-size:.82rem">{{ Str::limit($m->ke_lokasi,25) }}</td>
                            <td class="text-muted d-none d-sm-table-cell" style="font-size:.82rem">{{ $m->tanggal_mutasi->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3 text-muted"><small>Belum ada mutasi</small></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@keyframes blink { 0%,100%{opacity:1}50%{opacity:.3} }
.stat-update { animation: statFlash .5s ease; }
@keyframes statFlash { 0%{opacity:.3;transform:scale(.95)}100%{opacity:1;transform:scale(1)} }
</style>
@endpush

@push('scripts')
<script>
const initKategori = @json($asetPerKategori);
const initKondisi  = @json($asetPerKondisi);
const apiUrl       = '{{ route("dashboard.api") }}';
const warna        = ['#0f4c75','#e8a020','#10b981','#8b5cf6','#ef4444','#06b6d4'];
const labelKondisi = { baik:'Baik', rusak:'Rusak', hilang:'Hilang', tidak_aktif:'Tidak Aktif' };
const isStaff = {{ auth()->user()->role === 'staff' ? 'true' : 'false' }};

let chartKat, chartKon;

// Init Chart Kategori (hanya jika bukan staff)
if (!isStaff) {
    chartKat = new Chart(document.getElementById('chartKategori'), {
        type: 'doughnut',
        data: {
            labels: initKategori.map(d => d.label),
            datasets: [{ data: initKategori.map(d => d.jumlah), backgroundColor: warna, borderWidth: 2, borderColor: '#fff' }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 10 } },
                tooltip: { callbacks: { label: c => ` ${c.label}: ${c.raw} aset` } }
            },
            cutout: '58%'
        }
    });

    // Init Chart Kondisi
    const iniKonKeys = Object.keys(initKondisi);
    chartKon = new Chart(document.getElementById('chartKondisi'), {
        type: 'bar',
        data: {
            labels: iniKonKeys.map(k => labelKondisi[k] || k),
            datasets: [{ data: iniKonKeys.map(k => initKondisi[k]), backgroundColor: ['#10b981','#ef4444','#1f2937','#94a3b8'], borderRadius: 8, borderSkipped: false }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });
}

function updateEl(id, val) {
    const el = document.getElementById(id);
    if (!el || el.textContent.trim() === String(val)) return;
    el.textContent = val;
    el.classList.remove('stat-update');
    void el.offsetWidth;
    el.classList.add('stat-update');
}

function fetchDashboard() {
    fetch(apiUrl, {
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        // Stat cards (semua role bisa lihat 3 card pertama)
        updateEl('stat-total',    data.stats.totalAset);
        updateEl('stat-baik',     data.stats.asetBaik);
        updateEl('stat-rusak',    data.stats.asetRusak);
        updateEl('stat-kategori', data.stats.totalKategori + ' kategori');
        updateEl('stat-persen',   data.stats.persenBaik + '% dari total');
        updateEl('stat-tdk-aktif',data.stats.asetTidakAktif + ' tidak aktif');
        
        // Hanya admin yang update stat-nilai
        if (!isStaff && document.getElementById('stat-nilai')) {
            updateEl('stat-nilai', data.stats.totalNilaiFormat);
            updateEl('stat-karyawan', data.stats.totalKaryawan + ' karyawan');
        }
        
        // Hanya admin yang update badge dan chart
        if (!isStaff) {
            document.getElementById('badge-kategori').textContent = data.stats.totalAset + ' aset';
            document.getElementById('badge-kondisi').textContent  = data.stats.asetBaik + ' baik';
            document.getElementById('badge-mutasi').textContent   = 'Terakhir update: ' + data.lastUpdate;
            
            // Update chart
            if (chartKat) {
                chartKat.data.labels = data.chartKategori.map(d => d.label);
                chartKat.data.datasets[0].data = data.chartKategori.map(d => d.jumlah);
                chartKat.update('active');
            }
            
            if (chartKon) {
                const kk = Object.keys(data.chartKondisi);
                chartKon.data.labels = kk.map(k => labelKondisi[k] || k);
                chartKon.data.datasets[0].data = kk.map(k => data.chartKondisi[k]);
                chartKon.update('active');
            }
            
            // Maintenance list
            const lm = document.getElementById('list-maintenance');
            if (lm) {
                lm.innerHTML = data.maintenance.length ? data.maintenance.map(m => `
                    <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:36px;height:36px;background:${m.isToday?'#fee2e2':'#fef3c7'}">
                            <i class="bi bi-tools" style="color:${m.isToday?'#ef4444':'#f59e0b'}"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-truncate" style="font-size:.83rem">${m.nama}</div>
                            <div style="font-size:.73rem;color:#94a3b8">${m.tanggal} · ${m.jenis}</div>
                        </div>
                        ${m.isToday ? '<span class="badge bg-danger">Hari Ini!</span>' : `<span class="badge bg-warning text-dark">${m.diff}</span>`}
                    </div>`).join('') :
                    `<div class="text-center py-4 text-muted"><i class="bi bi-calendar-check fs-4 d-block mb-1 opacity-25"></i><small>Tidak ada maintenance mendatang</small></div>`;
            }
            
            // Garansi list
            const lg = document.getElementById('list-garansi');
            if (lg) {
                lg.innerHTML = data.garansi.length ? data.garansi.map(a => `
                    <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:36px;height:36px;background:${a.habis?'#fee2e2':'#fef3c7'}">
                            <i class="bi bi-shield-x" style="color:${a.habis?'#ef4444':'#f59e0b'}"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-truncate" style="font-size:.83rem">${a.nama}</div>
                            <div style="font-size:.73rem;color:#94a3b8"><code style="font-size:.68rem">${a.kode}</code> · ${a.tanggal}</div>
                        </div>
                        <span class="badge ${a.habis?'bg-danger':'bg-warning text-dark'} flex-shrink-0">${a.diff}</span>
                    </div>`).join('') :
                    `<div class="text-center py-4 text-muted"><i class="bi bi-shield-check fs-4 d-block mb-1 text-success opacity-50"></i><small>Semua garansi aman</small></div>`;
            }
        }
        
        // Mutasi (semua role bisa lihat)
        document.getElementById('last-update').textContent = data.lastUpdate;
        const tm = document.getElementById('tbody-mutasi');
        tm.innerHTML = data.mutasi.length ? data.mutasi.map(m => `
            <tr>
                <td><div class="fw-semibold" style="font-size:.83rem">${m.aset}</div><code style="font-size:.7rem;color:#94a3b8">${m.kode}</code></td>
                <td class="text-muted d-none d-md-table-cell" style="font-size:.82rem">${m.dari}</td>
                <td style="font-size:.82rem">${m.ke}</td>
                <td class="text-muted d-none d-sm-table-cell" style="font-size:.82rem">${m.tanggal}</td>
            </tr>`).join('') :
            `<tr><td colspan="4" class="text-center py-3 text-muted"><small>Belum ada mutasi</small></td></tr>`;

        document.getElementById('live-dot').style.background = '#10b981';
    })
    .catch(() => { document.getElementById('live-dot').style.background = '#ef4444'; });
}

setInterval(fetchDashboard, 30000);
document.addEventListener('visibilitychange', () => { if (!document.hidden) fetchDashboard(); });
</script>
@endpush