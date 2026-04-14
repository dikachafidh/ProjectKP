@extends('layouts.app')
@section('title', 'Catat Mutasi Aset')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('mutasi.index') }}" class="text-decoration-none">Mutasi Aset</a></li>
    <li class="breadcrumb-item active">Catat Baru</li>
@endsection

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0">Catat Mutasi Aset</h4>
    <small class="text-muted">Perpindahan aset antar departemen, lokasi, atau karyawan</small>
</div>

<form method="POST" action="{{ route('mutasi.store') }}">
    @csrf
    <div class="row g-3">
        <div class="col-md-8">
            <!-- Pilih Aset -->
            <div class="card mb-3">
                <div class="card-header">Pilih Aset</div>
                <div class="card-body">
                    <label class="form-label small fw-semibold">Aset yang Dimutasi <span class="text-danger">*</span></label>
                    <select name="aset_id" class="form-select" id="selectAset" required>
                        <option value="">-- Pilih Aset --</option>
                        @foreach($asets as $a)
                        <option value="{{ $a->id }}"
                            data-lokasi="{{ $a->lokasi }}"
                            data-dept="{{ $a->departemen_id }}"
                            data-pj="{{ $a->penanggung_jawab_id }}"
                            {{ request('aset') == $a->id ? 'selected' : '' }}>
                            [{{ $a->kode_aset }}] {{ $a->nama }} — {{ $a->departemen->nama ?? '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Dari -->
            <div class="card mb-3">
                <div class="card-header">Asal (Dari)</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Dari Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="dari_lokasi" id="dariLokasi" class="form-control" required placeholder="Otomatis terisi saat pilih aset">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Dari Departemen</label>
                            <select name="dari_departemen_id" id="dariDeptId" class="form-select">
                                <option value="">-- Pilih --</option>
                                @foreach($departemens as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Dari Karyawan (PJ Lama)</label>
                            <select name="dari_karyawan_id" id="dariKaryawanId" class="form-select">
                                <option value="">-- Pilih --</option>
                                @foreach($karyawans as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }} ({{ $k->departemen->nama ?? '' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ke -->
            <div class="card mb-3">
                <div class="card-header">Tujuan (Ke)</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Ke Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="ke_lokasi" class="form-control" required placeholder="Gedung B - Lantai 1 - R. 101">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Ke Departemen</label>
                            <select name="ke_departemen_id" class="form-select">
                                <option value="">-- Pilih --</option>
                                @foreach($departemens as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Ke Karyawan (PJ Baru)</label>
                            <select name="ke_karyawan_id" class="form-select">
                                <option value="">-- Pilih --</option>
                                @foreach($karyawans as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }} ({{ $k->departemen->nama ?? '' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Informasi Mutasi</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tanggal Mutasi <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_mutasi" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alasan Mutasi <span class="text-danger">*</span></label>
                        <input type="text" name="alasan" class="form-control" required placeholder="Rotasi jabatan, perbaikan kantor...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Disetujui Oleh</label>
                        <input type="text" name="disetujui_oleh" class="form-control" placeholder="Nama manajer / HRD">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
            </div>
            <div class="d-grid gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Mutasi
                </button>
                <a href="{{ route('mutasi.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('selectAset').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    document.getElementById('dariLokasi').value    = opt.dataset.lokasi || '';
    document.getElementById('dariDeptId').value    = opt.dataset.dept || '';
    document.getElementById('dariKaryawanId').value= opt.dataset.pj || '';
});
// Pre-select jika dari URL
if (document.getElementById('selectAset').value) {
    document.getElementById('selectAset').dispatchEvent(new Event('change'));
}
</script>
@endpush
