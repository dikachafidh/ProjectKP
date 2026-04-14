<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Departemen;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans   = Karyawan::with('departemen')->withCount('asets')->paginate(15);
        $departemens = Departemen::all();
        return view('karyawan.index', compact('karyawans', 'departemens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik'           => 'required|unique:karyawans,nik',
            'nama'          => 'required',
            'jabatan'       => 'required',
            'departemen_id' => 'required|exists:departemens,id',
        ]);
        Karyawan::create($request->only('nik', 'nama', 'jabatan', 'departemen_id', 'email', 'telepon'));
        return back()->with('sukses', 'Karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nik'           => 'required|unique:karyawans,nik,' . $karyawan->id,
            'nama'          => 'required',
            'jabatan'       => 'required',
            'departemen_id' => 'required|exists:departemens,id',
        ]);
        $karyawan->update($request->only('nik', 'nama', 'jabatan', 'departemen_id', 'email', 'telepon'));
        return back()->with('sukses', 'Karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        if ($karyawan->asets()->count() > 0) {
            return back()->with('gagal', 'Karyawan masih memiliki aset yang ditanggung.');
        }
        $karyawan->delete();
        return back()->with('sukses', 'Karyawan berhasil dihapus.');
    }
}
