@extends('layouts.app')
@section('title', 'Tambah Aset')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('aset.index') }}" class="text-decoration-none">Data Aset</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0">Tambah Aset Baru</h4>
    <small class="text-muted">Isi form berikut untuk mendaftarkan aset baru</small>
</div>

<form method="POST" action="{{ route('aset.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <!-- Info Dasar -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Informasi Dasar</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Nama Aset <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Merek</label>
                            <input type="text" name="merek" class="form-control" value="{{ old('merek') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Kondisi <span class="text-danger">*</span></label>
                            <select name="kondisi" class="form-select" required>
                                <option value="baik" {{ old('kondisi','baik')=='baik'?'selected':'' }}>Baik</option>
                                <option value="rusak" {{ old('kondisi')=='rusak'?'selected':'' }}>Rusak</option>
                                <option value="tidak_aktif" {{ old('kondisi')=='tidak_aktif'?'selected':'' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi') }}" placeholder="Gedung A - Lantai 2 - Ruang 201" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Pembelian -->
            <div class="card mt-3">
                <div class="card-header">Informasi Pembelian & Garansi</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Harga Beli (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_beli" class="form-control" value="{{ old('harga_beli',0) }}" min="0" step="1000" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Tanggal Beli <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_beli" class="form-control" value="{{ old('tanggal_beli') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Masa Garansi Hingga</label>
                            <input type="date" name="masa_garansi" class="form-control" value="{{ old('masa_garansi') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Depresiasi -->
            <div class="card mt-3">
                <div class="card-header">Pengaturan Depresiasi</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Metode Depresiasi <span class="text-danger">*</span></label>
                            <select name="metode_depresiasi" class="form-select" required>
                                <option value="garis_lurus" {{ old('metode_depresiasi','garis_lurus')=='garis_lurus'?'selected':'' }}>Garis Lurus</option>
                                <option value="saldo_menurun" {{ old('metode_depresiasi')=='saldo_menurun'?'selected':'' }}>Saldo Menurun</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Umur Ekonomis (Tahun) <span class="text-danger">*</span></label>
                            <input type="number" name="umur_ekonomis" class="form-control" value="{{ old('umur_ekonomis',5) }}" min="1" max="50" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Nilai Sisa (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="nilai_sisa" class="form-control" value="{{ old('nilai_sisa',0) }}" min="0" step="1000" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar kanan -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Klasifikasi & Penanggung Jawab</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id')==$k->id?'selected':'' }}>
                                {{ $k->nama }} ({{ $k->label_jenis }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Departemen <span class="text-danger">*</span></label>
                        <select name="departemen_id" class="form-select" required>
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departemens as $d)
                            <option value="{{ $d->id }}" {{ old('departemen_id')==$d->id?'selected':'' }}>{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Penanggung Jawab</label>
                        <select name="penanggung_jawab_id" class="form-select">
                            <option value="">-- Tidak Ada --</option>
                            @foreach($karyawans as $k)
                            <option value="{{ $k->id }}" {{ old('penanggung_jawab_id')==$k->id?'selected':'' }}>
                                {{ $k->nama }} - {{ $k->departemen->nama ?? '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label fw-semibold small">Foto Aset</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <small class="text-muted">Maks. 2MB, format JPG/PNG</small>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Aset
                </button>
                <a href="{{ route('aset.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </div>
    </div>
</form>
@endsection
