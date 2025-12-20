<?php

// File: app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roleId = $user->id_hak_akses;
        
        // Data yang akan dikirim ke view
        $data = [];

        switch ($roleId) {
            case 1: // Mahasiswa
                $mahasiswa = $user->mahasiswa;
                if ($mahasiswa) {
                    $query = \App\Models\PengajuanSurat::where('id_mahasiswa', $mahasiswa->id_mahasiswa);
                    $data['total_pengajuan'] = $query->count();
                    $data['menunggu'] = (clone $query)->whereIn('status_saat_ini', ['Menunggu Verifikasi', 'Diproses', 'Menunggu Tanda Tangan'])->count();
                    $data['selesai'] = (clone $query)->where('status_saat_ini', 'Selesai')->count();
                    $data['ditolak'] = (clone $query)->where('status_saat_ini', 'Ditolak')->count();
                    $data['pengajuan_terbaru'] = (clone $query)->with('jenisSurat')->latest()->take(5)->get();

                    // Fetch recent activity logs
                    $pengajuanIds = $query->pluck('id_pengajuan');
                    $data['aktifitas_terkini'] = \App\Models\LogStatusSurat::whereIn('id_pengajuan', $pengajuanIds)
                                                    ->with(['pengajuanSurat.jenisSurat'])
                                                    ->latest('tgl_perubahan')
                                                    ->take(5)
                                                    ->get();
                }
                return view('dashboard.mahasiswa', compact('user', 'data'));

            case 2: // Admin Administrasi Akademik
                $data['total_pengajuan'] = \App\Models\PengajuanSurat::count();
                $data['total_mahasiswa'] = \App\Models\Mahasiswa::count();
                $data['total_prodi'] = \App\Models\ProgramStudi::count();
                $data['pengajuan_terbaru'] = \App\Models\PengajuanSurat::with(['mahasiswa.user', 'jenisSurat'])->latest()->take(10)->get();
                return view('dashboard.admin', compact('user', 'data'));

            case 3: // Staff Pelayanan Jurusan
                // Asumsi staff bisa melihat semua pengajuan atau difilter berdasarkan jurusan (perlu relasi lebih lanjut)
                // Untuk saat ini kita tampilkan semua yang butuh verifikasi
                $data['menunggu_validasi'] = \App\Models\PengajuanSurat::where('status_saat_ini', 'Menunggu Verifikasi')->count();
                $data['diproses'] = \App\Models\PengajuanSurat::where('status_saat_ini', 'Diproses')->count();
                $data['daftar_pengajuan'] = \App\Models\PengajuanSurat::with(['mahasiswa.user', 'jenisSurat'])
                                            ->where('status_saat_ini', 'Menunggu Verifikasi')
                                            ->latest()->take(10)->get();
                return view('dashboard.staff', compact('user', 'data'));

            case 4: // Pejabat Berwenang
                $pejabat = $user->pejabat;
                // Asumsi pejabat melihat pengajuan yang sudah divalidasi staff (status 'Diproses' atau 'Menunggu Tanda Tangan')
                $data['menunggu_persetujuan'] = \App\Models\PengajuanSurat::where('status_saat_ini', 'Menunggu Tanda Tangan')->count();
                $data['disetujui'] = \App\Models\PengajuanSurat::where('status_saat_ini', 'Selesai')->count(); // Asumsi selesai = disetujui
                $data['daftar_persetujuan'] = \App\Models\PengajuanSurat::with(['mahasiswa.user', 'jenisSurat'])
                                              ->where('status_saat_ini', 'Menunggu Tanda Tangan')
                                              ->latest()->take(10)->get();
                return view('dashboard.pejabat', compact('user', 'data'));

            default:
                return view('dashboard.index', compact('user'));
        }
    }
}