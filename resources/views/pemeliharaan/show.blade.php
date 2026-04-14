@extends('layouts.app')
@section('title', 'Detail Pemeliharaan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pemeliharaan.index') }}" class="text-decoration-none">Pemeliharaan</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-wrench me-2 text-primary"></i>Detail Pemeliharaan</span>
                {!! $pemeliharaan->label_status !!}
            </div>
            <div class="card-body">
                <!-- Aset -->
                <div class="alert alert-light border mb-4 d-flex align-items-center gap-3">
                    <i class="bi bi-box-seam fs-3 text-primary"></i>
                    <div>
                        <div class="fw-bold">{{ $pemeliharaan->aset->nama ?? '-' }}</div>
                        <small class="text-muted"><code>{{ $pemeliharaan->aset->kode_aset ?? '' }}</code> &bull; {{ $pemeliharaan->aset->lokasi ?? '' }}</small>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ route('aset.show', $pemeliharaan->aset) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Lihat Aset
                        </a>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4 text-center p-3 bg-light rounded">
                        <div class="small text-muted mb-1">Tanggal Jadwal</div>
                        <div class="fw-bold">{{ $pemeliharaan->tanggal_jadwal->format('d M Y') }}</div>
                    </div>
                    <div class="col-md-4 text-center p-3 bg-light rounded">
                        <div class="small text-muted mb-1">Jenis</div>
                        <div class="fw-bold">{{ $pemeliharaan->label_jenis }}</div>
                    </div>
                    <div class="col-md-4 text-center p-3 bg-light rounded">
                        <div class="small text-muted mb-1">Biaya</div>
                        <div class="fw-bold text-primary">Rp {{ number_format($pemeliharaan->biaya, 0, ',', '.') }}</div>
                    </div>
                </div>

                <table class="table table-sm">
                    <tr>
                        <th class="text-muted small" style="width:35%">Teknisi / Vendor</th>
                        <td>{{ $pemeliharaan->teknisi ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted small">Tanggal Selesai</th>
                        <td>{{ $pemeliharaan->tanggal_selesai ? $pemeliharaan->tanggal_selesai->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted small">Deskripsi Pekerjaan</th>
                        <td>{{ $pemeliharaan->deskripsi }}</td>
                    </tr>
                    @if($pemeliharaan->hasil)
                    <tr>
                        <th class="text-muted small">Hasil Pemeliharaan</th>
                        <td>{{ $pemeliharaan->hasil }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="card-footer bg-white d-flex gap-2">
                <a href="{{ route('pemeliharaan.edit', $pemeliharaan) }}" class="btn btn-warning text-white btn-sm">
                    <i class="bi bi-pencil me-1"></i>Update Status
                </a>
                <a href="{{ route('pemeliharaan.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                <form method="POST" action="{{ route('pemeliharaan.destroy', $pemeliharaan) }}"
                    onsubmit="return confirm('Hapus data ini?')" class="ms-auto">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
