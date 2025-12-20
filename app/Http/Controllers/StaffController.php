<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\LogStatusSurat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * Menampilkan daftar pengajuan yang perlu divalidasi oleh Staff.
     */
    public function validationList()
    {
        $this->authorizeStaff();

        // Ambil pengajuan dengan status 'Menunggu Verifikasi'
        $pengajuan = PengajuanSurat::with(['mahasiswa.user', 'jenisSurat'])
                        ->where('status_saat_ini', 'Menunggu Verifikasi')
                        ->latest()
                        ->paginate(10);

        return view('staff.validation.index', compact('pengajuan'));
    }

    /**
     * Memproses validasi pengajuan (Terima / Tolak).
     */
    public function processValidation(Request $request, $id)
    {
        $this->authorizeStaff();

        $request->validate([
            'action' => 'required|in:accept,reject',
            'catatan' => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);

        // Pastikan status masih Menunggu Verifikasi
        if ($pengajuan->status_saat_ini != 'Menunggu Verifikasi') {
            return back()->with('error', 'Status pengajuan sudah berubah.');
        }

        DB::beginTransaction();
        try {
            if ($request->action == 'accept') {
                $statusBaru = 'Menunggu Tanda Tangan'; // Lanjut ke Pejabat
                $keterangan = 'Pengajuan divalidasi oleh staff dan diteruskan ke pejabat.';
            } else {
                $statusBaru = 'Ditolak';
                $keterangan = 'Pengajuan ditolak oleh staff. Alasan: ' . $request->catatan;
            }

            // Update Status Pengajuan
            $pengajuan->status_saat_ini = $statusBaru;
            $pengajuan->save();

            // Catat Log
            LogStatusSurat::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status_baru' => $statusBaru,
                'tgl_perubahan' => now(),
                'diubah_oleh_user' => Auth::id(),
                'keterangan' => $keterangan,
            ]);

            DB::commit();

            $msg = ($request->action == 'accept') ? 'Pengajuan berhasil divalidasi.' : 'Pengajuan telah ditolak.';
            return redirect()->route('staff.validation.index')->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function authorizeStaff()
    {
        if (Auth::user()->id_hak_akses != 3) {
            abort(403, 'Akses ditolak.');
        }
    }
}
