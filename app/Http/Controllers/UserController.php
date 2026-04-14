<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('nama')->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:admin,kepsek,staff',
            'jabatan'  => 'nullable|string|max:100',
        ], [
            'username.unique'    => 'Username sudah dipakai.',
            'email.unique'       => 'Email sudah dipakai.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'nama'     => $request->nama,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'jabatan'  => $request->jabatan,
            'aktif'    => true,
        ]);

        return back()->with('sukses', 'User '.$request->nama.' berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,'.$user->id,
            'email'    => 'nullable|email|unique:users,email,'.$user->id,
            'role'     => 'required|in:admin,kepsek,staff',
            'jabatan'  => 'nullable|string|max:100',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'nama'    => $request->nama,
            'username'=> $request->username,
            'email'   => $request->email,
            'role'    => $request->role,
            'jabatan' => $request->jabatan,
            'aktif'   => $request->boolean('aktif', true),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return back()->with('sukses', 'Data user '.$user->nama.' berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('gagal', 'Tidak bisa menghapus akun sendiri.');
        }
        $nama = $user->nama;
        $user->delete();
        return back()->with('sukses', 'User '.$nama.' berhasil dihapus.');
    }
}
