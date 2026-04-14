<?php

namespace App\Http\Controllers;

use App\Models\Pemeliharaan;
use App\Models\Aset;
use Illuminate\Http\Request;

class PemeliharaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemeliharaan::with('aset');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pemeliharaans = $query->orderBy('tanggal_jadwal')->paginate(15)->withQueryString();
        return view('pemeliharaan.index', compact('pemeliharaans'));
    }

    public function create()
    {
        $asets = Aset::orderBy('nama')->get();
        return view('pemeliharaan.create', compact('asets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aset_id'        => 'required|exists:asets,id',
            'jenis'          => 'required|in:rutin,perbaikan,penggantian_komponen',
            'tanggal_jadwal' => 'required|date',
            'biaya'          => 'nullable|numeric|min:0',
            'teknisi'        => 'nullable|string',
            'deskripsi'      => 'required|string',
        ]);

        Pemeliharaan::create([
            ...$request->only('aset_id', 'jenis', 'tanggal_jadwal', 'biaya', 'teknisi', 'deskripsi'),
            'status' => 'terjadwal',
        ]);

        return redirect()->route('pemeliharaan.index')->with('sukses', 'Jadwal pemeliharaan berhasil ditambahkan.');
    }

    public function show(Pemeliharaan $pemeliharaan)
    {
        $pemeliharaan->load('aset');
        return view('pemeliharaan.show', compact('pemeliharaan'));
    }

    public function edit(Pemeliharaan $pemeliharaan)
    {
        $asets = Aset::orderBy('nama')->get();
        return view('pemeliharaan.edit', compact('pemeliharaan', 'asets'));
    }

    public function update(Request $request, Pemeliharaan $pemeliharaan)
    {
        $request->validate([
            'jenis'           => 'required',
            'tanggal_jadwal'  => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'biaya'           => 'nullable|numeric|min:0',
            'teknisi'         => 'nullable|string',
            'status'          => 'required|in:terjadwal,dalam_proses,selesai,dibatalkan',
            'deskripsi'       => 'required|string',
            'hasil'           => 'nullable|string',
        ]);

        $pemeliharaan->update($request->only(
            'jenis', 'tanggal_jadwal', 'tanggal_selesai', 'biaya',
            'teknisi', 'status', 'deskripsi', 'hasil'
        ));

        // Jika selesai, update kondisi aset jadi baik
        if ($request->status === 'selesai') {
            $pemeliharaan->aset->update(['kondisi' => 'baik']);
        }

        return redirect()->route('pemeliharaan.index')->with('sukses', 'Pemeliharaan berhasil diperbarui.');
    }

    public function destroy(Pemeliharaan $pemeliharaan)
    {
        $pemeliharaan->delete();
        return back()->with('sukses', 'Data pemeliharaan berhasil dihapus.');
    }
}
