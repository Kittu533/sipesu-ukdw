<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\ProgramStudi;
use App\Models\JenisSurat;
use Illuminate\Support\Facades\Auth;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        // Authorize admin and staff access
        if (!in_array(Auth::user()->id_hak_akses, [2, 3])) {
            abort(403, 'Akses ditolak.');
        }

        // Data untuk dropdown filter
        $prodiList = ProgramStudi::all();
        $jenisSuratList = JenisSurat::all();

        // Query dasar - hanya surat yang sudah selesai
        $query = PengajuanSurat::with(['mahasiswa.user', 'mahasiswa.prodi', 'jenisSurat'])
                    ->where('status_saat_ini', 'Selesai');

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('updated_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('updated_at', '<=', $request->tanggal_sampai);
        }

        // Filter berdasarkan program studi
        if ($request->filled('prodi')) {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('id_prodi', $request->prodi);
            });
        }

        // Filter berdasarkan jenis surat
        if ($request->filled('jenis_surat')) {
            $query->where('id_jenis_surat', $request->jenis_surat);
        }

        // Filter berdasarkan NIM atau nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat_resmi', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', function($mq) use ($search) {
                      $mq->where('nim', 'like', "%{$search}%")
                        ->orWhereHas('user', function($uq) use ($search) {
                            $uq->where('nama_lengkap', 'like', "%{$search}%");
                        });
                  });
            });
        }

        // Default: tampilkan data terbaru dengan pagination
        $archives = $query->latest('updated_at')->paginate(20)->withQueryString();

        return view('admin.archive.index', compact('archives', 'prodiList', 'jenisSuratList'));
    }

    public function download($id)
    {
        if (!in_array(Auth::user()->id_hak_akses, [2, 3])) {
            abort(403, 'Akses ditolak.');
        }

        $pengajuan = PengajuanSurat::where('status_saat_ini', 'Selesai')->findOrFail($id);

        if (!$pengajuan->file_surat_content) {
            abort(404, 'File surat tidak tersedia.');
        }

        return response($pengajuan->file_surat_content)
            ->header('Content-Type', $pengajuan->file_surat_mime_type ?? 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . ($pengajuan->file_surat_name ?? 'surat.pdf') . '"');
    }

    public function detail($id)
    {
        if (!in_array(Auth::user()->id_hak_akses, [2, 3])) {
            abort(403, 'Akses ditolak.');
        }

        $pengajuan = PengajuanSurat::with([
            'mahasiswa.user', 
            'mahasiswa.prodi',
            'jenisSurat',
            'detailPengajuan',
            'validasiStaff.user',
            'persetujuanPejabat.pejabat.user',
            'logStatusSurat'
        ])->where('status_saat_ini', 'Selesai')->findOrFail($id);

        return view('admin.archive.detail', compact('pengajuan'));
    }

    public function print($id)
    {
        if (!in_array(Auth::user()->id_hak_akses, [2, 3])) {
            abort(403, 'Akses ditolak.');
        }

        $pengajuan = PengajuanSurat::where('status_saat_ini', 'Selesai')->findOrFail($id);

        if (!$pengajuan->file_surat_content) {
            abort(404, 'File surat tidak tersedia.');
        }

        return response($pengajuan->file_surat_content)
            ->header('Content-Type', $pengajuan->file_surat_mime_type ?? 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . ($pengajuan->file_surat_name ?? 'surat.pdf') . '"');
    }
}
