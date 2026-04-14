@extends('layouts.app')
@section('title','Mutasi Aset')
@section('breadcrumb') <li class="breadcrumb-item active">Mutasi Aset</li> @endsection

@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Tracking Mutasi Aset</h4>
        <small class="text-muted">Riwayat perpindahan aset SMKN 11 Kota Tangerang</small>
    </div>
    <a href="{{ route('mutasi.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Catat Mutasi
    </a>
</div>

{{-- Desktop --}}
<div class="card d-none d-md-block">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr>
                <th>Tanggal</th><th>Aset</th><th>Dari</th>
                <th>Ke</th><th>Alasan</th><th>Disetujui</th>
                <th class="text-center">Detail</th>
            </tr></thead>
            <tbody>
                @forelse($mutasis as $m)
                <tr>
                    <td style="font-size:.8rem">{{ $m->tanggal_mutasi->format('d/m/Y') }}</td>
                    <td>
                        <div class="fw-semibold" style="font-size:.82rem">{{ $m->aset->nama ?? '-' }}</div>
                        <code style="font-size:.7rem;color:#94a3b8">{{ $m->aset->kode_aset ?? '' }}</code>
                    </td>
                    <td style="font-size:.8rem">
                        <div>{{ Str::limit($m->dari_lokasi,20) }}</div>
                        @if($m->dariDepartemen)
                        <span class="badge bg-secondary bg-opacity-15 text-dark" style="font-size:.65rem">{{ $m->dariDepartemen->nama }}</span>
                        @endif
                    </td>
                    <td style="font-size:.8rem">
                        <div class="fw-semibold">{{ Str::limit($m->ke_lokasi,20) }}</div>
                        @if($m->keDepartemen)
                        <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.65rem">{{ $m->keDepartemen->nama }}</span>
                        @endif
                    </td>
                    <td style="font-size:.8rem;color:#64748b">{{ Str::limit($m->alasan,35) }}</td>
                    <td style="font-size:.8rem">{{ $m->disetujui_oleh ?: '—' }}</td>
                    <td class="text-center">
                        <a href="{{ route('mutasi.show', $m) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">
                    <i class="bi bi-arrow-left-right display-5 d-block mb-2 opacity-25"></i>Belum ada riwayat mutasi
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $mutasis->links() }}</div>
</div>

{{-- Mobile Cards --}}
<div class="d-md-none">
    @forelse($mutasis as $m)
    <div class="card mb-2">
        <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="fw-bold" style="font-size:.85rem">{{ $m->aset->nama ?? '-' }}</div>
                    <div class="d-flex align-items-center gap-1 mt-1" style="font-size:.75rem;color:#64748b">
                        <span>{{ Str::limit($m->dari_lokasi,15) }}</span>
                        <i class="bi bi-arrow-right text-primary"></i>
                        <span class="fw-semibold text-dark">{{ Str::limit($m->ke_lokasi,15) }}</span>
                    </div>
                    <div style="font-size:.72rem;color:#94a3b8;margin-top:2px">
                        {{ $m->tanggal_mutasi->format('d M Y') }} &bull; {{ Str::limit($m->alasan,30) }}
                    </div>
                </div>
                <a href="{{ route('mutasi.show', $m) }}" class="btn btn-sm btn-info text-white ms-2">
                    <i class="bi bi-eye"></i>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="card"><div class="card-body text-center py-5 text-muted">
        <i class="bi bi-arrow-left-right display-5 d-block mb-2 opacity-25"></i>Belum ada riwayat mutasi
    </div></div>
    @endforelse
    <div class="mt-2">{{ $mutasis->links() }}</div>
</div>
@endsection
