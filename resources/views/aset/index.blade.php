@extends('layouts.app')
@section('title','Data Aset')
@section('breadcrumb') <li class="breadcrumb-item active">Data Aset</li> @endsection

@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Data Aset Sekolah</h4>
        <small class="text-muted">SMKN 11 Kota Tangerang &mdash; {{ $asets->total() }} aset terdaftar</small>
    </div>
    {{-- FITUR 1: Tambah Aset — hanya Admin --}}
    @if(auth()->user()->canCreate())
    <a href="{{ route('aset.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Tambah Aset
    </a>
    @else
    <span class="badge bg-warning text-dark"><i class="bi bi-eye me-1"></i>Mode Lihat Saja</span>
    @endif
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('aset.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="cari" class="form-control" placeholder="Cari nama, kode, merek..." value="{{ request('cari') }}">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <select name="kondisi" class="form-select form-select-sm">
                    <option value="">Semua Kondisi</option>
                    @foreach(['baik'=>'✅ Baik','rusak'=>'⚠️ Rusak','hilang'=>'❓ Hilang','tidak_aktif'=>'🔒 Tdk Aktif'] as $v=>$l)
                    <option value="{{ $v }}" {{ request('kondisi')==$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="kategori_id" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id')==$k->id?'selected':'' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="departemen_id" class="form-select form-select-sm">
                    <option value="">Semua Departemen</option>
                    @foreach($departemens as $d)
                    <option value="{{ $d->id }}" {{ request('departemen_id')==$d->id?'selected':'' }}>{{ $d->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-funnel"></i> Filter</button>
                <a href="{{ route('aset.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

{{-- Desktop Table --}}
<div class="card d-none d-lg-block">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr>
                <th>Kode Aset</th><th>Nama Aset</th><th>Kategori</th>
                <th>Departemen</th><th>Kondisi</th><th>Harga Beli</th>
                <th>Garansi</th><th class="text-center">Aksi</th>
            </tr></thead>
            <tbody>
                @forelse($asets as $a)
                <tr>
                    <td><code style="font-size:.7rem;background:#f1f5f9;padding:2px 5px;border-radius:4px">{{ $a->kode_aset }}</code></td>
                    <td>
                        <div class="fw-semibold" style="font-size:.83rem">{{ $a->nama }}</div>
                        <small class="text-muted">{{ $a->merek }}</small>
                    </td>
                    <td><span class="badge bg-secondary bg-opacity-15 text-dark">{{ $a->kategori->nama ?? '-' }}</span></td>
                    <td style="font-size:.8rem">{{ $a->departemen->nama ?? '-' }}</td>
                    <td>{!! $a->label_kondisi !!}</td>
                    <td style="font-size:.8rem">Rp {{ number_format($a->harga_beli,0,',','.') }}</td>
                    <td style="font-size:.78rem">
                        @if($a->masa_garansi)
                            @if($a->garansi_habis)
                                <span class="text-danger"><i class="bi bi-x-circle-fill"></i> Habis</span>
                            @else
                                <span class="text-success"><i class="bi bi-check-circle-fill"></i> {{ $a->masa_garansi->format('d/m/Y') }}</span>
                            @endif
                        @else <span class="text-muted">—</span> @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            {{-- Lihat detail — semua role bisa --}}
                            <a href="{{ route('aset.show', $a) }}" class="btn btn-sm btn-info text-white" title="Detail"><i class="bi bi-eye"></i></a>

                            {{-- Edit — hanya Admin --}}
                            @if(auth()->user()->canEdit())
                            <a href="{{ route('aset.edit', $a) }}" class="btn btn-sm btn-warning text-white" title="Edit"><i class="bi bi-pencil"></i></a>
                            @endif

                            {{-- Cetak QR — hanya Admin --}}
                            @if(auth()->user()->canPrint())
                            <a href="{{ route('aset.qrcode', $a) }}" class="btn btn-sm btn-secondary" title="Cetak QR"><i class="bi bi-qr-code"></i></a>
                            @endif

                            {{-- Hapus — hanya Admin --}}
                            @if(auth()->user()->canDelete())
                            <form method="POST" action="{{ route('aset.destroy', $a) }}" onsubmit="return confirm('Hapus aset {{ addslashes($a->nama) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">
                    <i class="bi bi-inbox display-5 d-block mb-2 opacity-25"></i>Tidak ada data aset
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <small class="text-muted">Menampilkan {{ $asets->firstItem() }}–{{ $asets->lastItem() }} dari {{ $asets->total() }} aset</small>
        {{ $asets->links() }}
    </div>
</div>

{{-- Mobile Cards --}}
<div class="d-lg-none">
    @forelse($asets as $a)
    <div class="card mb-2">
        <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="fw-bold" style="font-size:.86rem">{{ $a->nama }}</div>
                    <div class="d-flex flex-wrap gap-1 mt-1 align-items-center">
                        <code style="font-size:.66rem;background:#f1f5f9;padding:1px 4px;border-radius:4px">{{ $a->kode_aset }}</code>
                        {!! $a->label_kondisi !!}
                    </div>
                    <div class="mt-1" style="font-size:.75rem;color:#64748b">
                        <i class="bi bi-building me-1"></i>{{ $a->departemen->nama ?? '-' }}
                        &bull; Rp {{ number_format($a->harga_beli/1000000,1,',','.') }}jt
                    </div>
                </div>
                <div class="d-flex gap-1 ms-2 flex-shrink-0">
                    <a href="{{ route('aset.show', $a) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                    @if(auth()->user()->canEdit())
                    <a href="{{ route('aset.edit', $a) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="card"><div class="card-body text-center py-5 text-muted">
        <i class="bi bi-inbox display-5 d-block mb-2 opacity-25"></i>Tidak ada data aset
    </div></div>
    @endforelse
    <div class="mt-2">{{ $asets->links() }}</div>
</div>
@endsection
