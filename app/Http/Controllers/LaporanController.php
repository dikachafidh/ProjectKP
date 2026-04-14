<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Departemen;
use App\Models\Kategori;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $departemens = Departemen::all();
        $kategoris   = Kategori::all();
        return view('laporan.index', compact('departemens', 'kategoris'));
    }

    public function inventaris(Request $request)
    {
        $query = Aset::with(['kategori', 'departemen', 'penanggungJawab']);

        if ($request->filled('kondisi'))      $query->where('kondisi', $request->kondisi);
        if ($request->filled('departemen_id'))$query->where('departemen_id', $request->departemen_id);
        if ($request->filled('kategori_id'))  $query->where('kategori_id', $request->kategori_id);

        $asets        = $query->get();
        $totalNilai   = $asets->sum('harga_beli');
        $departemens  = Departemen::all();
        $kategoris    = Kategori::all();

        return view('laporan.inventaris', compact('asets', 'totalNilai', 'departemens', 'kategoris'));
    }

    public function notifikasi()
    {
        $garansiHabis = Aset::whereNotNull('masa_garansi')
            ->whereDate('masa_garansi', '<=', now()->addDays(30))
            ->with('departemen')
            ->orderBy('masa_garansi')
            ->get();

        $maintenanceMendatang = \App\Models\Pemeliharaan::with('aset')
            ->where('status', 'terjadwal')
            ->whereDate('tanggal_jadwal', '<=', now()->addDays(30))
            ->orderBy('tanggal_jadwal')
            ->get();

        return view('laporan.notifikasi', compact('garansiHabis', 'maintenanceMendatang'));
    }
}
