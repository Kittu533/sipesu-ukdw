<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // --- MANAJEMEN MAHASISWA ---

    public function mahasiswaIndex(Request $request)
    {
        $this->authorizeAdmin();

        // Ambil data untuk dropdown filter
        $prodiList = ProgramStudi::all();
        $angkatanList = Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');

        // Query dasar
        $query = Mahasiswa::with(['user', 'prodi']);

        // Filter berdasarkan Prodi
        if ($request->filled('prodi')) {
            $query->where('id_prodi', $request->prodi);
        }

        // Filter berdasarkan Angkatan
        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        // Filter Pencarian (Nama/NIM)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        }

        // Jika tidak ada filter, jangan tampilkan semua data (kosongkan atau limit)
        // Sesuai request: "nanti menampilkan prodi dulu... baru memunculkan bagian mahasiswanya"
        // Jadi jika tidak ada filter, kita bisa return collection kosong atau paginate kosong
        if (!$request->filled('prodi') && !$request->filled('angkatan') && !$request->filled('search')) {
            $mahasiswa = collect([]); // Kosongkan jika belum ada filter
            $showData = false;
        } else {
            $mahasiswa = $query->latest()->paginate(20)->withQueryString();
            $showData = true;
        }

        return view('admin.mahasiswa.index', compact('mahasiswa', 'prodiList', 'angkatanList', 'showData'));
    }

    // --- MANAJEMEN PRODI ---

    public function prodiIndex()
    {
        $this->authorizeAdmin();
        $prodi = ProgramStudi::withCount('mahasiswa')->get();
        return view('admin.prodi.index', compact('prodi'));
    }

    // --- HELPER ---

    private function authorizeAdmin()
    {
        if (Auth::user()->id_hak_akses != 2) {
            abort(403, 'Akses ditolak.');
        }
    }
}
