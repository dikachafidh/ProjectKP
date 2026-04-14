@extends('layouts.app')
@section('title','Scan QR Code')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('aset.index') }}" class="text-decoration-none">Data Aset</a></li>
    <li class="breadcrumb-item active">Scan QR Code</li>
@endsection

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0">Scan QR Code Aset</h4>
    <small class="text-muted">Identifikasi aset SMKN 11 Tangerang via kamera atau kode manual</small>
</div>

<div class="row g-3 justify-content-center">
    <div class="col-12 col-md-7 col-lg-5">

        {{-- Scanner --}}
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-camera me-2 text-primary"></i>Kamera Scanner
            </div>
            <div class="card-body">
                <div id="reader" class="mb-3 rounded overflow-hidden"
                    style="background:#1e293b;min-height:200px;display:flex;align-items:center;justify-content:center">
                    <div class="text-center text-white opacity-50 py-4">
                        <i class="bi bi-camera display-4 d-block mb-2"></i>
                        <small>Memuat kamera...</small>
                    </div>
                </div>
                <div class="text-center">
                    <small class="text-muted">Arahkan kamera ke label QR Code aset SMKN 11 Tangerang</small>
                </div>
            </div>
        </div>

        {{-- Manual Input --}}
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-keyboard me-2 text-secondary"></i>Input Kode Manual
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('aset.scan') }}" class="d-flex gap-2">
                    <input type="text" name="kode" class="form-control" id="inputKode"
                        placeholder="Contoh: ELK-2025-0001"
                        value="{{ $kode ?? '' }}" autocomplete="off">
                    <button type="submit" class="btn btn-primary px-3">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Hasil --}}
        @if($aset)
        <div class="card border-success">
            <div class="card-header" style="background:#d1fae5;border-color:#6ee7b7">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <span class="fw-bold text-success">Aset Ditemukan!</span>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded p-2" style="background:#d1fae5;font-size:1.5rem">📦</div>
                    <div>
                        <div class="fw-bold">{{ $aset->nama }}</div>
                        <code style="font-size:.75rem;color:#64748b">{{ $aset->kode_aset }}</code>
                    </div>
                    <div class="ms-auto">{!! $aset->label_kondisi !!}</div>
                </div>
                <table class="table table-sm mb-3">
                    <tr>
                        <td class="text-muted" style="width:40%;font-size:.8rem">Kategori</td>
                        <td style="font-size:.8rem">{{ $aset->kategori->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted" style="font-size:.8rem">Departemen</td>
                        <td style="font-size:.8rem">{{ $aset->departemen->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted" style="font-size:.8rem">Lokasi</td>
                        <td style="font-size:.8rem">{{ $aset->lokasi }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted" style="font-size:.8rem">Penanggung Jawab</td>
                        <td style="font-size:.8rem">{{ $aset->penanggungJawab->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted" style="font-size:.8rem">Garansi</td>
                        <td style="font-size:.8rem">
                            @if($aset->masa_garansi)
                                <span class="{{ $aset->garansi_habis ? 'text-danger' : 'text-success' }}">
                                    {{ $aset->masa_garansi->format('d M Y') }}
                                    {{ $aset->garansi_habis ? '(Habis)' : '' }}
                                </span>
                            @else —
                            @endif
                        </td>
                    </tr>
                </table>
                <div class="d-grid gap-2">
                    <a href="{{ route('aset.show', $aset) }}" class="btn btn-primary">
                        <i class="bi bi-eye me-1"></i>Lihat Detail Lengkap
                    </a>
                    <a href="{{ route('aset.edit', $aset) }}" class="btn btn-outline-warning">
                        <i class="bi bi-pencil me-1"></i>Edit Aset
                    </a>
                </div>
            </div>
        </div>
        @elseif($kode)
        <div class="card border-danger">
            <div class="card-body text-center py-4">
                <i class="bi bi-x-circle-fill text-danger display-5 d-block mb-2"></i>
                <div class="fw-bold text-danger mb-1">Aset Tidak Ditemukan</div>
                <small class="text-muted">Kode <code>{{ $kode }}</code> tidak ada di database SMKN 11 Tangerang</small>
                <div class="mt-3">
                    <a href="{{ route('aset.scan') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-repeat me-1"></i>Scan Ulang
                    </a>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
const html5QrCode = new Html5Qrcode("reader");
const config = { fps: 10, qrbox: { width: 220, height: 220 }, aspectRatio: 1.0 };

html5QrCode.start(
    { facingMode: "environment" },
    config,
    (decodedText) => {
        html5QrCode.stop().then(() => {
            try {
                const url = new URL(decodedText);
                const kode = url.searchParams.get('kode');
                if (kode) {
                    window.location.href = '{{ route("aset.scan") }}?kode=' + encodeURIComponent(kode);
                } else {
                    window.location.href = decodedText;
                }
            } catch(e) {
                // Bukan URL — coba sebagai kode langsung
                window.location.href = '{{ route("aset.scan") }}?kode=' + encodeURIComponent(decodedText);
            }
        });
    },
    (errorMsg) => { /* ignore scan errors */ }
).catch(err => {
    document.getElementById('reader').innerHTML =
        '<div class="text-center text-white opacity-50 py-5">' +
        '<i class="bi bi-camera-video-off display-4 d-block mb-2"></i>' +
        '<small>Kamera tidak tersedia.<br>Gunakan input kode manual di bawah.</small></div>';
});

// Auto-focus input manual
@if(!$kode)
document.getElementById('inputKode')?.focus();
@endif
</script>
@endpush
