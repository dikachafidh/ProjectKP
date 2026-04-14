@extends('layouts.app')
@section('title','Dashboard')
@section('breadcrumb') <li class="breadcrumb-item active">Dashboard</li> @endsection

@section('content')

{{-- Header Banner SMKN 11 Dashboard --}}
<div class="smk-banner mb-4">
    <div class="smk-banner-text">
        <div class="smk-banner-sub">Selamat Datang,</div>
        <div class="smk-banner-title">{{ auth()->user()->nama }}</div>
        <div class="smk-banner-desc">
            <i class="bi bi-building me-1"></i>SMKN 11 Kota Tangerang &mdash;
            Sistem Informasi Manajemen Aset Sekolah
        </div>
    </div>
    <div class="smk-banner-right">
        <div class="smk-year-box">
            <div class="smk-year-label">Tahun Anggaran</div>
            <div class="smk-year">{{ date('Y') }}</div>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#0f4c75,#1b6ca8)">
            <div class="s-icon">📦</div>
            <div class="s-val">{{ $totalAset }}</div>
            <div class="s-lbl">Total Aset</div>
            <div class="s-sub">{{ $totalKategori }} kategori terdaftar</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#065f46,#059669)">
            <div class="s-icon">✅</div>
            <div class="s-val">{{ $asetBaik }}</div>
            <div class="s-lbl">Kondisi Baik</div>
            <div class="s-sub">{{ $totalAset > 0 ? round($asetBaik/$totalAset*100) : 0 }}% dari total</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#92400e,#d97706)">
            <div class="s-icon">⚠️</div>
            <div class="s-val">{{ $asetRusak }}</div>
            <div class="s-lbl">Perlu Perhatian</div>
            <div class="s-sub">Rusak / tidak aktif</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#3730a3,#6366f1)">
            <div class="s-icon">💰</div>
            <div class="s-val" style="font-size:1.1rem">Rp {{ number_format($totalNilai/1000000,1,',','.') }}jt</div>
            <div class="s-lbl">Total Nilai Aset</div>
            <div class="s-sub">Nilai perolehan</div>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart-fill me-2 text-primary"></i>Aset per Kategori
            </div>
            <div class="card-body" style="position:relative; height:220px">
                <canvas id="chartKategori"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Kondisi Aset
            </div>
            <div class="card-body" style="position:relative; height:220px">
                <canvas id="chartKondisi"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Notif + Mutasi --}}
