<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;

class DepartemenController extends Controller
{
    public function index()
    {
        $departemens = Departemen::withCount(['asets', 'karyawans'])->get();
        return view('departemen.index', compact('departemens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required',
            'kode'  => 'required|unique:departemens,kode',
        ]);
        Departemen::create($request->only('nama', 'kode', 'lokasi', 'kepala'));
        return back()->with('sukses', 'Departemen berhasil ditambahkan.');
    }

    public function update(Request $request, Departemen $departemen)
    {
        $request->validate([
            'nama'  => 'required',
            'kode'  => 'required|unique:departemens,kode,' . $departemen->id,
        ]);
        $departemen->update($request->only('nama', 'kode', 'lokasi', 'kepala'));
        return back()->with('sukses', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Departemen $departemen)
    {
        if ($departemen->asets()->count() > 0) {
            return back()->with('gagal', 'Departemen tidak bisa dihapus karena masih memiliki aset.');
        }
        $departemen->delete();
        return back()->with('sukses', 'Departemen berhasil dihapus.');
    }
}
