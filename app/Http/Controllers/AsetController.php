<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Departemen;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{
    public function index(Request $request)
    {
        $query = Aset::with(['kategori', 'departemen', 'penanggungJawab']);

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->cari . '%')
                  ->orWhere('kode_aset', 'like', '%' . $request->cari . '%')
                  ->orWhere('merek', 'like', '%' . $request->cari . '%')
                  ->orWhere('serial_number', 'like', '%' . $request->cari . '%');
            });
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('departemen_id')) {
            $query->where('departemen_id', $request->departemen_id);
        }

        $asets      = $query->latest()->paginate(15)->withQueryString();
        $kategoris  = Kategori::all();
        $departemens= Departemen::all();

        return view('aset.index', compact('asets', 'kategoris', 'departemens'));
    }

    public function create()
    {
        $kategoris   = Kategori::all();
        $departemens = Departemen::all();
        $karyawans   = Karyawan::with('departemen')->get();
        return view('aset.create', compact('kategoris', 'departemens', 'karyawans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'               => 'required|string|max:255',
            'merek'              => 'nullable|string|max:100',
            'serial_number'      => 'nullable|string|max:100',
            'harga_beli'         => 'required|numeric|min:0',
            'tanggal_beli'       => 'required|date',
            'masa_garansi'       => 'nullable|date|after:tanggal_beli',
            'kondisi'            => 'required|in:baik,rusak,hilang,tidak_aktif',
            'lokasi'             => 'required|string|max:255',
            'kategori_id'        => 'required|exists:kategoris,id',
            'departemen_id'      => 'required|exists:departemens,id',
            'penanggung_jawab_id'=> 'nullable|exists:karyawans,id',
            'metode_depresiasi'  => 'required|in:garis_lurus,saldo_menurun',
            'umur_ekonomis'      => 'required|integer|min:1|max:50',
            'nilai_sisa'         => 'required|numeric|min:0',
            'keterangan'         => 'nullable|string',
            'foto'               => 'nullable|image|max:2048',
        ]);

        $kategori       = Kategori::find($request->kategori_id);
        $data['kode_aset'] = Aset::generateKode($kategori->jenis);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('aset', 'public');
        }

        Aset::create($data);
        return redirect()->route('aset.index')->with('sukses', 'Aset berhasil ditambahkan.');
    }

    public function show(Aset $aset)
    {
        $aset->load(['kategori', 'departemen', 'penanggungJawab', 'mutasis.keDepartemen', 'pemeliharaans']);
        $dataDepresiasi = $aset->data_depresiasi;
        return view('aset.show', compact('aset', 'dataDepresiasi'));
    }

    public function edit(Aset $aset)
    {
        $kategoris   = Kategori::all();
        $departemens = Departemen::all();
        $karyawans   = Karyawan::with('departemen')->get();
        return view('aset.edit', compact('aset', 'kategoris', 'departemens', 'karyawans'));
    }

    public function update(Request $request, Aset $aset)
    {
        $data = $request->validate([
            'nama'               => 'required|string|max:255',
            'merek'              => 'nullable|string|max:100',
            'serial_number'      => 'nullable|string|max:100',
            'harga_beli'         => 'required|numeric|min:0',
            'tanggal_beli'       => 'required|date',
            'masa_garansi'       => 'nullable|date',
            'kondisi'            => 'required|in:baik,rusak,hilang,tidak_aktif',
            'lokasi'             => 'required|string|max:255',
            'kategori_id'        => 'required|exists:kategoris,id',
            'departemen_id'      => 'required|exists:departemens,id',
            'penanggung_jawab_id'=> 'nullable|exists:karyawans,id',
            'metode_depresiasi'  => 'required|in:garis_lurus,saldo_menurun',
            'umur_ekonomis'      => 'required|integer|min:1|max:50',
            'nilai_sisa'         => 'required|numeric|min:0',
            'keterangan'         => 'nullable|string',
            'foto'               => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($aset->foto) Storage::disk('public')->delete($aset->foto);
            $data['foto'] = $request->file('foto')->store('aset', 'public');
        }

        $aset->update($data);
        return redirect()->route('aset.show', $aset)->with('sukses', 'Aset berhasil diperbarui.');
    }

    public function destroy(Aset $aset)
    {
        if ($aset->foto) Storage::disk('public')->delete($aset->foto);
        $aset->delete();
        return redirect()->route('aset.index')->with('sukses', 'Aset berhasil dihapus.');
    }

    public function qrcode(Aset $aset)
    {
        return view('aset.qrcode', compact('aset'));
    }

    public function scan(Request $request)
    {
        $kode = $request->query('kode');
        $aset = $kode ? Aset::where('kode_aset', $kode)->first() : null;
        return view('aset.scan', compact('aset', 'kode'));
    }
}
