<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('asets')->get();
        return view('kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required', 'jenis' => 'required']);
        Kategori::create($request->only('nama', 'jenis', 'keterangan'));
        return back()->with('sukses', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate(['nama' => 'required', 'jenis' => 'required']);
        $kategori->update($request->only('nama', 'jenis', 'keterangan'));
        return back()->with('sukses', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->asets()->count() > 0) {
            return back()->with('gagal', 'Kategori tidak bisa dihapus karena masih memiliki aset.');
        }
        $kategori->delete();
        return back()->with('sukses', 'Kategori berhasil dihapus.');
    }
}
