<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemeliharaan extends Model
{
    protected $fillable = [
        'aset_id', 'jenis', 'tanggal_jadwal', 'tanggal_selesai',
        'biaya', 'teknisi', 'status', 'deskripsi', 'hasil'
    ];

    protected $casts = [
        'tanggal_jadwal'  => 'date',
        'tanggal_selesai' => 'date',
        'biaya'           => 'decimal:2',
    ];

    public function aset(): BelongsTo
    {
        return $this->belongsTo(Aset::class);
    }

    public function getLabelStatusAttribute(): string
    {
        return match($this->status) {
            'terjadwal'     => '<span class="badge bg-info">Terjadwal</span>',
            'dalam_proses'  => '<span class="badge bg-warning text-dark">Dalam Proses</span>',
            'selesai'       => '<span class="badge bg-success">Selesai</span>',
            'dibatalkan'    => '<span class="badge bg-danger">Dibatalkan</span>',
            default         => $this->status,
        };
    }

    public function getLabelJenisAttribute(): string
    {
        return match($this->jenis) {
            'rutin'                  => 'Rutin',
            'perbaikan'              => 'Perbaikan',
            'penggantian_komponen'   => 'Penggantian Komponen',
            default                  => $this->jenis,
        };
    }
}
