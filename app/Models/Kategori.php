<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $fillable = ['nama', 'jenis', 'keterangan'];

    public function asets(): HasMany
    {
        return $this->hasMany(Aset::class);
    }

    public function getLabelJenisAttribute(): string
    {
        return match($this->jenis) {
            'elektronik' => 'Elektronik',
            'furniture'  => 'Furniture',
            'kendaraan'  => 'Kendaraan',
            default      => 'Lainnya',
        };
    }
}
