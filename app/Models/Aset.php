<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Aset extends Model
{
    protected $fillable = [
        'kode_aset', 'nama', 'merek', 'serial_number', 'harga_beli',
        'tanggal_beli', 'masa_garansi', 'kondisi', 'lokasi',
        'kategori_id', 'departemen_id', 'penanggung_jawab_id',
        'metode_depresiasi', 'umur_ekonomis', 'nilai_sisa', 'foto', 'keterangan'
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
        'masa_garansi'  => 'date',
        'harga_beli'    => 'decimal:2',
        'nilai_sisa'    => 'decimal:2',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class);
    }

    public function penanggungJawab(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'penanggung_jawab_id');
    }

    public function mutasis(): HasMany
    {
        return $this->hasMany(MutasiAset::class);
    }

    public function pemeliharaans(): HasMany
    {
        return $this->hasMany(Pemeliharaan::class);
    }

    // Hitung nilai saat ini berdasarkan depresiasi
    public function getNilaiSekarangAttribute(): float
    {
        $umur = Carbon::now()->diffInYears($this->tanggal_beli);
        $harga = (float) $this->harga_beli;
        $sisa  = (float) $this->nilai_sisa;

        if ($this->metode_depresiasi === 'garis_lurus') {
            $depresiasi = ($harga - $sisa) / $this->umur_ekonomis;
            $nilai = $harga - ($depresiasi * $umur);
        } else {
            // Saldo menurun
            $rate = 1 - pow(($sisa / max($harga, 1)), 1 / $this->umur_ekonomis);
            $nilai = $harga * pow(1 - $rate, $umur);
        }

        return max((float) $nilai, $sisa);
    }

    // Data grafik depresiasi per tahun
    public function getDataDepresiasiAttribute(): array
    {
        $harga = (float) $this->harga_beli;
        $sisa  = (float) $this->nilai_sisa;
        $umur  = $this->umur_ekonomis;
        $data  = [];

        for ($i = 0; $i <= $umur; $i++) {
            if ($this->metode_depresiasi === 'garis_lurus') {
                $dep     = ($harga - $sisa) / $umur;
                $nilai   = $harga - ($dep * $i);
            } else {
                $rate  = 1 - pow(($sisa / max($harga, 1)), 1 / $umur);
                $nilai = $harga * pow(1 - $rate, $i);
            }
            $tahun  = $this->tanggal_beli->year + $i;
            $data[] = ['tahun' => $tahun, 'nilai' => max(round($nilai), $sisa)];
        }

        return $data;
    }

    public function getLabelKondisiAttribute(): string
    {
        return match($this->kondisi) {
            'baik'       => '<span class="badge bg-success">Baik</span>',
            'rusak'      => '<span class="badge bg-danger">Rusak</span>',
            'hilang'     => '<span class="badge bg-dark">Hilang</span>',
            'tidak_aktif'=> '<span class="badge bg-secondary">Tidak Aktif</span>',
            default      => $this->kondisi,
        };
    }

    public function getGaransiHabisAttribute(): bool
    {
        return $this->masa_garansi && $this->masa_garansi->isPast();
    }

    public function getGaransiSegera(): bool
    {
        return $this->masa_garansi && $this->masa_garansi->isFuture()
            && $this->masa_garansi->diffInDays(Carbon::now()) <= 30;
    }

    // Auto generate kode aset
    public static function generateKode(string $jenisKategori): string
    {
        $prefix = match($jenisKategori) {
            'elektronik' => 'ELK',
            'furniture'  => 'FRN',
            'kendaraan'  => 'KND',
            default      => 'AST',
        };
        $urut = self::count() + 1;
        return $prefix . '-' . date('Y') . '-' . str_pad($urut, 4, '0', STR_PAD_LEFT);
    }
}
