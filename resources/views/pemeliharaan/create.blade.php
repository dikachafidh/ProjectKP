@extends('layouts.app')
@section('title', 'Jadwal Pemeliharaan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pemeliharaan.index') }}" class="text-decoration-none">Pemeliharaan</a></li>
    <li class="breadcrumb-item active">Jadwal Baru</li>
@endsection

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0">Tambah Jadwal Pemeliharaan</h4>
    <small class="text-muted">Buat jadwal maintenance rutin atau perbaikan aset</small>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <form method="POST" action="{{ route('pemeliharaan.store') }}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Aset <span class="text-danger">*</span></label>
                            <select name="aset_id" class="form-select" required>
                                <option value="">-- Pilih Aset --</option>
                                @foreach($asets as $a)
                                <option value="{{ $a->id }}"
                                    {{ (old('aset_id') == $a->id || request('aset') == $a->id) ? 'selected' : '' }}>
                                    [{{ $a->kode_aset }}] {{ $a->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jenis Pemeliharaan <span class="text-danger">*</span></label>
                            <select name="jenis" class="form-select" required>
                                <option value="rutin" {{ old('jenis')=='rutin'?'selected':'' }}>Rutin</option>
                                <option value="perbaikan" {{ old('jenis')=='perbaikan'?'selected':'' }}>Perbaikan</option>
                                <option value="penggantian_komponen" {{ old('jenis')=='penggantian_komponen'?'selected':'' }}>Penggantian Komponen</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tanggal Jadwal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_jadwal" class="form-control" value="{{ old('tanggal_jadwal', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Teknisi / Vendor</label>
                            <input type="text" name="teknisi" class="form-control" value="{{ old('teknisi') }}" placeholder="Nama teknisi atau vendor">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Estimasi Biaya (Rp)</label>
                            <input type="number" name="biaya" class="form-control" value="{{ old('biaya', 0) }}" min="0" step="1000">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" class="form-control" rows="3" required
                                placeholder="Jelaskan pekerjaan pemeliharaan yang akan dilakukan...">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-calendar-plus me-1"></i> Simpan Jadwal
                    </button>
                    <a href="{{ route('pemeliharaan.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
