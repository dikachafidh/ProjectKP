@extends('layouts.app')
@section('title','Notifikasi Peringatan')
@section('breadcrumb') <li class="breadcrumb-item active">Notifikasi</li> @endsection

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0">Notifikasi & Peringatan</h4>
    <small class="text-muted">Peringatan garansi & jadwal maintenance 30 hari ke depan &mdash; SMKN 11 Kota Tangerang</small>
</div>

{{-- Summary badges --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center py-3" style="border-left:4px solid #ef4444">
            <div style="font-size:1.5rem">🛡️</div>
            <div class="fw-bold fs-5 text-danger">{{ $garansiHabis->count() }}</div>
            <small class="text-muted">Garansi Bermasalah</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center py-3" style="border-left:4px solid #f59e0b">
            <div style="font-size:1.5rem">🔧</div>
            <div class="fw-bold fs-5 text-warning">{{ $maintenanceMendatang->count() }}</div>
            <small class="text-muted">Maintenance Mendatang</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center py-3" style="border-left:4px solid #10b981">
            <div style="font-size:1.5rem">📅</div>
            <div class="fw-bold fs-5 text-success">{{ $maintenanceMendatang->where('status','terjadwal')->count() }}</div>
            <small class="text-muted">Terjadwal</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center py-3" style="border-left:4px solid #6366f1">
            <div style="font-size:1.5rem">⏰</div>
            <div class="fw-bold fs-5" style="color:#6366f1">30</div>
            <small class="text-muted">Hari ke Depan</small>
        </div>
    </div>
</div>

{{-- Garansi Bermasalah --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center" style="background:#fee2e2">
        <span class="fw-bold text-danger">
            <i class="bi bi-shield-exclamation me-2"></i>Peringatan Garansi Aset
        </span>
        <span class="badge bg-danger rounded-pill">{{ $garansiHabis->count() }} aset</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr>
                <th>Nama Aset</th>
                <th class="d-none d-md-table-cell">Departemen</th>
                <th class="d-none d-lg-table-cell">Lokasi</th>
                <th>Masa Garansi</th>
                <th>Status</th>
                <th class="text-center no-print">Aksi</th>
            </tr></thead>
            <tbody>
                @forelse($garansiHabis as $a)
                <tr class="{{ $a->masa_garansi->isPast() ? 'table-danger' : '' }}">
                    <td>
                        <div class="fw-semibold" style="font-size:.82rem">{{ $a->nama }}</div>
                        <code style="font-size:.7rem;color:#94a3b8">{{ $a->kode_aset }}</code>
                    </td>
                    <td class="d-none d-md-table-cell small">{{ $a->departemen->nama ?? '-' }}</td>
                    <td class="d-none d-lg-table-cell small text-muted">{{ $a->lokasi }}</td>
                    <td class="small fw-semibold">{{ $a->masa_garansi->format('d/m/Y') }}</td>
                    <td>
                        @if($a->masa_garansi->isPast())
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle me-1"></i>Habis {{ $a->masa_garansi->diffForHumans() }}
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-exclamation-triangle me-1"></i>Habis {{ $a->masa_garansi->diffForHumans() }}
                            </span>
                        @endif
                    </td>
                    <td class="text-center no-print">
                        <a href="{{ route('aset.show', $a) }}" class="btn btn-sm btn-outline-primary" style="font-size:.7rem">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="bi bi-shield-check display-6 d-block mb-2 text-success opacity-50"></i>
                        <small>Tidak ada aset dengan garansi bermasalah</small>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Maintenance Mendatang --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center" style="background:#fef3c7">
        <span class="fw-bold text-warning">
            <i class="bi bi-wrench-adjustable me-2"></i>Jadwal Maintenance Mendatang
        </span>
        <span class="badge bg-warning text-dark rounded-pill">{{ $maintenanceMendatang->count() }} jadwal</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr>
                <th>Tanggal Jadwal</th>
                <th>Aset</th>
                <th class="d-none d-md-table-cell">Jenis</th>
                <th class="d-none d-lg-table-cell">Teknisi</th>
                <th>Status</th>
                <th class="text-center no-print">Aksi</th>
            </tr></thead>
            <tbody>
                @forelse($maintenanceMendatang as $p)
                <tr class="{{ $p->tanggal_jadwal->isToday() ? 'table-warning' : '' }}">
                    <td>
                        <div class="fw-semibold small">{{ $p->tanggal_jadwal->format('d M Y') }}</div>
                        @if($p->tanggal_jadwal->isToday())
                            <span class="badge bg-danger" style="font-size:.65rem">🔴 Hari Ini!</span>
                        @elseif($p->tanggal_jadwal->isTomorrow())
                            <span class="badge bg-warning text-dark" style="font-size:.65rem">🟡 Besok</span>
                        @else
                            <small class="text-muted">{{ $p->tanggal_jadwal->diffForHumans() }}</small>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold small">{{ $p->aset->nama ?? '-' }}</div>
                        <code style="font-size:.7rem;color:#94a3b8">{{ $p->aset->kode_aset ?? '' }}</code>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge bg-secondary bg-opacity-15 text-dark">{{ $p->label_jenis }}</span>
                    </td>
                    <td class="d-none d-lg-table-cell small text-muted">{{ $p->teknisi ?: '-' }}</td>
                    <td>{!! $p->label_status !!}</td>
                    <td class="text-center no-print">
                        <a href="{{ route('pemeliharaan.edit', $p) }}" class="btn btn-sm btn-warning text-white" style="font-size:.7rem">
                            <i class="bi bi-pencil me-1"></i>Update
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="bi bi-calendar-check display-6 d-block mb-2 text-success opacity-50"></i>
                        <small>Tidak ada jadwal maintenance mendatang</small>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .sidebar, .topbar, .no-print, .btn, .breadcrumb, nav { display: none !important; }
    .main-wrapper { margin: 0 !important; }
    body, .content { background: #fff !important; padding: 0 !important; }
    .card { box-shadow: none !important; }
    .table { font-size: 9px !important; }
    .d-none { display: table-cell !important; }
}
</style>
@endpush
