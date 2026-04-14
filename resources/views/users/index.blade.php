@extends('layouts.app')
@section('title','Manajemen User')
@section('breadcrumb') <li class="breadcrumb-item active">Manajemen User</li> @endsection

@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Manajemen User</h4>
        <small class="text-muted">Kelola akun pengguna sistem SMKN 11 Kota Tangerang</small>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-person-plus me-1"></i>Tambah User
    </button>
</div>

{{-- Ringkasan Role --}}
<div class="row g-3 mb-4">
    @foreach([
        ['admin',  'Administrator', '👑', 'bg-danger',   'Akses penuh — tambah, edit, hapus, cetak QR'],
        ['kepsek', 'Kepala Sekolah','🏫', 'bg-warning text-dark', 'Lihat semua data & laporan'],
        ['staff',  'Staff',         '👤', 'bg-secondary', 'Lihat semua data & laporan'],
    ] as [$role, $label, $icon, $cls, $desc])
    <div class="col-md-4">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="fs-3">{{ $icon }}</div>
                    <div>
                        <span class="badge {{ $cls }} mb-1">{{ $label }}</span>
                        <div class="fw-bold" style="font-size:.82rem">
                            {{ $users->where('role',$role)->count() }} akun aktif
                        </div>
                    </div>
                </div>
                <div style="font-size:.75rem;color:#64748b">{{ $desc }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Tabel User --}}
<div class="card">
    <div class="card-header">
        <i class="bi bi-people me-2 text-primary"></i>Daftar Pengguna Sistem
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr>
                <th>Nama Pengguna</th>
                <th>Username</th>
                <th>Jabatan</th>
                <th>Role & Akses</th>
                <th>Status</th>
                <th>Terakhir Login</th>
                <th class="text-center">Aksi</th>
            </tr></thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                                style="width:34px;height:34px;background:{{ $u->role==='admin' ? '#ef4444' : ($u->role==='kepsek' ? '#f59e0b' : '#64748b') }};font-size:.8rem">
                                {{ strtoupper(substr($u->nama,0,1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.85rem">{{ $u->nama }}</div>
                                <small class="text-muted">{{ $u->email ?: '—' }}</small>
                            </div>
                        </div>
                    </td>
                    <td><code style="font-size:.78rem">{{ $u->username }}</code></td>
                    <td style="font-size:.82rem">{{ $u->jabatan ?: '—' }}</td>
                    <td>
                        {!! $u->badge_role !!}
                        <div style="font-size:.68rem;color:#94a3b8;margin-top:2px">
                            @if($u->role==='admin') Tambah • Edit • Hapus • Cetak QR • Kelola User
                            @else Lihat Data & Laporan
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($u->aktif)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td style="font-size:.78rem;color:#64748b">
                        {{ $u->last_login ? $u->last_login->diffForHumans() : 'Belum pernah' }}
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <button class="btn btn-sm btn-warning text-white" onclick='editUser(@json($u))' title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $u) }}"
                                onsubmit="return confirm('Hapus user {{ addslashes($u->nama) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @else
                            <span class="btn btn-sm btn-outline-secondary disabled" title="Akun aktif Anda">
                                <i class="bi bi-person-check"></i>
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada user</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- INFO HAK AKSES --}}
<div class="card mt-3" style="border-left:4px solid #0f4c75">
    <div class="card-body py-2">
        <div class="fw-bold small mb-1"><i class="bi bi-shield-check me-1 text-primary"></i>Keterangan Hak Akses</div>
        <div class="row g-2" style="font-size:.78rem">
            <div class="col-md-4">
                <span class="badge bg-danger me-1">👑 Admin</span>
                Tambah/edit/hapus aset, mutasi, pemeliharaan, cetak QR, kelola user & master data
            </div>
            <div class="col-md-4">
                <span class="badge bg-warning text-dark me-1">🏫 Kepala Sekolah</span>
                Lihat semua data, dashboard, laporan inventaris, notifikasi — tanpa bisa mengubah
            </div>
            <div class="col-md-4">
                <span class="badge bg-secondary me-1">👤 Staff</span>
                Lihat semua data, dashboard, laporan inventaris, notifikasi — tanpa bisa mengubah
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah User --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" placeholder="Contoh: Drs. Budi Santoso" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Kepala Sekolah">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" placeholder="Contoh: budi.santoso" required autocomplete="off">
                            <small class="text-muted">Digunakan untuk login. Huruf kecil, tanpa spasi.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@smkn11tangerang.sch.id">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Role / Hak Akses <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">👑 Administrator</option>
                                <option value="kepsek">🏫 Kepala Sekolah</option>
                                <option value="staff">👤 Staff</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required autocomplete="new-password">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit User --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formEdit">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-gear me-2"></i>Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="eNama" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" id="eJabatan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="eUsername" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="eEmail" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" id="eRole" class="form-select" required>
                                <option value="admin">👑 Administrator</option>
                                <option value="kepsek">🏫 Kepala Sekolah</option>
                                <option value="staff">👤 Staff</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="aktif" id="eAktif" class="form-select">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-info py-2 mb-0" style="font-size:.8rem">
                                <i class="bi bi-info-circle me-1"></i>
                                Kosongkan kolom password jika tidak ingin mengubah password.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah" autocomplete="new-password">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editUser(u) {
    document.getElementById('formEdit').action = '/users/' + u.id;
    document.getElementById('eNama').value     = u.nama;
    document.getElementById('eJabatan').value  = u.jabatan || '';
    document.getElementById('eUsername').value = u.username;
    document.getElementById('eEmail').value    = u.email || '';
    document.getElementById('eRole').value     = u.role;
    document.getElementById('eAktif').value    = u.aktif ? '1' : '0';
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
</script>
@endpush
