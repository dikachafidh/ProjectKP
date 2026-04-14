@extends('layouts.app')
@section('title', 'Departemen')
@section('breadcrumb') <li class="breadcrumb-item active">Departemen</li> @endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Departemen</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah" @if(auth()->user()->isViewOnly()) style="display:none" @endif>
        <i class="bi bi-plus-circle me-1"></i> Tambah Departemen
    </button>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr>
                <th>Kode</th><th>Nama Departemen</th><th>Lokasi</th><th>Kepala</th>
                <th class="text-center">Aset</th><th class="text-center">Karyawan</th><th class="text-center">Aksi</th>
            </tr></thead>
            <tbody>
                @forelse($departemens as $d)
                <tr>
                    <td><code>{{ $d->kode }}</code></td>
                    <td class="fw-semibold">{{ $d->nama }}</td>
                    <td class="text-muted small">{{ $d->lokasi ?: '-' }}</td>
                    <td class="small">{{ $d->kepala ?: '-' }}</td>
                    <td class="text-center"><span class="badge bg-primary">{{ $d->asets_count }}</span></td>
                    <td class="text-center"><span class="badge bg-info">{{ $d->karyawans_count }}</span></td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <button class="btn btn-sm btn-warning text-white" onclick="editDept({{ $d }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" action="{{ route('departemen.destroy', $d) }}"
                                onsubmit="return confirm('Hapus departemen?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada departemen</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('departemen.store') }}">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah Departemen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-8">
                            <label class="form-label small fw-semibold">Nama Departemen</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Kode</label>
                            <input type="text" name="kode" class="form-control" placeholder="IT, HR..." required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Kepala Departemen</label>
                            <input type="text" name="kepala" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="formEdit">
                @csrf @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Edit Departemen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-8">
                            <label class="form-label small fw-semibold">Nama Departemen</label>
                            <input type="text" name="nama" id="eNama" class="form-control" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold">Kode</label>
                            <input type="text" name="kode" id="eKode" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Lokasi</label>
                            <input type="text" name="lokasi" id="eLokasi" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Kepala</label>
                            <input type="text" name="kepala" id="eKepala" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editDept(d) {
    document.getElementById('formEdit').action = '/departemen/' + d.id;
    document.getElementById('eNama').value   = d.nama;
    document.getElementById('eKode').value   = d.kode;
    document.getElementById('eLokasi').value = d.lokasi || '';
    document.getElementById('eKepala').value = d.kepala || '';
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
</script>
@endpush
