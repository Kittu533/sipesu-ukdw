<?php

// File: app/Http/Controllers/SubmissionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\JenisSurat;
use App\Models\Mahasiswa;
use App\Models\PengajuanSurat;
use App\Models\LogStatusSurat;
use App\Services\NotificationService;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class SubmissionController extends Controller
{
    private function getJenisSuratIds(array $namaSurat): array
    {
        return JenisSurat::whereIn('nama_surat', $namaSurat)
            ->pluck('id_jenis_surat')
            ->all();
    }

    private function getAllowedSuratIdsForStatus(?string $statusMahasiswa): array
    {
        $suratByStatus = [
            Mahasiswa::STATUS_AKTIF => [
                JenisSurat::AKTIF_KULIAH,
                JenisSurat::PENGUNDURAN_DIRI,
                JenisSurat::CUTI_AKADEMIK,
            ],
            Mahasiswa::STATUS_TIDAK_AKTIF => [JenisSurat::PENGUNDURAN_DIRI],
            Mahasiswa::STATUS_LULUS => [JenisSurat::ALUMNI, JenisSurat::LULUS],
            Mahasiswa::STATUS_UNDUR_DIRI => [JenisSurat::PENGUNDURAN_DIRI],
            Mahasiswa::STATUS_CUTI => [],
        ];

        return $this->getJenisSuratIds($suratByStatus[$statusMahasiswa] ?? []);
    }

    private function isJenisSurat($pengajuan, string $namaSurat): bool
    {
        return ($pengajuan->jenisSurat->nama_surat ?? null) === $namaSurat;
    }

    /**
     * Menampilkan daftar status pengajuan surat (Sedang Diproses).
     */
    public function status()
    {
        $user = Auth::user();
        
        if ($user->id_hak_akses != 1 || !$user->mahasiswa) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Ambil pengajuan yang statusnya BUKAN Selesai atau Ditolak
        $pengajuan = PengajuanSurat::with(['jenisSurat'])
                        ->where('id_mahasiswa', $user->mahasiswa->id_mahasiswa)
                        ->whereNotIn('status_saat_ini', ['Selesai', 'Ditolak'])
                        ->latest()
                        ->paginate(10);

        return view('submission.status', compact('pengajuan'));
    }

    /**
     * Menampilkan riwayat pengajuan surat (Selesai/Ditolak).
     */
    public function history()
    {
        $user = Auth::user();
        
        if ($user->id_hak_akses != 1 || !$user->mahasiswa) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Ambil pengajuan yang statusnya Selesai atau Ditolak
        $pengajuan = PengajuanSurat::with(['jenisSurat'])
                        ->where('id_mahasiswa', $user->mahasiswa->id_mahasiswa)
                        ->whereIn('status_saat_ini', ['Selesai', 'Ditolak'])
                        ->latest()
                        ->paginate(10);

        return view('submission.history', compact('pengajuan'));
    }

    /**
     * Menampilkan formulir pengajuan surat baru.
     */
    public function create()
    {
        $user = auth()->user();
        
        if (!$user->mahasiswa) {
            return redirect()->route('dashboard')->withErrors(['msg' => 'Data mahasiswa tidak ditemukan.']);
        }
        
        $statusMahasiswa = $user->mahasiswa->status_mahasiswa;
        
        $allowedSurat = $this->getAllowedSuratIdsForStatus($statusMahasiswa);
        $jenisSurat = JenisSurat::whereIn('id_jenis_surat', $allowedSurat)->get();
        
        return view('submission.create', compact('jenisSurat', 'statusMahasiswa'));
    }

    /**
     * Menyimpan pengajuan surat baru ke database.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->mahasiswa) {
            return back()->withErrors(['msg' => 'Data mahasiswa tidak ditemukan.']);
        }
        
        $statusMahasiswa = $user->mahasiswa->status_mahasiswa;
        
        $allowedSurat = $this->getAllowedSuratIdsForStatus($statusMahasiswa);

        if ($statusMahasiswa === Mahasiswa::STATUS_CUTI) {
            return back()->withErrors([
                'id_jenis_surat' => 'Mahasiswa dengan status Cuti tidak dapat mengajukan surat apa pun.'
            ])->withInput();
        }

        // Validasi
        $request->validate([
            'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis_surat',
            'keterangan_mahasiswa' => 'required|string|max:500',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Opsional tergantung kebutuhan
        ]);
        
        if (!in_array($request->id_jenis_surat, $allowedSurat)) {
            $statusLabel = match($statusMahasiswa) {
                Mahasiswa::STATUS_AKTIF => 'Aktif',
                Mahasiswa::STATUS_TIDAK_AKTIF => 'Tidak Aktif',
                Mahasiswa::STATUS_LULUS => 'Lulus',
                Mahasiswa::STATUS_UNDUR_DIRI => 'Undur Diri',
                Mahasiswa::STATUS_CUTI => 'Cuti',
                default => 'Unknown',
            };
            
            $allowedNames = JenisSurat::whereIn('id_jenis_surat', $allowedSurat)->pluck('nama_surat')->toArray();
            $allowedText = count($allowedNames) > 0 ? implode(' atau ', $allowedNames) : 'surat apa pun';
            
            return back()->withErrors([
                'id_jenis_surat' => "Mahasiswa dengan status {$statusLabel} hanya dapat mengajukan {$allowedText}."
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            // 1. Simpan Pengajuan
            $pengajuan = new PengajuanSurat();
            $pengajuan->id_mahasiswa = $user->mahasiswa->id_mahasiswa;
            $pengajuan->id_jenis_surat = $request->id_jenis_surat;
            $pengajuan->tgl_pengajuan = now();
            $pengajuan->status_saat_ini = 'Menunggu Verifikasi Admin';
            $pengajuan->keterangan_mahasiswa = $request->keterangan_mahasiswa;
            
            // Handle File Upload jika ada (Simpan di storage/app/public/lampiran)
            if ($request->hasFile('lampiran')) {
                // $path = $request->file('lampiran')->store('lampiran_pengajuan', 'public');
                // $pengajuan->file_lampiran_path = $path; // Pastikan kolom ada di DB jika fitur ini aktif
            }

            $pengajuan->save();

            // 2. Catat Log Status
            LogStatusSurat::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status_baru' => 'Menunggu Verifikasi Admin',
                'tgl_perubahan' => now(),
                'diubah_oleh_user' => $user->id_user,
                'keterangan' => 'Pengajuan baru dibuat oleh mahasiswa.',
            ]);

            // 3. Kirim notifikasi ke Admin dan Dekan
            $jenisSurat = JenisSurat::find($request->id_jenis_surat)->nama_surat ?? 'Surat';
            $nim = $user->mahasiswa->nim ?? '-';
            $notificationService = new NotificationService();
            $notificationService->notifyAdminsAndDekan($user->mahasiswa->id_mahasiswa, $jenisSurat, $nim);

            DB::commit();

            return redirect()->route('submission.status')->with('success', 'Pengajuan surat berhasil dikirim.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Terjadi kesalahan saat menyimpan pengajuan: ' . $e->getMessage()])->withInput();
        }
    }
    /**
     * Menampilkan halaman persetujuan surat (untuk Pejabat).
     */
    public function showApproval($id)
    {
        $user = Auth::user();
        
        // Pastikan user adalah pejabat
        if ($user->id_hak_akses != 4 || !$user->pejabat) {
            abort(403, 'Akses ditolak. Hanya pejabat berwenang yang dapat mengakses halaman ini.');
        }

        $pengajuan = PengajuanSurat::with(['mahasiswa.user', 'jenisSurat', 'logStatusSurat'])
                        ->findOrFail($id);

        // Pastikan status surat memang menunggu tanda tangan
        if ($pengajuan->status_saat_ini != 'Menunggu Tanda Tangan') {
            return redirect()->route('dashboard')->with('error', 'Dokumen ini tidak dalam status menunggu tanda tangan.');
        }

        // Ambil tanda tangan digital user
        $digitalSignatures = \App\Models\DigitalSignature::where('user_id', $user->id_user)
                                                        ->where('is_active', true)
                                                        ->get()
                                                        ->filter(function ($signature) {
                                                            $storagePath = storage_path('app/public/' . $signature->path);
                                                            if (!file_exists($storagePath) && $signature->type === 'qrcode') {
                                                                $qrCode = new QrCode($signature->qr_text);
                                                                $writer = new PngWriter();
                                                                $result = $writer->write($qrCode);
                                                                
                                                                $qrImage = imagecreatefromstring($result->getString());
                                                                $logoPath = public_path('logo-ukdw.png');
                                                                
                                                                if (file_exists($logoPath)) {
                                                                    $logo = imagecreatefrompng($logoPath);
                                                                    $qrWidth = imagesx($qrImage);
                                                                    $qrHeight = imagesy($qrImage);
                                                                    
                                                                    $logoSize = min($qrWidth, $qrHeight) * 0.15;
                                                                    $logoResized = imagescale($logo, $logoSize, $logoSize);
                                                                    
                                                                    $logoX = ($qrWidth - $logoSize) / 2;
                                                                    $logoY = ($qrHeight - $logoSize) / 2;
                                                                    
                                                                    $white = imagecolorallocate($qrImage, 255, 255, 255);
                                                                    imagefilledellipse($qrImage, $qrWidth/2, $qrHeight/2, $logoSize + 10, $logoSize + 10, $white);
                                                                    
                                                                    imagecopy($qrImage, $logoResized, $logoX, $logoY, 0, 0, $logoSize, $logoSize);
                                                                    
                                                                    ob_start();
                                                                    imagepng($qrImage);
                                                                    $finalImage = ob_get_contents();
                                                                    ob_end_clean();
                                                                    
                                                                    imagedestroy($qrImage);
                                                                    imagedestroy($logo);
                                                                    imagedestroy($logoResized);
                                                                } else {
                                                                    $finalImage = $result->getString();
                                                                }
                                                                
                                                                Storage::disk('public')->put($signature->path, $finalImage);
                                                                return file_exists(storage_path('app/public/' . $signature->path));
                                                            }
                                                            return file_exists($storagePath);
                                                        });

        return view('submission.approve', compact('pengajuan', 'digitalSignatures'));
    }

    /**
     * Memproses persetujuan surat (Tanda Tangan Digital).
     */
    public function processApproval(Request $request, $id)
    {
        $user = Auth::user();
        
        if ($user->id_hak_akses != 4 || !$user->pejabat) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string|max:500',
            'digital_signature_id' => 'required_if:action,approve|exists:digital_signatures,id',
        ]);

        $pengajuan = PengajuanSurat::with('jenisSurat')->findOrFail($id);
        $perluValidasiDekan = $pengajuan->jenisSurat->perlu_validasi_dekan ?? false;

        DB::beginTransaction();
        try {
            $notificationService = new NotificationService();
            $mahasiswaUserId = $pengajuan->mahasiswa->user->id_user;
            $jenisSurat = $pengajuan->jenisSurat->nama_surat ?? 'Surat';

            if ($request->action == 'approve') {
                // Ambil tanda tangan digital yang dipilih
                $digitalSignature = \App\Models\DigitalSignature::where('id', $request->digital_signature_id)
                                                               ->where('user_id', $user->id_user)
                                                               ->firstOrFail();
                
                $requiresDekanValidation = $perluValidasiDekan;
                
                \Illuminate\Support\Facades\Log::info('=== PROCESS APPROVAL PEJABAT ===', [
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'id_jenis_surat' => $pengajuan->id_jenis_surat,
                    'requiresDekanValidation' => $requiresDekanValidation,
                    'digital_signature_id' => $digitalSignature->id,
                ]);
                
                if ($requiresDekanValidation) {
                    $statusBaru = 'Menunggu Validasi Dekan';
                    $keterangan = 'Surat telah ditandatangani oleh ' . $user->nama_lengkap . ' dan menunggu validasi Dekan.';
                    
                    $pengajuan->digital_signature_id = $digitalSignature->id;
                    
                    \Illuminate\Support\Facades\Log::info('Menyimpan digital_signature_id untuk surat pengunduran diri', [
                        'pengajuan_id' => $pengajuan->id_pengajuan,
                        'digital_signature_id' => $digitalSignature->id,
                    ]);
                    
                    $notificationService->notifyDekan($pengajuan->id_pengajuan, $jenisSurat, $pengajuan->mahasiswa->nim ?? '-');
                    
                    $notificationService->notifyMahasiswa(
                        $mahasiswaUserId,
                        'Tanda Tangan Selesai',
                        "Surat {$jenisSurat} telah ditandatangani oleh {$user->nama_lengkap} dan sedang menunggu validasi Dekan.",
                        route('submission.status'),
                        'info'
                    );
                } else {
                    $statusBaru = 'Menunggu Proses Admin';
                    $keterangan = 'Surat telah ditandatangani secara digital oleh ' . $user->nama_lengkap . ' dan menunggu proses akhir dari admin.';
                    
                    $bulanRomawi = $this->getRomawi(date('n'));
                    $tahun = date('Y');
                    $nomorUrut = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                    $kodeSurat = 'AKd';
                    
                    $pengajuan->nomor_surat_resmi = "$nomorUrut/$kodeSurat/$bulanRomawi/$tahun";
                    
                    $pdfContent = $this->generatePDFContent($pengajuan, $digitalSignature);
                    $pengajuan->file_surat_content = $pdfContent;
                    $pengajuan->file_surat_name = 'surat-' . $pengajuan->id_pengajuan . '.pdf';
                    $pengajuan->file_surat_mime_type = 'application/pdf';
                    $pengajuan->digital_signature_id = $digitalSignature->id;
                    
                    $notificationService->notifyAdminsForFinalProcess($pengajuan->id_pengajuan, $jenisSurat, $pengajuan->mahasiswa->nim ?? '-');
                }
            } else {
                $statusBaru = 'Ditolak';
                $keterangan = 'Pengajuan ditolak oleh pejabat. Alasan: ' . $request->catatan;
                
                // Notify mahasiswa
                $alasan = $request->catatan ?: 'Tidak ada alasan diberikan.';
                $notificationService->notifyPenolakan($mahasiswaUserId, $jenisSurat, $alasan);
            }

            $pengajuan->status_saat_ini = $statusBaru;
            $pengajuan->save();
            
            \Illuminate\Support\Facades\Log::info('Pengajuan disimpan setelah approval pejabat', [
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status_saat_ini' => $pengajuan->status_saat_ini,
                'digital_signature_id' => $pengajuan->digital_signature_id,
            ]);

            // Catat Log
            LogStatusSurat::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status_lama' => 'Menunggu Tanda Tangan',
                'status_baru' => $statusBaru,
                'tgl_perubahan' => now(),
                'diubah_oleh_user' => $user->id_user,
                'keterangan' => $keterangan,
            ]);

            DB::commit();

            if ($request->action == 'approve') {
                return redirect()->route('pejabat.approval')->with('success', $requiresDekanValidation 
                    ? 'Surat berhasil ditandatangani dan sedang menunggu validasi Dekan.'
                    : 'Surat berhasil disetujui dan ditandatangani secara digital.');
            } else {
                return redirect()->route('pejabat.approval')->with('success', 'Surat telah ditolak.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Download completed letter PDF
     */
    public function download($id)
    {
        $user = Auth::user();
        
        if ($user->id_hak_akses != 1 || !$user->mahasiswa) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $pengajuan = PengajuanSurat::where('id_mahasiswa', $user->mahasiswa->id_mahasiswa)
                        ->where('status_saat_ini', 'Selesai')
                        ->findOrFail($id);

        if (!$pengajuan->file_surat_content) {
            abort(404, 'File surat tidak ditemukan');
        }

        return response($pengajuan->file_surat_content)
            ->header('Content-Type', $pengajuan->file_surat_mime_type ?? 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . ($pengajuan->file_surat_name ?? 'surat.pdf') . '"');
    }

    /**
     * Generate PDF content for storage in BLOB
     */
    private function generatePDFContent($pengajuan, $digitalSignature)
    {
        // Load relationships
        $pengajuan->load(['mahasiswa.user', 'mahasiswa.prodi', 'jenisSurat']);
        
        // Generate PDF based on letter type
        switch($pengajuan->jenisSurat->nama_surat ?? null) {
            case JenisSurat::AKTIF_KULIAH:
                return $this->generateActiveStudentPDFContent($pengajuan, $digitalSignature);
            case JenisSurat::ALUMNI:
                return $this->generateAlumniPDFContent($pengajuan, $digitalSignature);
            case JenisSurat::PENGUNDURAN_DIRI:
                return $this->generateWithdrawalPDFContent($pengajuan, $digitalSignature);
            case JenisSurat::LULUS:
                return $this->generateStatementLetterPDFContent($pengajuan, $digitalSignature);
            case JenisSurat::CUTI_AKADEMIK:
                return $this->generateAcademicLeavePDFContent($pengajuan, $digitalSignature);
            default:
                throw new \Exception('Jenis surat tidak ditemukan');
        }
    }

    /**
     * Generate PDF content with specific signatory (for admin final process)
     */
    private function generatePDFContentWithSignatory($pengajuan, $digitalSignature, $signatoryUser)
    {
        $pengajuan->load(['mahasiswa.user', 'mahasiswa.prodi', 'jenisSurat']);
        
        $signatory = [
            'nama' => $signatoryUser->nama_lengkap,
            'nik' => $signatoryUser->pejabat->nip ?? '-',
            'pangkat' => $signatoryUser->pejabat->pangkat ?? '-',
            'jabatan' => $signatoryUser->pejabat->jabatan->nama_jabatan ?? 'Pejabat Berwenang',
        ];

        switch($pengajuan->jenisSurat->nama_surat ?? null) {
            case JenisSurat::AKTIF_KULIAH:
                return $this->generateActiveStudentPDFContentWithSignatory($pengajuan, $digitalSignature, $signatory);
            case JenisSurat::ALUMNI:
                return $this->generateAlumniPDFContentWithSignatory($pengajuan, $digitalSignature, $signatory);
            case JenisSurat::PENGUNDURAN_DIRI:
                return $this->generateWithdrawalPDFContentWithSignatory($pengajuan, $digitalSignature, $signatory);
            case JenisSurat::LULUS:
                return $this->generateStatementLetterPDFContentWithSignatory($pengajuan, $digitalSignature, $signatory);
            case JenisSurat::CUTI_AKADEMIK:
                return $this->generateAcademicLeavePDFContentWithSignatory($pengajuan, $digitalSignature, $signatory);
            default:
                throw new \Exception('Jenis surat tidak ditemukan');
        }
    }

    private function prepareDigitalSignatureData($digitalSignature)
    {
        $fullPath = storage_path('app/public/' . $digitalSignature->path);
        
        if (!file_exists($fullPath) && $digitalSignature->type === 'qrcode') {
            $qrText = $digitalSignature->qr_text ?: 'Digital Signature - ' . now()->format('Y-m-d H:i:s');
            $qrCode = new QrCode($qrText);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $qrString = $result->getString();
            
            if (empty($qrString)) {
                throw new \Exception('Failed to generate QR code');
            }
            
            $qrImage = imagecreatefromstring($qrString);
            if ($qrImage === false) {
                throw new \Exception('Failed to create QR code image');
            }
            
            $logoPath = public_path('logo-ukdw.png');
            $finalImage = $qrString;
            
            if (file_exists($logoPath)) {
                $logo = @imagecreatefrompng($logoPath);
                if ($logo !== false) {
                    $qrWidth = imagesx($qrImage);
                    $qrHeight = imagesy($qrImage);
                    
                    $logoSize = min($qrWidth, $qrHeight) * 0.15;
                    $logoResized = imagescale($logo, $logoSize, $logoSize);
                    
                    if ($logoResized !== false) {
                        $logoX = ($qrWidth - $logoSize) / 2;
                        $logoY = ($qrHeight - $logoSize) / 2;
                        
                        $white = imagecolorallocate($qrImage, 255, 255, 255);
                        imagefilledellipse($qrImage, $qrWidth/2, $qrHeight/2, $logoSize + 10, $logoSize + 10, $white);
                        
                        imagecopy($qrImage, $logoResized, $logoX, $logoY, 0, 0, $logoSize, $logoSize);
                    }
                    
                    imagedestroy($logo);
                    if ($logoResized !== false) {
                        imagedestroy($logoResized);
                    }
                }
            }
            
            ob_start();
            imagepng($qrImage);
            $finalImage = ob_get_contents();
            ob_end_clean();
            
            imagedestroy($qrImage);
            
            Storage::disk('public')->put($digitalSignature->path, $finalImage);
            $fullPath = storage_path('app/public/' . $digitalSignature->path);
        }
        
        if (!file_exists($fullPath)) {
            throw new \Exception('Signature file not found: ' . $fullPath);
        }
        
        return [
            'type' => $digitalSignature->type,
            'path' => $digitalSignature->path,
            'qr_text' => $digitalSignature->qr_text,
            'base64' => base64_encode(file_get_contents($fullPath)),
        ];
    }

    private function generateActiveStudentPDFContent($pengajuan, $digitalSignature)
    {
        $mahasiswa = $pengajuan->mahasiswa;
        $user = $mahasiswa->user;
        $currentUser = Auth::user(); // Pejabat yang menandatangani
        
        $data = [
            'student' => [
                'nama' => $user->nama_lengkap,
                'tempat_lahir' => $mahasiswa->tempat_lahir ?? 'Yogyakarta',
                'tanggal_lahir' => $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->locale('id')->translatedFormat('d F Y') : '01 Januari 2000',
                'nim' => $mahasiswa->nim,
                'fakultas' => $mahasiswa->prodi->fakultas->nama_fakultas ?? 'Teknologi Informasi',
                'prodi' => $mahasiswa->prodi->nama_prodi ?? 'Teknik Informatika',
                'semester_aktif' => 'Gasal 2025/2026',
            ],
            'parent' => [
                'nama' => $mahasiswa->nama_orang_tua ?? 'Nama Orang Tua',
                'nip' => $mahasiswa->nip_orang_tua ?? '123456789',
                'pangkat' => $mahasiswa->pangkat_orang_tua ?? 'Pembina, IV/a',
                'instansi' => $mahasiswa->instansi_orang_tua ?? 'Instansi Kerja',
            ],
            'signatory' => [
                'nama' => $currentUser->nama_lengkap,
                'nik' => $currentUser->pejabat->nip ?? '-',
                'pangkat' => $currentUser->pejabat->pangkat ?? '-',
                'jabatan' => $currentUser->pejabat->jabatan->nama_jabatan ?? 'Pejabat Berwenang',
            ],
            'digital_signature' => $this->prepareDigitalSignatureData($digitalSignature),
            'nomor_surat' => $pengajuan->nomor_surat_resmi,
            'tanggal_surat' => now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.active-student-certificate', $data);
        return $pdf->output();
    }

    private function generateAlumniPDFContent($pengajuan, $digitalSignature)
    {
        $mahasiswa = $pengajuan->mahasiswa;
        $user = $mahasiswa->user;
        $currentUser = Auth::user(); // Pejabat yang menandatangani
        
        $data = [
            'alumni' => [
                'nama' => $user->nama_lengkap,
                'tempat_lahir' => $mahasiswa->tempat_lahir ?? 'Yogyakarta',
                'tanggal_lahir' => $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->locale('id')->translatedFormat('d F Y') : '01 Januari 2000',
                'nim' => $mahasiswa->nim,
                'fakultas' => $mahasiswa->prodi->fakultas->nama_fakultas ?? 'Teknologi Informasi',
                'prodi' => $mahasiswa->prodi->nama_prodi ?? 'Teknik Informatika',
                'status' => 'Lulus',
                'tanggal_lulus' => '20 September 2023',
                'nomor_ijazah' => '1612 T.I 2023',
            ],
            'signatory' => [
                'nama' => $currentUser->nama_lengkap,
                'nik' => $currentUser->pejabat->nip ?? '-',
                'pangkat' => $currentUser->pejabat->pangkat ?? '-',
                'jabatan' => $currentUser->pejabat->jabatan->nama_jabatan ?? 'Pejabat Berwenang',
            ],
            'digital_signature' => $this->prepareDigitalSignatureData($digitalSignature),
            'nomor_surat' => $pengajuan->nomor_surat_resmi,
            'tanggal_surat' => now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.alumni-certificate', $data);
        return $pdf->output();
    }

    private function generateWithdrawalPDFContent($pengajuan, $digitalSignature)
    {
        $mahasiswa = $pengajuan->mahasiswa;
        $user = $mahasiswa->user;
        $currentUser = Auth::user(); // Pejabat yang menandatangani
        
        $data = [
            'student' => [
                'nama' => $user->nama_lengkap,
                'tempat_lahir' => $mahasiswa->tempat_lahir ?? 'Yogyakarta',
                'tanggal_lahir' => $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->locale('id')->translatedFormat('d F Y') : '01 Januari 2000',
                'nim' => $mahasiswa->nim,
                'fakultas' => $mahasiswa->prodi->fakultas->nama_fakultas ?? 'Teknologi Informasi',
                'prodi' => $mahasiswa->prodi->nama_prodi ?? 'Teknik Informatika',
                'akreditasi' => 'B',
                'sk_ban_pt' => '1234/SK/BAN-PT/Ak-PPJ/S/V/2023',
                'semester_awal' => 'Gasal 2020/2021',
                'semester_akhir' => 'Genap 2022/2023',
                'tanggal_mundur' => '15 Januari 2023',
                'referensi_surat' => 'Surat Keterangan Wakil Rektor Bidang Akademik dan Riset',
            ],
            'signatory' => [
                'nama' => $currentUser->nama_lengkap,
                'nik' => $currentUser->pejabat->nip ?? '-',
                'pangkat' => $currentUser->pejabat->pangkat ?? '-',
                'jabatan' => $currentUser->pejabat->jabatan->nama_jabatan ?? 'Pejabat Berwenang',
            ],
            'digital_signature' => $this->prepareDigitalSignatureData($digitalSignature),
            'nomor_surat' => $pengajuan->nomor_surat_resmi,
            'tanggal_surat' => now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.withdrawal-certificate', $data);
        return $pdf->output();
    }

    private function generateStatementLetterPDFContent($pengajuan, $digitalSignature)
    {
        $mahasiswa = $pengajuan->mahasiswa;
        $user = $mahasiswa->user;
        $currentUser = Auth::user(); // Pejabat yang menandatangani
        
        $data = [
            'student' => [
                'full_name' => strtoupper($user->nama_lengkap),
                'date_of_birth' => $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->format('F d, Y') : 'January 01, 2000',
                'student_id' => $mahasiswa->nim,
                'institution' => 'Universitas Kristen Duta Wacana',
                'faculty' => $mahasiswa->prodi->fakultas->nama_fakultas ?? 'Information Technology',
                'department' => $mahasiswa->prodi->nama_prodi ?? 'Informatics',
                'study_period' => 'August 2020 - August 2024',
                'degree_awarded' => 'Bachelor of Informatics / B.Inf.',
                'graduation_date' => 'August 2024',
            ],
            'signatory' => [
                'name' => $currentUser->nama_lengkap,
                'position' => $currentUser->pejabat->jabatan->nama_jabatan ?? 'Authorized Official',
            ],
            'digital_signature' => $this->prepareDigitalSignatureData($digitalSignature),
            'document_number' => $pengajuan->nomor_surat_resmi,
            'issue_date' => now()->format('F d, Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.statement-letter', $data);
        return $pdf->output();
    }

    private function generateAcademicLeavePDFContent($pengajuan, $digitalSignature)
    {
        $mahasiswa = $pengajuan->mahasiswa;
        $user = $mahasiswa->user;
        $currentUser = Auth::user();

        $data = [
            'student' => [
                'nama' => $user->nama_lengkap,
                'tempat_lahir' => $mahasiswa->tempat_lahir ?? 'Yogyakarta',
                'tanggal_lahir' => $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->locale('id')->translatedFormat('d F Y') : '01 Januari 2000',
                'nim' => $mahasiswa->nim,
                'fakultas' => $mahasiswa->prodi->fakultas->nama_fakultas ?? 'Teknologi Informasi',
                'prodi' => $mahasiswa->prodi->nama_prodi ?? 'Teknik Informatika',
                'angkatan' => $mahasiswa->angkatan ?? '-',
            ],
            'leave' => [
                'keterangan' => $pengajuan->keterangan_mahasiswa ?: 'Cuti akademik sesuai permohonan mahasiswa.',
            ],
            'signatory' => [
                'nama' => $currentUser->nama_lengkap,
                'nik' => $currentUser->pejabat->nip ?? '-',
                'pangkat' => $currentUser->pejabat->pangkat ?? '-',
                'jabatan' => $currentUser->pejabat->jabatan->nama_jabatan ?? 'Pejabat Berwenang',
            ],
            'digital_signature' => $this->prepareDigitalSignatureData($digitalSignature),
            'nomor_surat' => $pengajuan->nomor_surat_resmi,
            'tanggal_surat' => now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.academic-leave-certificate', $data);
        return $pdf->output();
    }

    // PDF Generation methods with custom signatory (for admin final process)
    
    private function generateActiveStudentPDFContentWithSignatory($pengajuan, $digitalSignature, $signatory)
    {
        $mahasiswa = $pengajuan->mahasiswa;
        $user = $mahasiswa->user;
        
        $data = [
            'student' => [
                'nama' => $user->nama_lengkap,
                'tempat_lahir' => $mahasiswa->tempat_lahir ?? 'Yogyakarta',
                'tanggal_lahir' => $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->locale('id')->translatedFormat('d F Y') : '01 Januari 2000',
                'nim' => $mahasiswa->nim,
                'fakultas' => $mahasiswa->prodi->fakultas->nama_fakultas ?? 'Teknologi Informasi',
                'prodi' => $mahasiswa->prodi->nama_prodi ?? 'Teknik Informatika',
                'semester_aktif' => 'Gasal 2025/2026',
            ],
            'parent' => [
                'nama' => $mahasiswa->nama_orang_tua ?? 'Nama Orang Tua',
                'nip' => $mahasiswa->nip_orang_tua ?? '123456789',
                'pangkat' => $mahasiswa->pangkat_orang_tua ?? 'Pembina, IV/a',
                'instansi' => $mahasiswa->instansi_orang_tua ?? 'Instansi Kerja',
            ],
            'signatory' => $signatory,
            'digital_signature' => $this->prepareDigitalSignatureData($digitalSignature),
            'nomor_surat' => $pengajuan->nomor_surat_resmi,
            'tanggal_surat' => now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.active-student-certificate', $data);
        return $pdf->output();
    }

    private function generateAlumniPDFContentWithSignatory($pengajuan, $digitalSignature, $signatory)
    {
        $mahasiswa = $pengajuan->mahasiswa;
        $user = $mahasiswa->user;
        
        $data = [
            'alumni' => [
                'nama' => $user->nama_lengkap,
                'tempat_lahir' => $mahasiswa->tempat_lahir ?? 'Yogyakarta',
                'tanggal_lahir' => $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->locale('id')->translatedFormat('d F Y') : '01 Januari 2000',
                'nim' => $mahasiswa->nim,
                'fakultas' => $mahasiswa->prodi->fakultas->nama_fakultas ?? 'Teknologi Informasi',
                'prodi' => $mahasiswa->prodi->nama_prodi ?? 'Teknik Informatika',
                'status' => 'Lulus',
                'tanggal_lulus' => '20 September 2023',
                'nomor_ijazah' => '1612 T.I 2023',
            ],
            'signatory' => $signatory,
            'digital_signature' => $this->prepareDigitalSignatureData($digitalSignature),
            'nomor_surat' => $pengajuan->nomor_surat_resmi,
            'tanggal_surat' => now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.alumni-certificate', $data);
        return $pdf->output();
    }

    private function generateWithdrawalPDFContentWithSignatory($pengajuan, $digitalSignature, $signatory)
    {
        $mahasiswa = $pengajuan->mahasiswa;
        $user = $mahasiswa->user;
        
        $data = [
            'student' => [
                'nama' => $user->nama_lengkap,
                'tempat_lahir' => $mahasiswa->tempat_lahir ?? 'Yogyakarta',
                'tanggal_lahir' => $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->locale('id')->translatedFormat('d F Y') : '01 Januari 2000',
                'nim' => $mahasiswa->nim,
                'fakultas' => $mahasiswa->prodi->fakultas->nama_fakultas ?? 'Teknologi Informasi',
                'prodi' => $mahasiswa->prodi->nama_prodi ?? 'Teknik Informatika',
                'akreditasi' => 'B',
                'sk_ban_pt' => '1234/SK/BAN-PT/Ak-PPJ/S/V/2023',
                'semester_awal' => 'Gasal 2020/2021',
                'semester_akhir' => 'Genap 2022/2023',
                'tanggal_mundur' => '15 Januari 2023',
                'referensi_surat' => 'Surat Keterangan Wakil Rektor Bidang Akademik dan Riset',
            ],
            'signatory' => $signatory,
            'digital_signature' => $this->prepareDigitalSignatureData($digitalSignature),
            'nomor_surat' => $pengajuan->nomor_surat_resmi,
            'tanggal_surat' => now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.withdrawal-certificate', $data);
        return $pdf->output();
    }

    private function generateStatementLetterPDFContentWithSignatory($pengajuan, $digitalSignature, $signatory)
    {
        $mahasiswa = $pengajuan->mahasiswa;
        $user = $mahasiswa->user;
        
        $data = [
            'student' => [
                'full_name' => strtoupper($user->nama_lengkap),
                'date_of_birth' => $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->format('F d, Y') : 'January 01, 2000',
                'student_id' => $mahasiswa->nim,
                'institution' => 'Universitas Kristen Duta Wacana',
                'faculty' => $mahasiswa->prodi->fakultas->nama_fakultas ?? 'Information Technology',
                'department' => $mahasiswa->prodi->nama_prodi ?? 'Informatics',
                'study_period' => 'August 2020 - August 2024',
                'degree_awarded' => 'Bachelor of Informatics / B.Inf.',
                'graduation_date' => 'August 2024',
            ],
            'signatory' => [
                'name' => $signatory['nama'],
                'position' => $signatory['jabatan'],
            ],
            'digital_signature' => $this->prepareDigitalSignatureData($digitalSignature),
            'document_number' => $pengajuan->nomor_surat_resmi,
            'issue_date' => now()->format('F d, Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.statement-letter', $data);
        return $pdf->output();
    }

    private function generateAcademicLeavePDFContentWithSignatory($pengajuan, $digitalSignature, $signatory)
    {
        $mahasiswa = $pengajuan->mahasiswa;
        $user = $mahasiswa->user;

        $data = [
            'student' => [
                'nama' => $user->nama_lengkap,
                'tempat_lahir' => $mahasiswa->tempat_lahir ?? 'Yogyakarta',
                'tanggal_lahir' => $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->locale('id')->translatedFormat('d F Y') : '01 Januari 2000',
                'nim' => $mahasiswa->nim,
                'fakultas' => $mahasiswa->prodi->fakultas->nama_fakultas ?? 'Teknologi Informasi',
                'prodi' => $mahasiswa->prodi->nama_prodi ?? 'Teknik Informatika',
                'angkatan' => $mahasiswa->angkatan ?? '-',
            ],
            'leave' => [
                'keterangan' => $pengajuan->keterangan_mahasiswa ?: 'Cuti akademik sesuai permohonan mahasiswa.',
            ],
            'signatory' => $signatory,
            'digital_signature' => $this->prepareDigitalSignatureData($digitalSignature),
            'nomor_surat' => $pengajuan->nomor_surat_resmi,
            'tanggal_surat' => now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.academic-leave-certificate', $data);
        return $pdf->output();
    }

    /**
     * Menampilkan daftar pengajuan yang perlu disetujui oleh Pejabat.
     */
    public function approvalList()
    {
        $user = Auth::user();
        
        if ($user->id_hak_akses != 4 || !$user->pejabat) {
            abort(403, 'Akses ditolak.');
        }

        $pengajuan = PengajuanSurat::with(['mahasiswa.user', 'jenisSurat'])
                        ->where('status_saat_ini', 'Menunggu Tanda Tangan')
                        ->latest()
                        ->paginate(10);

        return view('pejabat.approval_list', compact('pengajuan'));
    }

    /**
     * Menampilkan riwayat persetujuan oleh Pejabat.
     */
    public function approvalHistory()
    {
        $user = Auth::user();
        
        if ($user->id_hak_akses != 4 || !$user->pejabat) {
            abort(403, 'Akses ditolak.');
        }

        // Cari pengajuan yang pernah diproses oleh pejabat ini (via LogStatusSurat)
        // Atau sederhananya, tampilkan semua yang statusnya Selesai/Ditolak (asumsi pejabat melihat semua)
        // Idealnya ada relasi 'disetujui_oleh' di tabel pengajuan atau tabel pivot
        
        // Untuk MVP: Tampilkan semua yang Selesai/Ditolak
        $pengajuan = PengajuanSurat::with(['mahasiswa.user', 'jenisSurat'])
                        ->whereIn('status_saat_ini', ['Selesai', 'Ditolak'])
                        ->latest()
                        ->paginate(10);

        return view('pejabat.approval_history', compact('pengajuan'));
    }

    // Helper untuk konversi bulan ke romawi
    private function getRomawi($bulan) {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$bulan];
    }

    /**
     * Menampilkan semua pengajuan untuk Admin.
     */
    public function adminIndex(Request $request)
    {
        $user = Auth::user();
        
        // Izinkan admin dan staf untuk melihat daftar pengajuan
        if (!in_array($user->id_hak_akses, [2, 3])) {
            abort(403, 'Akses ditolak.');
        }

        $query = PengajuanSurat::with(['mahasiswa.user', 'jenisSurat'])
                    ->whereNotIn('status_saat_ini', ['Selesai']) // Exclude completed
                    ->latest();

        // Filter status if needed
        if ($request->has('status') && $request->status != '') {
            $query->where('status_saat_ini', $request->status);
        }

        // Filter search if needed
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('mahasiswa.user', function($u) use ($search) {
                    $u->where('nama_lengkap', 'like', "%{$search}%");
                })->orWhereHas('jenisSurat', function($j) use ($search) {
                    $j->where('nama_surat', 'like', "%{$search}%");
                })->orWhereHas('mahasiswa', function($m) use ($search) {
                    $m->where('nim', 'like', "%{$search}%");
                });
            });
        }

        $pengajuan = $query->paginate(10);

        return view('admin.submission.index', compact('pengajuan'));
    }

    /**
     * Menampilkan detail pengajuan untuk Admin.
     */
    public function adminDetail($id)
    {
        $user = Auth::user();
        
        // Izinkan admin dan staf untuk melihat detail
        if (!in_array($user->id_hak_akses, [2, 3])) {
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
        ])->findOrFail($id);

        return view('admin.submission.detail', compact('pengajuan'));
    }

    /**
     * Cetak PDF surat yang sudah jadi untuk Admin.
     */
    public function adminPrint($id)
    {
        $user = Auth::user();
        
        if ($user->id_hak_akses != 2) {
            abort(403, 'Akses ditolak.');
        }

        $pengajuan = PengajuanSurat::findOrFail($id);

        // Cek apakah surat sudah selesai dan ada file PDF
        if ($pengajuan->status_saat_ini !== 'Selesai' || !$pengajuan->file_surat_content) {
            abort(404, 'Surat belum selesai atau file tidak tersedia.');
        }

        // Return file PDF yang sudah ada
        return response($pengajuan->file_surat_content)
            ->header('Content-Type', $pengajuan->file_surat_mime_type ?? 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . ($pengajuan->file_surat_name ?? 'surat.pdf') . '"');
    }

    public function validateSubmission(Request $request, $id)
    {
        $user = Auth::user();
        
        // Hanya Dekan yang bisa validasi
        if ($user->id_hak_akses != 3) {
            abort(403, 'Akses ditolak.');
        }

        $pengajuan = PengajuanSurat::findOrFail($id);
        
        if ($pengajuan->status_saat_ini != 'Menunggu Validasi Dekan') {
            return back()->with('error', 'Pengajuan tidak dapat divalidasi pada status saat ini.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();
            
            $action = $request->action;
            $catatan = $request->catatan;
            
            \Illuminate\Support\Facades\Log::info('=== VALIDATE SUBMISSION DEKAN ===', [
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'id_jenis_surat' => $pengajuan->id_jenis_surat,
                'action' => $action,
                'digital_signature_id_field' => $pengajuan->digital_signature_id,
            ]);
            
            if ($action === 'approve') {
                $bulanRomawi = $this->getRomawi(date('n'));
                $tahun = date('Y');
                $nomorUrut = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                $kodeSurat = 'AKd';
                
                $pengajuan->nomor_surat_resmi = "$nomorUrut/$kodeSurat/$bulanRomawi/$tahun";
                
                $pengajuan->load('digitalSignature');
                $digitalSignature = $pengajuan->digitalSignature;
                
                \Illuminate\Support\Facades\Log::info('Digital Signature Check', [
                    'digital_signature_id' => $pengajuan->digital_signature_id,
                    'digitalSignature_exists' => $digitalSignature ? 'YES' : 'NO',
                    'digitalSignature_data' => $digitalSignature ? [
                        'id' => $digitalSignature->id,
                        'type' => $digitalSignature->type,
                        'user_id' => $digitalSignature->user_id,
                    ] : null,
                ]);
                
                if (!$digitalSignature) {
                    \Illuminate\Support\Facades\Log::error('ERROR: Digital Signature tidak ditemukan', [
                        'id_pengajuan' => $pengajuan->id_pengajuan,
                        'digital_signature_id' => $pengajuan->digital_signature_id,
                    ]);
                    throw new \Exception('Tanda tangan digital tidak ditemukan untuk surat ini. Digital Signature ID: ' . ($pengajuan->digital_signature_id ?? 'NULL'));
                }
                
                \Illuminate\Support\Facades\Log::info('Mulai generate PDF untuk surat pengunduran diri', [
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'id_jenis_surat' => $pengajuan->id_jenis_surat,
                ]);
                
                try {
                    $pdfContent = $this->generatePDFContent($pengajuan, $digitalSignature);
                    
                    \Illuminate\Support\Facades\Log::info('PDF berhasil di-generate', [
                        'pdf_size' => strlen($pdfContent),
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('ERROR: Gagal generate PDF', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    throw $e;
                }
                
                if (!$pdfContent) {
                    \Illuminate\Support\Facades\Log::error('ERROR: PDF Content kosong');
                    throw new \Exception('Gagal membuat file PDF surat.');
                }
                
                $pengajuan->file_surat_content = $pdfContent;
                $pengajuan->file_surat_name = 'surat-' . $pengajuan->id_pengajuan . '.pdf';
                $pengajuan->file_surat_mime_type = 'application/pdf';
                
                $pengajuan->status_saat_ini = 'Menunggu Proses Admin';
                
                \Illuminate\Support\Facades\Log::info('Sebelum save pengajuan', [
                    'file_surat_content_size' => strlen($pengajuan->file_surat_content),
                    'status' => $pengajuan->status_saat_ini,
                ]);
                
                $pengajuan->save();
                
                \App\Models\LogStatusSurat::create([
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'status_lama' => 'Menunggu Validasi Dekan',
                    'status_baru' => 'Menunggu Proses Admin',
                    'tgl_perubahan' => now(),
                    'keterangan' => $catatan ?: 'Disetujui oleh Dekan dan menunggu proses akhir admin.',
                ]);
                
                $notificationService = new NotificationService();
                $jenisSurat = $pengajuan->jenisSurat->nama_surat ?? 'Surat';
                
                $notificationService->notifyAdminsForFinalProcess($pengajuan->id_pengajuan, $jenisSurat, $pengajuan->mahasiswa->nim ?? '-');
                
                $message = 'Pengajuan berhasil disetujui dan surat telah selesai.';
            } else {
                $pengajuan->update(['status_saat_ini' => 'Ditolak']);
                
                \App\Models\LogStatusSurat::create([
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'status_lama' => 'Menunggu Validasi Dekan',
                    'status_baru' => 'Ditolak',
                    'tgl_perubahan' => now(),
                    'keterangan' => $catatan ?: 'Ditolak oleh Dekan',
                ]);
                
                $notificationService = new NotificationService();
                $mahasiswaUserId = $pengajuan->mahasiswa->user->id_user;
                $jenisSurat = $pengajuan->jenisSurat->nama_surat ?? 'Surat';
                $alasan = $catatan ?: 'Tidak ada alasan diberikan.';
                $notificationService->notifyPenolakan($mahasiswaUserId, $jenisSurat, $alasan);
                
                $message = 'Pengajuan berhasil ditolak.';
            }
            
            DB::commit();
            
            return redirect()->route('admin.submission.index')->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memproses validasi: ' . $e->getMessage());
        }
    }

    /**
     * Admin verifies submission
     * Flow: Menunggu Verifikasi Admin -> Menunggu Tanda Tangan (Pejabat)
     */
    public function adminVerify(Request $request, $id)
    {
        $user = Auth::user();
        
        if ($user->id_hak_akses != 2) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanSurat::with('jenisSurat')->findOrFail($id);
        
        if ($pengajuan->status_saat_ini != 'Menunggu Verifikasi Admin') {
            return back()->with('error', 'Pengajuan tidak dapat diverifikasi pada status saat ini.');
        }

        DB::beginTransaction();
        try {
            if ($request->action == 'approve') {
                $statusBaru = 'Menunggu Tanda Tangan';
                $keterangan = 'Pengajuan diverifikasi oleh admin dan diteruskan ke Kepala Biro untuk ditandatangani.';
                
                $notificationService = new NotificationService();
                $notificationService->notifyPejabats($pengajuan->id_pengajuan, $pengajuan->jenisSurat->nama_surat ?? 'Surat', $pengajuan->mahasiswa->nim ?? '-');
                
                $message = 'Pengajuan berhasil diverifikasi dan diteruskan ke Kepala Biro untuk ditandatangani.';
            } else {
                $statusBaru = 'Ditolak';
                $keterangan = 'Pengajuan ditolak. Alasan: ' . $request->catatan;
                $message = 'Pengajuan berhasil ditolak.';
                
                $notificationService = new NotificationService();
                $notificationService->notifyPenolakan($pengajuan->mahasiswa->user->id_user, $pengajuan->jenisSurat->nama_surat ?? 'Surat', $request->catatan ?: 'Tidak ada alasan diberikan.');
            }

            $pengajuan->status_saat_ini = $statusBaru;
            $pengajuan->save();

            LogStatusSurat::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status_lama' => 'Menunggu Verifikasi Admin',
                'status_baru' => $statusBaru,
                'tgl_perubahan' => now(),
                'diubah_oleh_user' => Auth::id(),
                'keterangan' => $keterangan,
            ]);

            DB::commit();

            return redirect()->route('admin.submission.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Proses akhir surat oleh Admin (cetak/kirim email)
     * Flow: Menunggu Proses Admin -> Selesai
     */
    public function finalProcess(Request $request, $id)
    {
        $user = Auth::user();
        
        if ($user->id_hak_akses != 2) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'action' => 'required|in:cetak_email,cetak,kirim_email',
        ]);

        $pengajuan = PengajuanSurat::with(['mahasiswa.user', 'jenisSurat', 'digitalSignature'])->findOrFail($id);
        
        \Illuminate\Support\Facades\Log::info('=== FINAL PROCESS ADMIN ===', [
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'id_jenis_surat' => $pengajuan->id_jenis_surat,
            'status' => $pengajuan->status_saat_ini,
            'digital_signature_id' => $pengajuan->digital_signature_id,
            'has_file_surat_content' => $pengajuan->file_surat_content ? 'YES' : 'NO',
            'file_surat_content_size' => $pengajuan->file_surat_content ? strlen($pengajuan->file_surat_content) : 0,
        ]);
        
        if ($pengajuan->status_saat_ini != 'Menunggu Proses Admin') {
            return back()->with('error', 'Surat tidak dapat diproses pada status saat ini.');
        }

        try {
            DB::beginTransaction();
            
            $action = $request->action;
            $jenisSurat = $pengajuan->jenisSurat->nama_surat ?? 'Surat';
            $mahasiswaUserId = $pengajuan->mahasiswa->user->id_user;
            
            // Generate PDF jika belum ada
            if (!$pengajuan->file_surat_content) {
                \Illuminate\Support\Facades\Log::info('File PDF belum ada, akan generate', [
                    'nomor_surat_resmi' => $pengajuan->nomor_surat_resmi,
                    'digitalSignature_loaded' => $pengajuan->digitalSignature ? 'YES' : 'NO',
                ]);
                
                if (!$pengajuan->nomor_surat_resmi) {
                    $bulanRomawi = $this->getRomawi(date('n'));
                    $tahun = date('Y');
                    $nomorUrut = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                    $kodeSurat = 'AKd';
                    $pengajuan->nomor_surat_resmi = "$nomorUrut/$kodeSurat/$bulanRomawi/$tahun";
                    \Illuminate\Support\Facades\Log::info('Nomor surat dibuat', ['nomor' => $pengajuan->nomor_surat_resmi]);
                }
                
                if (!$pengajuan->digitalSignature) {
                    \Illuminate\Support\Facades\Log::error('ERROR: Digital Signature tidak ditemukan di finalProcess', [
                        'id_pengajuan' => $pengajuan->id_pengajuan,
                        'digital_signature_id' => $pengajuan->digital_signature_id,
                    ]);
                    throw new \Exception('Tanda tangan digital tidak ditemukan. Surat belum ditandatangani oleh pejabat.');
                }
                
                \Illuminate\Support\Facades\Log::info('Digital Signature ditemukan', [
                    'id' => $pengajuan->digitalSignature->id,
                    'type' => $pengajuan->digitalSignature->type,
                    'user_id' => $pengajuan->digitalSignature->user_id,
                ]);
                
                // Gunakan data Pejabat dari digital signature, bukan Auth::user()
                $signatoryUser = $pengajuan->digitalSignature->user;
                
                \Illuminate\Support\Facades\Log::info('Mulai generate PDF dengan signatory', [
                    'signatory_user_id' => $signatoryUser->id_user,
                    'signatory_name' => $signatoryUser->nama_lengkap,
                ]);
                
                try {
                    $pdfContent = $this->generatePDFContentWithSignatory($pengajuan, $pengajuan->digitalSignature, $signatoryUser);
                    
                    \Illuminate\Support\Facades\Log::info('PDF berhasil di-generate di finalProcess', [
                        'pdf_size' => strlen($pdfContent),
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('ERROR: Gagal generate PDF di finalProcess', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    throw $e;
                }
                
                if (!$pdfContent) {
                    \Illuminate\Support\Facades\Log::error('ERROR: PDF Content kosong di finalProcess');
                    throw new \Exception('Gagal membuat file PDF surat.');
                }
                
                $pengajuan->file_surat_content = $pdfContent;
                $pengajuan->file_surat_name = 'surat-' . $pengajuan->id_pengajuan . '.pdf';
                $pengajuan->file_surat_mime_type = 'application/pdf';
                $pengajuan->save();
            }
            
            // Validasi file PDF tersedia sebelum melanjutkan proses
            if (!$pengajuan->file_surat_content) {
                throw new \Exception('File surat tidak tersedia. Proses tidak dapat dilanjutkan.');
            }
            
            if ($action === 'kirim_email' || $action === 'cetak_email') {
                if ($pengajuan->file_surat_content) {
                    Mail::to($pengajuan->mahasiswa->user->email)->send(new \App\Mail\SuratMail($pengajuan));
                }
            }
            
            $keteranganLog = match($action) {
                'cetak' => 'Surat siap dicetak oleh admin.',
                'kirim_email' => 'Surat dikirim ke email mahasiswa.',
                'cetak_email' => 'Surat dikirim ke email mahasiswa dan siap dicetak.',
            };
            
            $pengajuan->status_saat_ini = 'Selesai';
            $pengajuan->save();

            if ($this->isJenisSurat($pengajuan, JenisSurat::CUTI_AKADEMIK)) {
                $pengajuan->mahasiswa->update([
                    'status_mahasiswa' => Mahasiswa::STATUS_CUTI,
                ]);
            }

            LogStatusSurat::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status_lama' => 'Menunggu Proses Admin',
                'status_baru' => 'Selesai',
                'tgl_perubahan' => now(),
                'diubah_oleh_user' => Auth::id(),
                'keterangan' => $keteranganLog,
            ]);

            $notificationService = new NotificationService();
            $notificationService->notifySuratSiap($mahasiswaUserId, $jenisSurat, $pengajuan->nomor_surat_resmi);

            DB::commit();

            $message = 'Surat berhasil diproses dan status diubah menjadi Selesai.';
            return redirect()->route('admin.submission.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
