@extends('layouts.app')
@section('title', 'Detail Mutasi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('mutasi.index') }}" class="text-decoration-none">Mutasi Aset</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-arrow-left-right me-2 text-primary"></i>Detail Mutasi Aset</span>
                <small class="text-muted">{{ $mutasi->tanggal_mutasi->format('d M Y') }}</small>
            </div>
            <div class="card-body">
                <!-- Aset Info -->
                <div class="alert alert-primary d-flex align-items-center gap-3 mb-4">
                    <i class="bi bi-box-seam fs-3"></i>
                    <div>
                        <div class="fw-bold">{{ $mutasi->aset->nama ?? '-' }}</div>
                        <small><code>{{ $mutasi->aset->kode_aset ?? '' }}</code></small>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ route('aset.show', $mutasi->aset) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Lihat Aset
                        </a>
                    </div>
                </div>

                <!-- Dari → Ke -->
                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small fw-semibold mb-2"><i class="bi bi-geo me-1"></i>DARI</div>
                            <div class="fw-bold mb-1">{{ $mutasi->dari_lokasi }}</div>
                            @if($mutasi->dariDepartemen)
                            <div class="small"><i class="bi bi-diagram-3 me-1 text-muted"></i>{{ $mutasi->dariDepartemen->nama }}</div>
                            @endif
                            @if($mutasi->dariKaryawan)
                            <div class="small"><i class="bi bi-person me-1 text-muted"></i>{{ $mutasi->dariKaryawan->nama }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-arrow-right display-6 text-primary"></i>
                    </div>
                    <div class="col-md-5">
                        <div class="border border-primary rounded p-3 h-100 bg-primary bg-opacity-5">
                            <div class="text-primary small fw-semibold mb-2"><i class="bi bi-geo-fill me-1"></i>KE</div>
                            <div class="fw-bold mb-1">{{ $mutasi->ke_lokasi }}</div>
                            @if($mutasi->keDepartemen)
                            <div class="small"><i class="bi bi-diagram-3 me-1 text-muted"></i>{{ $mutasi->keDepartemen->nama }}</div>
                            @endif
                            @if($mutasi->keKaryawan)
                            <div class="small"><i class="bi bi-person me-1 text-muted"></i>{{ $mutasi->keKaryawan->nama }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Info tambahan -->
                <table class="table table-sm">
                    <tr>
                        <th class="text-muted small w-40">Alasan Mutasi</th>
                        <td>{{ $mutasi->alasan }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted small">Disetujui Oleh</th>
                        <td>{{ $mutasi->disetujui_oleh ?: '-' }}</td>
                    </tr>
                    @if($mutasi->keterangan)
                    <tr>
                        <th class="text-muted small">Keterangan</th>
                        <td>{{ $mutasi->keterangan }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th class="text-muted small">Dicatat Pada</th>
                        <td>{{ $mutasi->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('mutasi.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
