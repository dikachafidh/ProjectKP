@extends('layouts.app')
@section('title','Laporan Inventaris')
@section('breadcrumb') <li class="breadcrumb-item active">Laporan Inventaris</li> @endsection

@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Laporan Inventaris Aset</h4>
        <small class="text-muted">SMKN 11 Kota Tangerang &mdash; Tahun {{ date('Y') }}</small>
    </div>
    <button class="btn btn-success btn-sm" onclick="window.print()">
        <i class="bi bi-printer me-1"></i>Cetak / PDF
    </button>
</div>

{{-- Filter --}}
<div class="card mb-3 no-print">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('laporan.inventaris') }}" class="row g-2 align-items-end">
            <div class="col-6 col-md-3">
                <select name="kondisi" class="form-select form-select-sm">
                    <option value="">Semua Kondisi</option>
                    <option value="baik"        {{ request('kondisi')=='baik'        ? 'selected':'' }}>Baik</option>
                    <option value="rusak"       {{ request('kondisi')=='rusak'       ? 'selected':'' }}>Rusak</option>
                    <option value="hilang"      {{ request('kondisi')=='hilang'      ? 'selected':'' }}>Hilang</option>
                    <option value="tidak_aktif" {{ request('kondisi')=='tidak_aktif' ? 'selected':'' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="departemen_id" class="form-select form-select-sm">
                    <option value="">Semua Departemen</option>
                    @foreach($departemens as $d)
                    <option value="{{ $d->id }}" {{ request('departemen_id')==$d->id ? 'selected':'' }}>{{ $d->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="kategori_id" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id')==$k->id ? 'selected':'' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="{{ route('laporan.inventaris') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Ringkasan Stat --}}
<div class="row g-2 mb-3">
    @foreach([
        ['Total Item', $asets->count(), 'bg-primary', '📦'],
        ['Kondisi Baik', $asets->where('kondisi','baik')->count(), 'bg-success', '✅'],
        ['Kondisi Rusak', $asets->where('kondisi','rusak')->count(), 'bg-danger', '⚠️'],
        ['Tidak Aktif', $asets->where('kondisi','tidak_aktif')->count(), 'bg-secondary', '🔒'],
    ] as [$lbl, $val, $cls, $ic])
    <div class="col-6 col-md-3">
        <div class="card text-center py-2">
            <div class="fs-5">{{ $ic }}</div>
            <div class="fw-bold fs-5 {{ str_replace('bg-','text-',$cls) }}">{{ $val }}</div>
            <small class="text-muted" style="font-size:.7rem">{{ $lbl }}</small>
        </div>
    </div>
    @endforeach
</div>

{{-- Header Cetak --}}
<div class="print-only print-header">
    <div class="d-flex align-items-center gap-3 border-bottom pb-3 mb-3">
        <div style="font-size:2.5rem">🏫</div>
        <div>
            <div style="font-size:1.1rem;font-weight:800;color:#0f4c75">LAPORAN INVENTARIS ASET</div>
            <div style="font-size:.85rem;font-weight:700">SMKN 11 KOTA TANGERANG</div>
            <div style="font-size:.75rem;color:#64748b">Jl. Nusantara Raya, Panunggangan Barat, Cibodas, Kota Tangerang</div>
        </div>
    </div>
    <div style="font-size:.78rem;color:#64748b;margin-bottom:.5rem">
        Dicetak: {{ now()->isoFormat('dddd, D MMMM Y, HH:mm') }} WIB &bull;
        Oleh: {{ auth()->user()->nama }} ({{ auth()->user()->label_role }})
        @if(request('kondisi')) &bull; Filter Kondisi: {{ ucfirst(request('kondisi')) }} @endif
    </div>
</div>

{{-- Tabel --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Daftar Inventaris</span>
        <small class="text-muted">{{ $asets->count() }} aset ditemukan</small>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle mb-0" id="tblLaporan">
            <thead class="table-light">
                <tr>
                    <th style="width:30px">No</th>
                    <th>Kode Aset</th>
                    <th>Nama Aset</th>
                    <th class="d-none d-md-table-cell">Kategori</th>
                    <th>Departemen</th>
                    <th class="d-none d-lg-table-cell">Lokasi</th>
                    <th>Kondisi</th>
                    <th class="d-none d-md-table-cell text-end">Harga Beli</th>
                    <th class="d-none d-md-table-cell text-end">Nilai Skrg</th>
                    <th class="d-none d-lg-table-cell">Penanggung Jawab</th>
                </tr>
            </thead>
            <tbody>
                @forelse($asets as $i => $a)
                <tr>
                    <td class="text-center text-muted">{{ $i+1 }}</td>
                    <td><code style="font-size:.7rem">{{ $a->kode_aset }}</code></td>
                    <td>
                        <div class="fw-semibold" style="font-size:.82rem">{{ $a->nama }}</div>
                        <small class="text-muted">{{ $a->merek }}</small>
                    </td>
                    <td class="d-none d-md-table-cell small">{{ $a->kategori->nama ?? '-' }}</td>
                    <td class="small">{{ $a->departemen->nama ?? '-' }}</td>
                    <td class="d-none d-lg-table-cell small text-muted">{{ $a->lokasi }}</td>
                    <td>{!! $a->label_kondisi !!}</td>
                    <td class="d-none d-md-table-cell small text-end">Rp {{ number_format($a->harga_beli,0,',','.') }}</td>
                    <td class="d-none d-md-table-cell small text-end fw-semibold text-success">Rp {{ number_format($a->nilai_sekarang,0,',','.') }}</td>
                    <td class="d-none d-lg-table-cell small">{{ $a->penanggungJawab->nama ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center py-4 text-muted">Tidak ada data aset</td></tr>
                @endforelse
            </tbody>
            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="7" class="text-end small">Total Nilai Perolehan:</td>
                    <td class="text-end small text-primary">Rp {{ number_format($totalNilai,0,',','.') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Tanda tangan cetak --}}
<div class="print-only mt-5">
    <div class="row">
        <div class="col-4 text-center">
            <div style="font-size:.78rem">Mengetahui,</div>
            <div style="font-size:.78rem;font-weight:600">Kepala Sekolah</div>
            <div style="margin:50px 0 5px;border-bottom:1px solid #000"></div>
            <div style="font-size:.75rem">NIP. ________________</div>
        </div>
        <div class="col-4 text-center">
            <div style="font-size:.78rem">Kepala Tata Usaha</div>
            <div style="font-size:.78rem;font-weight:600">SMKN 11 Kota Tangerang</div>
            <div style="margin:50px 0 5px;border-bottom:1px solid #000"></div>
            <div style="font-size:.75rem">NIP. ________________</div>
        </div>
        <div class="col-4 text-center">
            <div style="font-size:.78rem">Tangerang, {{ now()->isoFormat('D MMMM Y') }}</div>
            <div style="font-size:.78rem;font-weight:600">Pengelola Aset</div>
            <div style="margin:50px 0 5px;border-bottom:1px solid #000"></div>
            <div style="font-size:.75rem">NIP. ________________</div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.print-only { display: none; }
@media print {
    .sidebar, .topbar, .no-print, .btn, .card-header .btn,
    .breadcrumb, nav, .alert { display: none !important; }
    .main-wrapper { margin: 0 !important; }
    body, .content { background: #fff !important; padding: 0 !important; font-size: 10px !important; }
    .card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
    .print-only { display: block !important; }
    .d-none { display: table-cell !important; }
    .table { font-size: 9px !important; }
}
</style>
@endpush
