@extends('layouts.app')
@section('title', 'Edit Aset')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('aset.index') }}" class="text-decoration-none">Data Aset</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0">Edit Aset</h4>
    <small class="text-muted">Kode: <code>{{ $aset->kode_aset }}</code></small>
</div>

<form method="POST" action="{{ route('aset.update', $aset) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Informasi Dasar</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Nama Aset <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $aset->nama) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number', $aset->serial_number) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Kondisi <span class="text-danger">*</span></label>
                            <select name="kondisi" class="form-select" required>
                                @foreach(['baik'=>'Baik','rusak'=>'Rusak','hilang'=>'Hilang','tidak_aktif'=>'Tidak Aktif'] as $val => $label)
                                <option value="{{ $val }}" {{ old('kondisi',$aset->kondisi)==$val?'selected':'' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $aset->lokasi) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $aset->keterangan) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">Pembelian & Garansi</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Harga Beli (Rp)</label>
                            <input type="number" name="harga_beli" class="form-control" value="{{ old('harga_beli', $aset->harga_beli) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Tanggal Beli</label>
                            <input type="date" name="tanggal_beli" class="form-control" value="{{ old('tanggal_beli', $aset->tanggal_beli?->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Masa Garansi</label>
                            <input type="date" name="masa_garansi" class="form-control" value="{{ old('masa_garansi', $aset->masa_garansi?->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">Depresiasi</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Metode</label>
                            <select name="metode_depresiasi" class="form-select" required>
                                <option value="garis_lurus" {{ old('metode_depresiasi',$aset->metode_depresiasi)=='garis_lurus'?'selected':'' }}>Garis Lurus</option>
                                <option value="saldo_menurun" {{ old('metode_depresiasi',$aset->metode_depresiasi)=='saldo_menurun'?'selected':'' }}>Saldo Menurun</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Umur Ekonomis (Thn)</label>
                            <input type="number" name="umur_ekonomis" class="form-control" value="{{ old('umur_ekonomis', $aset->umur_ekonomis) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Nilai Sisa (Rp)</label>
                            <input type="number" name="nilai_sisa" class="form-control" value="{{ old('nilai_sisa', $aset->nilai_sisa) }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Klasifikasi</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Kategori</label>
                        <select name="kategori_id" class="form-select" required>
                            @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id',$aset->kategori_id)==$k->id?'selected':'' }}>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Divisi</label>
                        <select name="departemen_id" class="form-select" required>
                            @foreach($departemens as $d)
                            <option value="{{ $d->id }}" {{ old('departemen_id',$aset->departemen_id)==$d->id?'selected':'' }}>{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="mb-3">
                        <label class="form-label fw-semibold small">Penanggung Jawab</label>
                        <select name="penanggung_jawab_id" class="form-select">
                            <option value="">-- Tidak Ada --</option>
                            @foreach($karyawans as $k)
                            <option value="{{ $k->id }}" {{ old('penanggung_jawab_id',$aset->penanggung_jawab_id)==$k->id?'selected':'' }}>
                                {{ $k->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div>
                        <label class="form-label fw-semibold small">Foto Aset</label>
                        @if($aset->foto)
                            <img src="{{ asset('storage/'.$aset->foto) }}" class="img-fluid rounded mb-2" style="max-height:100px">
                        @endif
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                <a href="{{ route('aset.show', $aset) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </div>
    </div>
</form>
@endsection
