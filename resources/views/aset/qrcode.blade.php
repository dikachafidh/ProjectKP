@extends('layouts.app')
@section('title', 'QR Code — '.$aset->kode_aset)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('aset.index') }}" class="text-decoration-none">Data Aset</a></li>
    <li class="breadcrumb-item active">QR Code</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-qr-code me-2 text-primary"></i>Label QR Code Aset</span>
                <button class="btn btn-primary btn-sm" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i>Cetak
                </button>
            </div>
            <div class="card-body text-center" id="printArea">
                {{-- Label cetak --}}
                <div class="label-cetak border rounded p-3 mb-3">
                    {{-- Header Sekolah --}}
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-2 pb-2 border-bottom">
                        <span style="font-size:1.4rem">🏫</span>
                        <div class="text-start">
                            <div style="font-weight:800;font-size:.75rem;line-height:1.1;color:#0f4c75">SIMAS ASET</div>
                            <div style="font-size:.6rem;color:#64748b;font-weight:600">SMKN 11 KOTA TANGERANG</div>
                        </div>
                    </div>

                    {{-- QR Code --}}
                    <div class="mb-2">
                        {!! QrCode::size(180)->generate(route('aset.scan').'?kode='.$aset->kode_aset) !!}
                    </div>

                    {{-- Info Aset --}}
                    <div style="font-weight:800;font-size:.9rem;color:#0f172a">{{ $aset->kode_aset }}</div>
                    <div style="font-size:.75rem;font-weight:600;color:#1e293b;margin-top:2px">{{ $aset->nama }}</div>
                    <div style="font-size:.65rem;color:#64748b;margin-top:2px">{{ $aset->departemen->nama ?? '' }}</div>
                    <div style="font-size:.6rem;color:#94a3b8;margin-top:4px;border-top:1px dashed #e2e8f0;padding-top:4px">
                        Scan untuk informasi lengkap aset
                    </div>
                </div>

                {{-- Info detail di bawah (tidak ikut cetak) --}}
                <div class="text-start small no-print">
                    <div class="row g-1">
                        <div class="col-5 text-muted">Nama</div>
                        <div class="col-7 fw-semibold">{{ $aset->nama }}</div>
                        <div class="col-5 text-muted">Merek</div>
                        <div class="col-7">{{ $aset->merek ?: '-' }}</div>
                        <div class="col-5 text-muted">Lokasi</div>
                        <div class="col-7">{{ $aset->lokasi }}</div>
                        <div class="col-5 text-muted">Kondisi</div>
                        <div class="col-7">{!! $aset->label_kondisi !!}</div>
                        <div class="col-5 text-muted">Departemen</div>
                        <div class="col-7">{{ $aset->departemen->nama ?? '-' }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white d-flex gap-2 no-print">
                <button class="btn btn-primary flex-grow-1" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i>Cetak Label
                </button>
                <a href="{{ route('aset.show', $aset) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.label-cetak { background: #fff; max-width: 220px; margin: 0 auto; }

@media print {
    .sidebar, .topbar, .card-footer, .no-print,
    .breadcrumb, .alert { display: none !important; }
    .main-wrapper { margin: 0 !important; }
    body, .content { background: #fff !important; padding: 0 !important; }
    .card { box-shadow: none !important; border: none !important; }
    .label-cetak { border: 1px solid #e2e8f0 !important; }
    #printArea { padding: 0 !important; }
}
</style>
@endpush
