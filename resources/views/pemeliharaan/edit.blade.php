@extends('layouts.app')
@section('title', 'Update Pemeliharaan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pemeliharaan.index') }}" class="text-decoration-none">Pemeliharaan</a></li>
    <li class="breadcrumb-item active">Update Status</li>
@endsection

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0">Update Pemeliharaan</h4>
    <small class="text-muted">Aset: <strong>{{ $pemeliharaan->aset->nama ?? '-' }}</strong></small>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <form method="POST" action="{{ route('pemeliharaan.update', $pemeliharaan) }}">
            @csrf @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jenis</label>
                            <select name="jenis" class="form-select" required>
                                <option value="rutin" {{ old('jenis',$pemeliharaan->jenis)=='rutin'?'selected':'' }}>Rutin</option>
                                <option value="perbaikan" {{ old('jenis',$pemeliharaan->jenis)=='perbaikan'?'selected':'' }}>Perbaikan</option>
                                <option value="penggantian_komponen" {{ old('jenis',$pemeliharaan->jenis)=='penggantian_komponen'?'selected':'' }}>Penggantian Komponen</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="terjadwal"    {{ old('status',$pemeliharaan->status)=='terjadwal'?'selected':'' }}>Terjadwal</option>
                                <option value="dalam_proses" {{ old('status',$pemeliharaan->status)=='dalam_proses'?'selected':'' }}>Dalam Proses</option>
                                <option value="selesai"      {{ old('status',$pemeliharaan->status)=='selesai'?'selected':'' }}>Selesai</option>
                                <option value="dibatalkan"   {{ old('status',$pemeliharaan->status)=='dibatalkan'?'selected':'' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tanggal Jadwal</label>
                            <input type="date" name="tanggal_jadwal" class="form-control" value="{{ old('tanggal_jadwal', $pemeliharaan->tanggal_jadwal?->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $pemeliharaan->tanggal_selesai?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Teknisi / Vendor</label>
                            <input type="text" name="teknisi" class="form-control" value="{{ old('teknisi', $pemeliharaan->teknisi) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Biaya Aktual (Rp)</label>
                            <input type="number" name="biaya" class="form-control" value="{{ old('biaya', $pemeliharaan->biaya) }}" min="0" step="1000">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Deskripsi Pekerjaan</label>
                            <textarea name="deskripsi" class="form-control" rows="2" required>{{ old('deskripsi', $pemeliharaan->deskripsi) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Hasil Pemeliharaan</label>
                            <textarea name="hasil" class="form-control" rows="3"
                                placeholder="Catatan hasil perbaikan, komponen yang diganti, kondisi setelah servis...">{{ old('hasil', $pemeliharaan->hasil) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('pemeliharaan.show', $pemeliharaan) }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
