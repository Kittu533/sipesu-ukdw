<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index()
    {
        // Ambil semua surat yang sudah Selesai
        $archives = \App\Models\PengajuanSurat::with(['mahasiswa.user', 'jenisSurat'])
                    ->where('status_saat_ini', 'Selesai')
                    ->latest()
                    ->paginate(10);

        return view('admin.archive.index', compact('archives'));
    }
}
