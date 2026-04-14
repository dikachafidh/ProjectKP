@extends('layouts.app')
@section('title', 'Data Karyawan')
@section('breadcrumb') <li class="breadcrumb-item active">Karyawan</li> @endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Data Karyawan</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah" @if(auth()->user()->isViewOnly()) style="display:none" @endif>
        <i class="bi bi-plus-circle me-1"></i> Tambah Karyawan
    </button>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr>
                <th>NIK</th><th>Nama</th><th>Jabatan</th><th>Departemen</th>
                <th>Kontak</th><th class="text-center">Aset</th><th class="text-center">Aksi</th>
            </tr></thead>
            <tbody>
                @forelse($karyawans as $k)
                <tr>
                    <td><code class="small">{{ $k->nik }}</code></td>
                    <td class="fw-semibold">{{ $k->nama }}</td>
                    <td class="small text-muted">{{ $k->jabatan }}</td>
                    <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $k->departemen->nama ?? '-' }}</span></td>
                    <td class="small">
                        @if($k->email)<div><i class="bi bi-envelope me-1"></i>{{ $k->email }}</div>@endif
                        @if($k->telepon)<div><i class="bi bi-telephone me-1"></i>{{ $k->telepon }}</div>@endif
                        @if(!$k->email && !$k->telepon)<span class="text-muted">-</span>@endif
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $k->asets_count > 0 ? 'success' : 'secondary' }} rounded-pill">{{ $k->asets_count }}</span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <button class="btn btn-sm btn-warning text-white" onclick='editKaryawan(@json($k))'>
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" action="{{ route('karyawan.destroy', $k) }}"
                                onsubmit="return confirm('Hapus karyawan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">
                    <i class="bi bi-people display-6 d-block mb-2"></i>Belum ada data karyawan
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $karyawans->links() }}</div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('karyawan.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">NIK <span class="text-danger">*</span></label>
                            <input type="text" name="nik" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" name="jabatan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Departemen <span class="text-danger">*</span></label>
                            <select name="departemen_id" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                @foreach($departemens as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">No. Telepon</label>
                            <input type="text" name="telepon" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formEdit">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">NIK</label>
                            <input type="text" name="nik" id="eNik" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Nama Lengkap</label>
                            <input type="text" name="nama" id="eNama" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jabatan</label>
                            <input type="text" name="jabatan" id="eJabatan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Departemen</label>
                            <select name="departemen_id" id="eDeptId" class="form-select" required>
                                @foreach($departemens as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" id="eEmail" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">No. Telepon</label>
                            <input type="text" name="telepon" id="eTelepon" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editKaryawan(d) {
    document.getElementById('formEdit').action = '/karyawan/' + d.id;
    document.getElementById('eNik').value      = d.nik;
    document.getElementById('eNama').value     = d.nama;
    document.getElementById('eJabatan').value  = d.jabatan;
    document.getElementById('eDeptId').value   = d.departemen_id;
    document.getElementById('eEmail').value    = d.email || '';
    document.getElementById('eTelepon').value  = d.telepon || '';
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
</script>
@endpush
