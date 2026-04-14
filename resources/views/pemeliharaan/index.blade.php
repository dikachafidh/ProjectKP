@extends('layouts.app')
@section('title','Pemeliharaan Aset')
@section('breadcrumb') <li class="breadcrumb-item active">Pemeliharaan</li> @endsection

@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Manajemen Pemeliharaan</h4>
        <small class="text-muted">Jadwal servis & riwayat perbaikan aset SMKN 11 Tangerang</small>
    </div>
    <a href="{{ route('pemeliharaan.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Jadwal Baru
    </a>
</div>

{{-- Filter Status --}}
<div class="mb-3 d-flex gap-2 flex-wrap">
    @foreach(['' => 'Semua', 'terjadwal' => 'Terjadwal', 'dalam_proses' => 'Dalam Proses', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $val => $label)
    <a href="{{ route('pemeliharaan.index', $val ? ['status'=>$val] : []) }}"
        class="btn btn-sm {{ request('status') === $val ? 'btn-primary' : 'btn-outline-secondary' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Desktop Table --}}
<div class="card d-none d-lg-block">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr>
                <th>Jadwal</th><th>Aset</th><th>Jenis</th>
                <th>Teknisi</th><th>Biaya</th><th>Status</th>
                <th class="text-center">Aksi</th>
            </tr></thead>
            <tbody>
                @forelse($pemeliharaans as $p)
                <tr>
                    <td>
                        <div class="fw-semibold" style="font-size:.82rem">{{ $p->tanggal_jadwal->format('d/m/Y') }}</div>
                        @if($p->tanggal_jadwal->isPast() && $p->status === 'terjadwal')
                            <span class="badge bg-danger" style="font-size:.62rem">Terlambat!</span>
                        @elseif($p->tanggal_jadwal->isToday())
                            <span class="badge bg-warning text-dark" style="font-size:.62rem">Hari Ini</span>
                        @elseif($p->tanggal_jadwal->isTomorrow())
                            <span class="badge bg-info text-white" style="font-size:.62rem">Besok</span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:.82rem">{{ $p->aset->nama ?? '-' }}</div>
                        <code style="font-size:.7rem;color:#94a3b8">{{ $p->aset->kode_aset ?? '' }}</code>
                    </td>
                    <td><span class="badge bg-secondary bg-opacity-15 text-dark">{{ $p->label_jenis }}</span></td>
                    <td style="font-size:.82rem">{{ $p->teknisi ?: '-' }}</td>
                    <td style="font-size:.82rem">{{ $p->biaya > 0 ? 'Rp '.number_format($p->biaya,0,',','.') : '—' }}</td>
                    <td>{!! $p->label_status !!}</td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('pemeliharaan.show', $p) }}" class="btn btn-sm btn-info text-white" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('pemeliharaan.edit', $p) }}" class="btn btn-sm btn-warning text-white" title="Update Status">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('pemeliharaan.destroy', $p) }}"
                                onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">
                    <i class="bi bi-wrench display-5 d-block mb-2 opacity-25"></i>Belum ada jadwal pemeliharaan
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $pemeliharaans->links() }}</div>
</div>

{{-- Mobile Cards --}}
<div class="d-lg-none">
    @forelse($pemeliharaans as $p)
    <div class="card mb-2 {{ $p->tanggal_jadwal->isToday() ? 'border-warning' : '' }}">
        <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="fw-bold" style="font-size:.85rem">{{ $p->aset->nama ?? '-' }}</div>
                    <div class="d-flex flex-wrap gap-1 mt-1">
                        <span class="badge bg-secondary bg-opacity-15 text-dark" style="font-size:.65rem">{{ $p->label_jenis }}</span>
                        {!! $p->label_status !!}
                        @if($p->tanggal_jadwal->isToday())
                        <span class="badge bg-warning text-dark" style="font-size:.62rem">Hari Ini!</span>
                        @elseif($p->tanggal_jadwal->isPast() && $p->status === 'terjadwal')
                        <span class="badge bg-danger" style="font-size:.62rem">Terlambat</span>
                        @endif
                    </div>
                    <div class="mt-1" style="font-size:.75rem;color:#64748b">
                        <i class="bi bi-calendar3 me-1"></i>{{ $p->tanggal_jadwal->format('d M Y') }}
                        @if($p->teknisi) &bull; {{ Str::limit($p->teknisi,20) }} @endif
                    </div>
                </div>
                <div class="d-flex gap-1 ms-2">
                    <a href="{{ route('pemeliharaan.show', $p) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('pemeliharaan.edit', $p) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil"></i></a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="card"><div class="card-body text-center py-5 text-muted">
        <i class="bi bi-wrench display-5 d-block mb-2 opacity-25"></i>Belum ada jadwal pemeliharaan
    </div></div>
    @endforelse
    <div class="mt-2">{{ $pemeliharaans->links() }}</div>
</div>
@endsection
