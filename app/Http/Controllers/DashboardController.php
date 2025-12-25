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
                
                // Pengajuan terbaru dengan pencarian
                $pengajuanQuery = \App\Models\PengajuanSurat::with(['mahasiswa.user', 'jenisSurat']);
                
                if (request('search')) {
                    $search = request('search');
                    $pengajuanQuery->where(function($q) use ($search) {
                        $q->whereHas('mahasiswa.user', function($u) use ($search) {
                            $u->where('nama_lengkap', 'like', "%{$search}%");
                        })->orWhereHas('jenisSurat', function($j) use ($search) {
                            $j->where('nama_surat', 'like', "%{$search}%");
                        })->orWhereHas('mahasiswa', function($m) use ($search) {
                            $m->where('nim', 'like', "%{$search}%");
                        });
                    });
                }
                
                $data['pengajuan_terbaru'] = $pengajuanQuery->latest()->take(10)->get();
                
                // Data untuk chart
                $data['pengajuan_per_bulan'] = \App\Models\PengajuanSurat::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
                    ->whereYear('created_at', date('Y'))
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->get();
                
                $data['pengajuan_per_status'] = \App\Models\PengajuanSurat::selectRaw('status_saat_ini, COUNT(*) as total')
                    ->groupBy('status_saat_ini')
                    ->get();
                
                $data['mahasiswa_per_prodi'] = \App\Models\ProgramStudi::withCount('mahasiswa')
                    ->get();
                
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