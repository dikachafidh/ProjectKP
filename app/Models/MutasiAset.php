<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiAset extends Model
{
    protected $fillable = [
        'aset_id', 'dari_lokasi', 'ke_lokasi',
        'dari_departemen_id', 'ke_departemen_id',
        'dari_karyawan_id', 'ke_karyawan_id',
        'tanggal_mutasi', 'alasan', 'keterangan', 'disetujui_oleh'
    ];

    protected $casts = ['tanggal_mutasi' => 'date'];

    public function aset(): BelongsTo
    {
        return $this->belongsTo(Aset::class);
    }

    public function dariDepartemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'dari_departemen_id');
    }

    public function keDepartemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'ke_departemen_id');
    }

    public function dariKaryawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'dari_karyawan_id');
    }

    public function keKaryawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'ke_karyawan_id');
    }
}
