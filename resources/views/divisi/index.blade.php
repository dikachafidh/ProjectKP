@extends('layouts.app')
@section('title','Divisi')
@section('breadcrumb') <li class="breadcrumb-item active">Divisi</li> @endsection

@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Data Divisi</h4>
        <small class="text-muted">Kelola divisi/departemen SMKN 11 Kota Tangerang</small>
    </div>
    @if(auth()->user()->isAdmin())
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle me-1"></i>Tambah Divisi
    </button>
    @endif
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr>
                <th>Kode</th><th>Nama Divisi</th><th>Lokasi</th><th>Kepala</th>
                <th class="text-center">Jumlah Aset</th>
                @if(auth()->user()->isAdmin())<th class="text-center">Aksi</th>@endif
            </tr></thead>
            <tbody>
                @forelse($divisis as $d)
                <tr>
                    <td><code style="font-size:.8rem">{{ $d->kode }}</code></td>
                    <td class="fw-semibold">{{ $d->nama }}</td>
                    <td style="font-size:.82rem;color:#64748b">{{ $d->lokasi ?: '—' }}</td>
                    <td style="font-size:.82rem">{{ $d->kepala ?: '—' }}</td>
                    <td class="text-center">
                        <span class="badge bg-primary">{{ $d->asets_count }}</span>
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <button class="btn btn-sm btn-warning text-white" onclick='editDivisi(@json($d))'>
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" action="{{ route('divisi.destroy', $d) }}" onsubmit="return confirm('Hapus divisi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="{{ auth()->user()->isAdmin() ? 6 : 5 }}" class="text-center py-4 text-muted">
                    Belum ada data divisi
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(auth()->user()->isAdmin())
{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('divisi.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Divisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-8">
                            <label class="form-label">Nama Divisi</label>
                            <input type="text" name="nama" class="form-control" required placeholder="IT & Teknologi">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Kode</label>
                            <input type="text" name="kode" class="form-control" required placeholder="IT">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" placeholder="Gedung A Lt.2">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Kepala Divisi</label>
                            <input type="text" name="kepala" class="form-control" placeholder="Nama Kepala">
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

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="formEdit">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Divisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-8">
                            <label class="form-label">Nama Divisi</label>
                            <input type="text" name="nama" id="eNama" class="form-control" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Kode</label>
                            <input type="text" name="kode" id="eKode" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" id="eLokasi" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Kepala</label>
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
@endif
@endsection

@push('scripts')
<script>
function editDivisi(d) {
    document.getElementById('formEdit').action = '/divisi/' + d.id;
    document.getElementById('eNama').value   = d.nama;
    document.getElementById('eKode').value   = d.kode;
    document.getElementById('eLokasi').value = d.lokasi || '';
    document.getElementById('eKepala').value = d.kepala || '';
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
</script>
@endpush
