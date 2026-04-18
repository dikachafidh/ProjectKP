<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index()
    {
        $divisis = Departemen::withCount('asets')->get();
        return view('divisi.index', compact('divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required',
            'kode'  => 'required|unique:departemens,kode',
        ]);
        Departemen::create($request->only('nama','kode','lokasi','kepala'));
        return back()->with('sukses','Divisi berhasil ditambahkan.');
    }

    public function update(Request $request, Departemen $divisi)
    {
        $request->validate([
            'nama'  => 'required',
            'kode'  => 'required|unique:departemens,kode,'.$divisi->id,
        ]);
        $divisi->update($request->only('nama','kode','lokasi','kepala'));
        return back()->with('sukses','Divisi berhasil diperbarui.');
    }

    public function destroy(Departemen $divisi)
    {
        if ($divisi->asets()->count() > 0) {
            return back()->with('gagal','Divisi tidak bisa dihapus karena masih memiliki aset.');
        }
        $divisi->delete();
        return back()->with('sukses','Divisi berhasil dihapus.');
    }
}
