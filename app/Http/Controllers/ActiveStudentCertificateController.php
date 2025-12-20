<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ActiveStudentCertificateController extends Controller
{
    public function generatePDF(Request $request)
    {
        // Data mahasiswa aktif
        $studentData = [
            'nama' => $request->input('nama', 'Tiara Nov Adelia'),
            'tempat_lahir' => $request->input('tempat_lahir', 'Sanggau'),
            'tanggal_lahir' => $request->input('tanggal_lahir', '11 November 2003'),
            'nim' => $request->input('nim', '12210664'),
            'fakultas' => $request->input('fakultas', 'Bisnis'),
            'prodi' => $request->input('prodi', 'Akuntansi'),
            'semester_aktif' => $request->input('semester_aktif', 'Gasal 2025/2026'),
        ];

        // Data orang tua
        $parentData = [
            'nama' => $request->input('parent_nama', 'Elia, S.Pd., M.A.P.'),
            'nip' => $request->input('parent_nip', '197302102005021001'),
            'pangkat' => $request->input('parent_pangkat', 'Pembina Tk.I, IV/b'),
            'instansi' => $request->input('parent_instansi', 'SMP Negeri 2 Ngabang'),
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
            'parent' => $parentData,
            'signatory' => $signatoryData,
            'nomor_surat' => $nomorSurat,
            'tanggal_surat' => Carbon::now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('pdf.active-student-certificate', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('surat-keterangan-aktif-kuliah-' . $studentData['nim'] . '.pdf');
    }

    private function generateNomorSurat()
    {
        $year = date('Y');
        $counter = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return "{$counter}/C.12/BAA/UKDW/{$year}";
    }
}
