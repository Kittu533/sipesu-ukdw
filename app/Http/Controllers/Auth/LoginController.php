<?php

// File: app/Http/Controllers/Auth/LoginController.php
// File: app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
use App\Models\Pejabat;

class LoginController extends Controller
{
    /**
     * Tampilkan formulir login.
     * Pastikan user yang sudah login tidak bisa mengakses halaman ini.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    /**
     * Tangani permintaan login yang masuk.
     */
    public function login(Request $request)
    {
        // 1. Validasi Input (menggunakan 'username' dan 'password')
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $input = $request->input('username');
        $password = $request->input('password');
        $remember = false; // Disable remember me functionality

        // Logika penentuan username berdasarkan input (NIM, NIP, atau Username)
        $username = $input;

        // Cek apakah input adalah NIM (Mahasiswa)
        $mahasiswa = Mahasiswa::where('nim', $input)->first();
        if ($mahasiswa && $mahasiswa->user) {
            $username = $mahasiswa->user->username;
        } else {
            // Cek apakah input adalah NIP (Pejabat)
            $pejabat = Pejabat::where('nip', $input)->first();
            if ($pejabat && $pejabat->user) {
                $username = $pejabat->user->username;
            }
        }

        $credentials = [
            'username' => $username,
            'password' => $password
        ];
        
        // 2. Proses Autentikasi
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Cek Status Aktif (Tambahan Logika Bisnis)
            if (!$user->status_aktif) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['username' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
            }
            
            $request->session()->regenerate();

            // 3. Redirect ke dashboard
            return redirect()->intended(route('dashboard'));
        }

        // 4. Gagal Autentikasi
        return back()->withErrors([
            'username' => 'Username/NIM atau Password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }
    
    /**
     * Tangani permintaan logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login'); // Arahkan kembali ke halaman login
    }
}