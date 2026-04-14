@extends('layouts.app')
@section('title', 'Kategori Aset')
@section('breadcrumb') <li class="breadcrumb-item active">Kategori</li> @endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Kategori Aset</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah" @if(auth()->user()->isViewOnly()) style="display:none" @endif>
        <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
    </button>
</div>

<div class="row g-3">
    @foreach($kategoris as $k)
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-1">{{ $k->nama }}</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $k->label_jenis }}</span>
                        <div class="mt-2 small text-muted">{{ $k->keterangan }}</div>
                    </div>
                    <span class="badge bg-secondary rounded-pill fs-6">{{ $k->asets_count }}</span>
                </div>
                <hr class="my-2">
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-warning text-white" onclick="editKategori({{ $k }})">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <form method="POST" action="{{ route('kategori.destroy', $k) }}"
                        onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('kategori.store') }}">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kategori</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jenis</label>
                        <select name="jenis" class="form-select" required>
                            <option value="elektronik">Elektronik</option>
                            <option value="furniture">Furniture</option>
                            <option value="kendaraan">Kendaraan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2"></textarea>
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
                <div class="modal-header"><h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kategori</label>
                        <input type="text" name="nama" id="editNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jenis</label>
                        <select name="jenis" id="editJenis" class="form-select">
                            <option value="elektronik">Elektronik</option>
                            <option value="furniture">Furniture</option>
                            <option value="kendaraan">Kendaraan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Keterangan</label>
                        <textarea name="keterangan" id="editKeterangan" class="form-control" rows="2"></textarea>
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
function editKategori(data) {
    document.getElementById('formEdit').action = '/kategori/' + data.id;
    document.getElementById('editNama').value = data.nama;
    document.getElementById('editJenis').value = data.jenis;
    document.getElementById('editKeterangan').value = data.keterangan || '';
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
</script>
@endpush
