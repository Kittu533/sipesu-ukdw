<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Kirim notifikasi ke user tertentu
     */
    public function notifyToUser(int $userId, string $judul, string $pesan, ?string $link = null, string $type = 'info'): Notifikasi
    {
        return Notifikasi::create([
            'id_user_penerima' => $userId,
            'role_penerima' => null,
            'judul' => $judul,
            'pesan' => $pesan,
            'type' => $type,
            'link' => $link,
            'tgl_kirim' => now(),
            'is_read' => false,
        ]);
    }

    /**
     * Kirim notifikasi ke semua user dengan role tertentu
     */
    public function notifyToRole(string $role, string $judul, string $pesan, ?string $link = null, string $type = 'info'): void
    {
        $users = User::whereHas('hakAkses', function($q) use ($role) {
            $q->where('nama_hak_akses', $role);
        })->where('status_aktif', true)->get();

        foreach ($users as $user) {
            $this->notifyToUser($user->id_user, $judul, $pesan, $link, $type);
        }
    }

    /**
     * Kirim notifikasi ke multiple users
     */
    public function notifyToUsers(array $userIds, string $judul, string $pesan, ?string $link = null, string $type = 'info'): void
    {
        foreach ($userIds as $userId) {
            $this->notifyToUser($userId, $judul, $pesan, $link, $type);
        }
    }

    /**
     * Kirim notifikasi ke Admin dan Dekan (saat mahasiswa mengajukan surat)
     */
    public function notifyAdminsAndDekan(int $mahasiswaId, string $jenisSurat, string $nim): void
    {
        $judul = 'Pengajuan Baru';
        $pesan = "Mahasiswa dengan NIM {$nim} mengajukan {$jenisSurat}.";
        $link = route('admin.submission.index');

        $this->notifyToRole('admin administrasi akademik', $judul, $pesan, $link, 'info');
    }

    /**
     * Kirim notifikasi ke Dekan saja
     */
    public function notifyDekan(int $pengajuanId, string $jenisSurat, string $nim): void
    {
        $judul = 'Menunggu Validasi';
        $pesan = "Surat {$jenisSurat} untuk NIM {$nim} menunggu validasi Anda.";
        $link = route('dekan.validation.index');

        $this->notifyToRole('dekan fakultas', $judul, $pesan, $link, 'warning');
    }

    /**
     * Kirim notifikasi ke Pejabat (saat Dekan validasi selesai)
     */
    public function notifyPejabats(int $pengajuanId, string $jenisSurat, string $nim): void
    {
        $judul = 'Menunggu Persetujuan';
        $pesan = "Surat {$jenisSurat} untuk NIM {$nim} menunggu tanda tangan Anda.";
        $link = route('pejabat.approval');

        $this->notifyToRole('pejabat yg berwenang', $judul, $pesan, $link, 'warning');
    }

    /**
     * Kirim notifikasi ke Admin untuk proses akhir (cetak/kirim email)
     */
    public function notifyAdminsForFinalProcess(int $pengajuanId, string $jenisSurat, string $nim): void
    {
        $judul = 'Surat Perlu Diproses';
        $pesan = "Surat {$jenisSurat} untuk NIM {$nim} telah ditandatangani dan menunggu proses akhir (cetak/kirim email).";
        $link = route('admin.submission.index');

        $this->notifyToRole('admin administrasi akademik', $judul, $pesan, $link, 'warning');
    }

    /**
     * Kirim notifikasi ke Mahasiswa (saat status berubah)
     */
    public function notifyMahasiswa(int $userId, string $judul, string $pesan, ?string $link = null, string $type = 'info'): void
    {
        $this->notifyToUser($userId, $judul, $pesan, $link, $type);
    }

    /**
     * Kirim notifikasi penolakan ke Mahasiswa
     */
    public function notifyPenolakan(int $userId, string $jenisSurat, string $alasan): void
    {
        $judul = 'Pengajuan Ditolak';
        $pesan = "Mohon maaf, pengajuan {$jenisSurat} Anda ditolak. Alasan: {$alasan}";
        $link = route('submission.history');

        $this->notifyToUser($userId, $judul, $pesan, $link, 'error');
    }

    /**
     * Kirim notifikasi persetujuan ke Mahasiswa
     */
    public function notifyDisetujui(int $userId, string $jenisSurat, string $pejabat): void
    {
        $judul = 'Surat Disetujui';
        $pesan = "Pengajuan {$jenisSurat} Anda telah disetujui oleh {$pejabat}.";
        $link = route('submission.history');

        $this->notifyToUser($userId, $judul, $pesan, $link, 'success');
    }

    /**
     * Kirim notifikasi selesai (surat siap diambil)
     */
    public function notifySuratSiap(int $userId, string $jenisSurat, ?string $nomorSurat = null): void
    {
        $nomorText = $nomorSurat ? " No: {$nomorSurat}" : '';
        $judul = 'Surat Siap Diambil';
        $pesan = "Surat {$jenisSurat}{$nomorText} sudah siap untuk diambil.";
        $link = route('submission.history');

        $this->notifyToUser($userId, $judul, $pesan, $link, 'success');
    }
}
