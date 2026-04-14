<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\MutasiAset;
use App\Models\Pemeliharaan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAset      = Aset::count();
        $asetBaik       = Aset::where('kondisi', 'baik')->count();
        $asetRusak      = Aset::where('kondisi', 'rusak')->count();
        $asetHilang     = Aset::where('kondisi', 'hilang')->count();
        $totalNilai     = Aset::sum('harga_beli');
        $totalKategori  = Kategori::count();
        $totalDepartemen= Departemen::count();

        // Maintenance mendatang (30 hari ke depan)
        $maintenanceMendatang = Pemeliharaan::with('aset')
            ->where('status', 'terjadwal')
            ->whereBetween('tanggal_jadwal', [Carbon::today(), Carbon::today()->addDays(30)])
            ->orderBy('tanggal_jadwal')
            ->take(5)
            ->get();

        // Garansi hampir habis (30 hari ke depan)
        $garansiHampirHabis = Aset::whereBetween('masa_garansi', [Carbon::today(), Carbon::today()->addDays(30)])
            ->orderBy('masa_garansi')
            ->take(5)
            ->get();

        // Aset per kategori untuk chart
        $asetPerKategori = Aset::selectRaw('kategori_id, count(*) as jumlah')
            ->with('kategori')
            ->groupBy('kategori_id')
            ->get()
            ->map(fn($a) => ['label' => $a->kategori->nama ?? '-', 'jumlah' => $a->jumlah]);

        // Aset per kondisi untuk chart
        $asetPerKondisi = Aset::selectRaw('kondisi, count(*) as jumlah')
            ->groupBy('kondisi')
            ->pluck('jumlah', 'kondisi');

        // Mutasi terbaru
        $mutasiTerbaru = MutasiAset::with('aset')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalAset', 'asetBaik', 'asetRusak', 'asetHilang', 'totalNilai',
            'totalKategori', 'totalDepartemen', 'maintenanceMendatang',
            'garansiHampirHabis', 'asetPerKategori', 'asetPerKondisi', 'mutasiTerbaru'
        ));
    }
}
