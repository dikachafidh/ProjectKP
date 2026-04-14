<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karyawan extends Model
{
    protected $fillable = ['nik', 'nama', 'jabatan', 'departemen_id', 'email', 'telepon'];

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class);
    }

    public function asets(): HasMany
    {
        return $this->hasMany(Aset::class, 'penanggung_jawab_id');
    }
}
