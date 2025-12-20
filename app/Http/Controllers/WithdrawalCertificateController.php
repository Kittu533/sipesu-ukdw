<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class WithdrawalCertificateController extends Controller
{
    public function generatePDF(Request $request)
    {
        // Data mahasiswa pengunduran diri
        $studentData = [
            'nama' => $request->input('nama', 'MARCO AMADEUS ANDRIANTO'),
            'tempat_lahir' => $request->input('tempat_lahir', 'Purwokerto'),
            'tanggal_lahir' => $request->input('tanggal_lahir', '4 Desember 2000'),
            'nim' => $request->input('nim', '01180170'),
            'fakultas' => $request->input('fakultas', 'Teologi'),
            'prodi' => $request->input('prodi', 'Filsafat Keilahian'),
            'akreditasi' => $request->input('akreditasi', 'B'),
            'sk_ban_pt' => $request->input('sk_ban_pt', '1234/SK/BAN-PT/Ak-PPJ/S/V/2023'),
            'semester_awal' => $request->input('semester_awal', 'Gasal 2018/2019'),
            'semester_akhir' => $request->input('semester_akhir', 'Genap 2022/2023'),
            'tanggal_mundur' => $request->input('tanggal_mundur', '15 Januari 2023'),
            'referensi_surat' => $request->input('referensi_surat', 'Surat Keterangan Wakil Rektor Bidang Akademik dan Riset'),
        ];

        // Data penandatangan
        $signatoryData = [
            'nama' => 'Drs. Wimmie Handiwidjojo, MIT',
            'nik' => '894 E 090',
            'pangkat' => 'Pembina Utama, IV/e',
            'jabatan' => 'Kepala Biro Administrasi Akademik',
        ];

        $nomorSurat = $this->generateNomorSurat();

        $data = [
            'student' => $studentData,
            'signatory' => $signatoryData,
            'nomor_surat' => $nomorSurat,
            'tanggal_surat' => Carbon::now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('pdf.withdrawal-certificate', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('surat-keterangan-pengunduran-diri-' . $studentData['nim'] . '.pdf');
    }

    private function generateNomorSurat()
    {
        $year = date('Y');
        $counter = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return "{$counter}/C.12/BAA/UKDW/{$year}";
    }
}
