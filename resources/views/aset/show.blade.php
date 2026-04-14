@extends('layouts.app')
@section('title', $aset->nama)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('aset.index') }}" class="text-decoration-none">Data Aset</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">{{ $aset->nama }}</h4>
        <small class="text-muted"><code>{{ $aset->kode_aset }}</code> &bull; {{ $aset->kategori->nama ?? '-' }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('aset.qrcode', $aset) }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-qr-code me-1"></i> QR Code
        </a>
        @if(auth()->user()->canEdit())
        <a href="{{ route('aset.edit', \$aset) }}" class="btn btn-warning btn-sm text-white">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        @endif
    </div>
</div>

<div class="row g-3">
    <!-- Info Aset -->
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">Informasi Aset</div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach([
                        ['Nama', $aset->nama],
                        ['Kode Aset', $aset->kode_aset],
                        ['Merek', $aset->merek ?: '-'],
                        ['Serial Number', $aset->serial_number ?: '-'],
                        ['Kondisi', null],
                        ['Lokasi', $aset->lokasi],
                        ['Kategori', $aset->kategori->nama ?? '-'],
                        ['Departemen', $aset->departemen->nama ?? '-'],
                        ['Penanggung Jawab', $aset->penanggungJawab->nama ?? '-'],
                    ] as [$label, $val])
                    <div class="col-md-6">
                        <small class="text-muted d-block">{{ $label }}</small>
                        @if($label === 'Kondisi')
                            {!! $aset->label_kondisi !!}
                        @else
                            <span class="fw-semibold">{{ $val }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @if($aset->keterangan)
                <hr>
                <small class="text-muted d-block">Keterangan</small>
                <p class="mb-0">{{ $aset->keterangan }}</p>
                @endif
            </div>
        </div>

        <!-- Pembelian & Depresiasi -->
        <div class="card mb-3">
            <div class="card-header">Pembelian & Nilai Aset</div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Harga Beli</small>
                        <span class="fw-bold text-primary">Rp {{ number_format($aset->harga_beli,0,',','.') }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Nilai Sekarang</small>
                        <span class="fw-bold text-success">Rp {{ number_format($aset->nilai_sekarang,0,',','.') }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Nilai Sisa</small>
                        <span class="fw-bold text-secondary">Rp {{ number_format($aset->nilai_sisa,0,',','.') }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Tanggal Beli</small>
                        <span>{{ $aset->tanggal_beli->format('d/m/Y') }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Masa Garansi</small>
                        <span>{{ $aset->masa_garansi ? $aset->masa_garansi->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Metode Depresiasi</small>
                        <span>{{ $aset->metode_depresiasi === 'garis_lurus' ? 'Garis Lurus' : 'Saldo Menurun' }}</span>
                    </div>
                </div>

                <!-- Grafik Depresiasi -->
                <canvas id="chartDepresiasi" height="180"></canvas>
            </div>
        </div>

        <!-- Riwayat Mutasi -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Riwayat Mutasi</span>
                <a href="{{ route('mutasi.create') }}?aset={{ $aset->id }}" class="btn btn-sm btn-outline-primary">+ Mutasi Baru</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead><tr><th>Tanggal</th><th>Dari</th><th>Ke</th><th>Alasan</th></tr></thead>
                    <tbody>
                        @forelse($aset->mutasis as $m)
                        <tr>
                            <td class="small">{{ $m->tanggal_mutasi->format('d/m/Y') }}</td>
                            <td class="small">{{ $m->dari_lokasi }}</td>
                            <td class="small fw-semibold">{{ $m->ke_lokasi }}</td>
                            <td class="small text-muted">{{ Str::limit($m->alasan, 40) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted small py-2">Belum ada riwayat mutasi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Riwayat Pemeliharaan -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Riwayat Pemeliharaan</span>
                <a href="{{ route('pemeliharaan.create') }}?aset={{ $aset->id }}" class="btn btn-sm btn-outline-primary">+ Jadwal Baru</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead><tr><th>Tanggal</th><th>Jenis</th><th>Biaya</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($aset->pemeliharaans as $p)
                        <tr>
                            <td class="small">{{ $p->tanggal_jadwal->format('d/m/Y') }}</td>
                            <td class="small">{{ $p->label_jenis }}</td>
                            <td class="small">Rp {{ number_format($p->biaya,0,',','.') }}</td>
                            <td>{!! $p->label_status !!}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted small py-2">Belum ada riwayat pemeliharaan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar kanan -->
    <div class="col-md-4">
        @if($aset->foto)
        <div class="card mb-3">
            <div class="card-body text-center">
                <img src="{{ asset('storage/'.$aset->foto) }}" class="img-fluid rounded" style="max-height:200px">
            </div>
        </div>
        @endif

        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-qr-code me-2 text-primary"></i>QR Code Aset</div>
            <div class="card-body text-center">
                <div class="border rounded p-2 mb-2 d-inline-block">
                    <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                        <span style="font-size:.8rem">🏫</span>
                        <small style="font-size:.6rem;font-weight:700;color:#0f4c75">SMKN 11 TANGERANG</small>
                    </div>
                    {!! QrCode::size(140)->generate(route('aset.scan').'?kode='.$aset->kode_aset) !!}
                    <div class="fw-bold" style="font-size:.75rem;margin-top:4px">{{ $aset->kode_aset }}</div>
                    <div style="font-size:.65rem;color:#64748b">{{ Str::limit($aset->nama, 22) }}</div>
                </div>
                <div class="d-grid mt-2">
                    <a href="{{ route('aset.qrcode', $aset) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-printer me-1"></i>Cetak Label QR
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(auth()->user()->canEdit())
                    <a href="{{ route('aset.edit', \$aset) }}" class="btn btn-warning text-white">
                        <i class="bi bi-pencil me-1"></i> Edit Aset
                    </a>
                    @endif
                    @if(auth()->user()->canCreate())
                    <a href="{{ route('mutasi.create') }}" class="btn btn-info text-white">
                        <i class="bi bi-arrow-left-right me-1"></i> Catat Mutasi
                    </a>
                    <a href="{{ route('pemeliharaan.create') }}" class="btn btn-secondary">
                        <i class="bi bi-wrench me-1"></i> Jadwal Maintenance
                    </a>
                    @if(auth()->user()->canDelete())
                    <form method="POST" action="{{ route('aset.destroy', $aset) }}"
                        onsubmit="return confirm('Yakin hapus aset ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-1"></i> Hapus Aset
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const dep = @json($dataDepresiasi);
new Chart(document.getElementById('chartDepresiasi'), {
    type: 'line',
    data: {
        labels: dep.map(d => d.tahun),
        datasets: [{
            label: 'Nilai Aset (Rp)',
            data: dep.map(d => d.nilai),
            borderColor: '#1e40af',
            backgroundColor: 'rgba(30,64,175,.1)',
            fill: true,
            tension: .4,
            pointRadius: 4,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: {
                ticks: {
                    callback: v => 'Rp ' + Intl.NumberFormat('id').format(v)
                }
            }
        }
    }
});
</script>
@endpush
