<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'aktif'    => true,
        ];

        if (Auth::attempt($credentials, $request->boolean('ingat'))) {
            $request->session()->regenerate();

            // Update last login
            Auth::user()->update(['last_login' => now()]);

            return response()->json([
                'sukses'  => true,
                'nama'    => Auth::user()->nama,
                'role'    => Auth::user()->label_role,
                'redirect'=> route('dashboard'),
            ]);
        }

        return response()->json([
            'sukses'  => false,
            'pesan'   => 'Username atau password salah. Silakan coba lagi.',
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('sukses', 'Berhasil keluar dari sistem.');
    }
}
