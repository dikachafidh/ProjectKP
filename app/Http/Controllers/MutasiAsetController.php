<?php

namespace App\Http\Controllers;

use App\Models\MutasiAset;
use App\Models\Aset;
use App\Models\Departemen;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class MutasiAsetController extends Controller
{
    public function index()
    {
        $mutasis = MutasiAset::with(['aset', 'keDepartemen', 'keKaryawan'])
            ->latest()->paginate(15);
        return view('mutasi.index', compact('mutasis'));
    }

    public function create()
    {
        $asets       = Aset::where('kondisi', 'baik')->with('departemen')->get();
        $departemens = Departemen::all();
        $karyawans   = Karyawan::with('departemen')->get();
        return view('mutasi.create', compact('asets', 'departemens', 'karyawans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'aset_id'            => 'required|exists:asets,id',
            'dari_lokasi'        => 'required|string',
            'ke_lokasi'          => 'required|string',
            'dari_departemen_id' => 'nullable|exists:departemens,id',
            'ke_departemen_id'   => 'nullable|exists:departemens,id',
            'dari_karyawan_id'   => 'nullable|exists:karyawans,id',
            'ke_karyawan_id'     => 'nullable|exists:karyawans,id',
            'tanggal_mutasi'     => 'required|date',
            'alasan'             => 'required|string',
            'keterangan'         => 'nullable|string',
            'disetujui_oleh'     => 'nullable|string',
        ]);

        MutasiAset::create($data);

        // Update aset
        $aset = Aset::find($data['aset_id']);
        $aset->update([
            'lokasi'             => $data['ke_lokasi'],
            'departemen_id'      => $data['ke_departemen_id'] ?? $aset->departemen_id,
            'penanggung_jawab_id'=> $data['ke_karyawan_id'] ?? $aset->penanggung_jawab_id,
        ]);

        return redirect()->route('mutasi.index')->with('sukses', 'Mutasi aset berhasil dicatat.');
    }

    public function show(MutasiAset $mutasi)
    {
        $mutasi->load(['aset', 'dariDepartemen', 'keDepartemen', 'dariKaryawan', 'keKaryawan']);
        return view('mutasi.show', compact('mutasi'));
    }
}
