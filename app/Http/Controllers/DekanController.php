<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\LogStatusSurat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class DekanController extends Controller
{
    /**
     * Menampilkan daftar pengajuan yang perlu divalidasi oleh Dekan.
     */
    public function validationList()
    {
        $this->authorizeDekan();

        $pengajuan = PengajuanSurat::with(['mahasiswa.user', 'jenisSurat'])
                        ->where('status_saat_ini', 'Menunggu Validasi Dekan')
                        ->latest()
                        ->paginate(10);

        return view('dekan.validation.index', compact('pengajuan'));
    }

    /**
     * Memproses validasi pengajuan Dekan (Terima / Tolak).
     * Dekan memvalidasi SETELAH Pejabat menandatangani.
     */
    public function processValidation(Request $request, $id)
    {
        $this->authorizeDekan();

        $request->validate([
            'action' => 'required|in:accept,reject',
            'catatan' => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanSurat::with(['mahasiswa.user', 'jenisSurat', 'digitalSignature'])->findOrFail($id);

        \Illuminate\Support\Facades\Log::info('Dekan Validation Debug', [
            'id' => $id,
            'status' => $pengajuan->status_saat_ini,
            'jenis_surat' => $pengajuan->jenisSurat->nama_surat ?? 'unknown',
            'digital_sig' => $pengajuan->digitalSignature ? [
                'id' => $pengajuan->digitalSignature->id,
                'type' => $pengajuan->digitalSignature->type,
                'path' => $pengajuan->digitalSignature->path,
            ] : null,
            'action' => $request->action
        ]);

        // Pastikan status masih Menunggu Validasi Dekan
        if ($pengajuan->status_saat_ini != 'Menunggu Validasi Dekan') {
            return back()->with('error', 'Status pengajuan sudah berubah.');
        }

        DB::beginTransaction();
        try {
            $notificationService = new NotificationService();
            $mahasiswaUserId = $pengajuan->mahasiswa->user->id_user;
            $jenisSurat = $pengajuan->jenisSurat->nama_surat ?? 'Surat';
            $nim = $pengajuan->mahasiswa->nim ?? '-';

            if ($request->action == 'accept') {
                \Illuminate\Support\Facades\Log::info('Processing ACCEPT');

                $bulanRomawi = $this->getRomawi(date('n'));
                $tahun = date('Y');
                $nomorUrut = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                $kodeSurat = 'AKd';
                
                $pengajuan->nomor_surat_resmi = "$nomorUrut/$kodeSurat/$bulanRomawi/$tahun";
                
                $digitalSignature = $pengajuan->digitalSignature;
                \Illuminate\Support\Facades\Log::info('Digital Signature Check', [
                    'exists' => $digitalSignature ? true : false,
                    'type' => $digitalSignature->type ?? null,
                    'path' => $digitalSignature->path ?? null,
                ]);

                if ($digitalSignature) {
                    try {
                        $fullPath = storage_path('app/public/' . $digitalSignature->path);
                        \Illuminate\Support\Facades\Log::info('Signature file check', ['path' => $fullPath, 'exists' => file_exists($fullPath)]);
                        
                        $pdfContent = $this->generateFinalPDF($pengajuan, $digitalSignature);
                        \Illuminate\Support\Facades\Log::info('PDF Generated', ['size' => strlen($pdfContent)]);
                        $pengajuan->file_surat_content = $pdfContent;
                        $pengajuan->file_surat_name = 'surat-' . $pengajuan->id_pengajuan . '.pdf';
                        $pengajuan->file_surat_mime_type = 'application/pdf';
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('PDF Generation Error', [
                            'error' => $e->getMessage(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                        ]);
                    }
                } else {
                    \Illuminate\Support\Facades\Log::warning('No digital signature found for pengajuan: ' . $pengajuan->id_pengajuan);
                }
                
                $statusBaru = 'Menunggu Proses Admin';
                $keterangan = 'Pengajuan divalidasi oleh Dekan dan menunggu proses akhir dari admin (cetak/kirim email).';
                
                $notificationService->notifyAdminsForFinalProcess($pengajuan->id_pengajuan, $jenisSurat, $nim);
            } else {
                $statusBaru = 'Ditolak';
                $keterangan = 'Pengajuan ditolak oleh Dekan. Alasan: ' . $request->catatan;
                
                // Notify penolakan ke Mahasiswa
                $alasan = $request->catatan ?: 'Tidak ada alasan diberikan.';
                $notificationService->notifyPenolakan($mahasiswaUserId, $jenisSurat, $alasan);
            }

            // Update Status Pengajuan
            $pengajuan->status_saat_ini = $statusBaru;
            $pengajuan->save();

            // Catat Log
            LogStatusSurat::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status_lama' => 'Menunggu Validasi Dekan',
                'status_baru' => 'Menunggu Proses Admin',
                'tgl_perubahan' => now(),
                'diubah_oleh_user' => Auth::id(),
                'keterangan' => $keterangan,
            ]);

            DB::commit();

            $msg = ($request->action == 'accept') ? 'Pengajuan berhasil divalidasi oleh Dekan.' : 'Pengajuan telah ditolak.';
            return redirect()->route('dekan.validation.index')->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Dekan Validation Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function getRomawi($month)
    {
        $romawi = [1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        return $romawi[$month] ?? $month;
    }

    private function generateFinalPDF($pengajuan, $digitalSignature)
    {
        $pengajuan->load(['mahasiswa.user', 'mahasiswa.prodi.fakulta', 'jenisSurat']);
        
        $studentData = [
            'nama' => $pengajuan->mahasiswa->user->nama_lengkap ?? '-',
            'nim' => $pengajuan->mahasiswa->nim ?? '-',
            'fakultas' => $pengajuan->mahasiswa->prodi->fakulta->nama_fakult ?? 'Fakultas',
            'prodi' => $pengajuan->mahasiswa->prodi->nama_prodi ?? '-',
        ];

        $signatureData = $this->prepareDigitalSignatureData($digitalSignature);

        $data = [
            'pengajuan' => $pengajuan,
            'student' => $studentData,
            'nomor_surat' => $pengajuan->nomor_surat_resmi,
            'tanggal_surat' => Carbon::now()->locale('id')->translatedFormat('d F Y'),
            'digital_signature' => $signatureData,
        ];

        $templatePath = $pengajuan->jenisSurat->template_path ?? 'pdf.withdrawal-certificate';
        $pdf = Pdf::loadView($templatePath, $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->output();
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

    private function authorizeDekan()
    {
        if (Auth::user()->id_hak_akses != 3) {
            abort(403, 'Akses ditolak.');
        }
    }
}