<div class="row g-3">
    {{-- Maintenance Mendatang --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-wrench-adjustable me-2 text-warning"></i>Maintenance Mendatang</span>
                <a href="{{ route('pemeliharaan.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:.7rem;padding:3px 10px">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($maintenanceMendatang as $m)
                <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                    <div class="flex-shrink-0 rounded-3 p-2" style="background:#fef3c7">
                        <i class="bi bi-tools text-warning"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold text-truncate" style="font-size:.82rem">{{ $m->aset->nama ?? '-' }}</div>
                        <small class="text-muted">{{ $m->tanggal_jadwal->format('d M Y') }} &mdash; {{ $m->label_jenis }}</small>
                    </div>
                    <span class="badge bg-warning text-dark flex-shrink-0">{{ $m->label_jenis }}</span>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-calendar-check display-6 d-block mb-2 opacity-25"></i>
                    <small>Tidak ada maintenance mendatang</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Garansi Hampir Habis --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-shield-exclamation me-2 text-danger"></i>Garansi Hampir Habis</span>
                <a href="{{ route('laporan.notifikasi') }}" class="btn btn-sm btn-outline-danger" style="font-size:.7rem;padding:3px 10px">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($garansiHampirHabis as $a)
                <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                    <div class="flex-shrink-0 rounded-3 p-2" style="background:#fee2e2">
                        <i class="bi bi-shield-x text-danger"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold text-truncate" style="font-size:.82rem">{{ $a->nama }}</div>
                        <small class="text-muted">Garansi: {{ $a->masa_garansi->format('d M Y') }}</small>
                    </div>
                    <span class="badge bg-danger flex-shrink-0">{{ $a->masa_garansi->diffForHumans() }}</span>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-shield-check display-6 d-block mb-2 text-success opacity-50"></i>
                    <small>Semua garansi masih aman</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Mutasi Terbaru --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-arrow-left-right me-2 text-primary"></i>Mutasi Aset Terbaru</span>
                <a href="{{ route('mutasi.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:.7rem;padding:3px 10px">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr>
                        <th>Aset</th><th class="d-none d-md-table-cell">Dari</th><th>Ke</th>
                        <th class="d-none d-sm-table-cell">Tanggal</th><th class="d-none d-lg-table-cell">Alasan</th>
                    </tr></thead>
                    <tbody>
                        @forelse($mutasiTerbaru as $m)
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size:.82rem">{{ $m->aset->nama ?? '-' }}</div>
                                <code style="font-size:.7rem;color:#94a3b8">{{ $m->aset->kode_aset ?? '' }}</code>
                            </td>
                            <td class="text-muted d-none d-md-table-cell" style="font-size:.82rem">{{ Str::limit($m->dari_lokasi,25) }}</td>
                            <td style="font-size:.82rem">{{ Str::limit($m->ke_lokasi,25) }}</td>
                            <td class="text-muted d-none d-sm-table-cell" style="font-size:.82rem">{{ $m->tanggal_mutasi->format('d/m/Y') }}</td>
                            <td class="text-muted d-none d-lg-table-cell" style="font-size:.82rem">{{ Str::limit($m->alasan,35) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted"><small>Belum ada mutasi</small></td></tr>
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
.smk-banner {
    background: linear-gradient(135deg, #0a3352 0%, #0f4c75 55%, var(--accent) 100%);
    border-radius: 16px; padding: 1.4rem 1.75rem;
    display: flex; justify-content: space-between; align-items: center;
    overflow: hidden; position: relative;
}
.smk-banner::before {
    content: ''; position: absolute; right: -40px; top: -40px;
    width: 180px; height: 180px; background: rgba(255,255,255,.06);
    border-radius: 50%;
}
.smk-banner::after {
    content: ''; position: absolute; right: 60px; bottom: -50px;
    width: 130px; height: 130px; background: rgba(255,255,255,.04);
    border-radius: 50%;
}
.smk-banner-sub { color: rgba(255,255,255,.65); font-size: .75rem; font-weight: 500; margin-bottom: 2px; }
.smk-banner-title { color: #fff; font-size: 1.35rem; font-weight: 800; line-height: 1.2; margin-bottom: 4px; }
.smk-banner-desc { color: rgba(255,255,255,.65); font-size: .78rem; }
.smk-year-box { text-align: right; position: relative; z-index: 1; }
.smk-year-label { color: rgba(255,255,255,.5); font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }
.smk-year { color: var(--accent-lt); font-size: 2.5rem; font-weight: 800; line-height: 1; }
@media(max-width:576px) {
    .smk-banner { flex-direction: column; gap: .75rem; text-align: center; }
    .smk-year-box { text-align: center; }
    .smk-year { font-size: 1.8rem; }
}
</style>
@endpush

@push('scripts')
<script>
const w = ['#0f4c75','#e8a020','#10b981','#8b5cf6','#ef4444','#06b6d4'];
const dataKat = @json($asetPerKategori);
const dataKon = @json($asetPerKondisi);
const labelKon = { baik:'Baik', rusak:'Rusak', hilang:'Hilang', tidak_aktif:'Tidak Aktif' };

new Chart(document.getElementById('chartKategori'), {
    type: 'doughnut',
    data: {
        labels: dataKat.map(d => d.label),
        datasets: [{ data: dataKat.map(d => d.jumlah), backgroundColor: w, borderWidth: 2, borderColor: '#fff' }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12 } } },
        cutout: '62%'
    }
});

const keys = Object.keys(dataKon);
new Chart(document.getElementById('chartKondisi'), {
    type: 'bar',
    data: {
        labels: keys.map(k => labelKon[k] || k),
        datasets: [{ data: keys.map(k => dataKon[k]), backgroundColor: ['#10b981','#ef4444','#1f2937','#94a3b8'], borderRadius: 8, borderSkipped: false }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
    }
});
</script>
@endpush
