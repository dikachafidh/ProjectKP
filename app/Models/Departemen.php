<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departemen extends Model
{
    protected $fillable = ['nama', 'kode', 'lokasi', 'kepala'];

    public function asets(): HasMany
    {
        return $this->hasMany(Aset::class);
    }

    public function karyawans(): HasMany
    {
        return $this->hasMany(Karyawan::class);
    }
}
