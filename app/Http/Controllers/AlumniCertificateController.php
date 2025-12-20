<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AlumniCertificateController extends Controller
{
    public function generatePDF(Request $request)
    {
        // Data alumni dari request atau database
        $alumniData = [
            'nama' => $request->input('nama', 'Graciana Sri Wulansari Poernawan Soenarwidjojo'),
            'tempat_lahir' => $request->input('tempat_lahir', 'Sleman'),
            'tanggal_lahir' => $request->input('tanggal_lahir', '09 September 1985'),
            'nim' => $request->input('nim', '22033289'),
            'fakultas' => $request->input('fakultas', 'Teknologi Informasi'),
            'prodi' => $request->input('prodi', 'Teknik Informatika'),
            'status' => $request->input('status', 'Lulus'),
            'tanggal_lulus' => $request->input('tanggal_lulus', '20 September 2007'),
            'nomor_ijazah' => $request->input('nomor_ijazah', '1612 T.I 2007'),
        ];

        // Data penandatangan (bisa diambil dari database)
        $signatoryData = [
            'nama' => 'Drs. Wimmie Handiwidjojo, MIT',
            'nik' => '894 E 090',
            'pangkat' => 'Pembina Utama, IV/e',
            'jabatan' => 'Kepala Biro Administrasi Akademik',
        ];

        // Nomor surat otomatis
        $nomorSurat = $this->generateNomorSurat();

        $data = [
            'alumni' => $alumniData,
            'signatory' => $signatoryData,
            'nomor_surat' => $nomorSurat,
            'tanggal_surat' => Carbon::now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('pdf.alumni-certificate', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('surat-keterangan-alumni-' . $alumniData['nim'] . '.pdf');
    }

    private function generateNomorSurat()
    {
        $year = date('Y');
        $counter = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return "{$counter}/C.12/BAA/UKDW/{$year}";
    }
}
