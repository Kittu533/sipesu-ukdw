<?php

// File: app/Http/Controllers/SubmissionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Models\LogStatusSurat;

class SubmissionController extends Controller
{
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
        $jenisSurat = JenisSurat::all();
        return view('submission.create', compact('jenisSurat'));
    }

    /**
     * Menyimpan pengajuan surat baru ke database.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Validasi
        $request->validate([
            'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis_surat',
            'keterangan_mahasiswa' => 'required|string|max:500',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Opsional tergantung kebutuhan
        ]);

        if (!$user->mahasiswa) {
            return back()->withErrors(['msg' => 'Data mahasiswa tidak ditemukan.']);
        }

        DB::beginTransaction();
        try {
            // 1. Simpan Pengajuan
            $pengajuan = new PengajuanSurat();
            $pengajuan->id_mahasiswa = $user->mahasiswa->id_mahasiswa;
            $pengajuan->id_jenis_surat = $request->id_jenis_surat;
            $pengajuan->tgl_pengajuan = now();
            $pengajuan->status_saat_ini = 'Menunggu Verifikasi';
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
                'status_baru' => 'Menunggu Verifikasi',
                'tgl_perubahan' => now(),
                'diubah_oleh_user' => $user->id_user,
                'keterangan' => 'Pengajuan baru dibuat oleh mahasiswa.',
            ]);

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
                                                        ->get();

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

        $pengajuan = PengajuanSurat::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($request->action == 'approve') {
                // Ambil tanda tangan digital yang dipilih
                $digitalSignature = \App\Models\DigitalSignature::where('id', $request->digital_signature_id)
                                                               ->where('user_id', $user->id_user)
                                                               ->firstOrFail();
                
                $statusBaru = 'Selesai';
                $keterangan = 'Surat telah ditandatangani secara digital oleh ' . $user->nama_lengkap . ' menggunakan ' . $digitalSignature->name;
                
                // Generate Nomor Surat
                $bulanRomawi = $this->getRomawi(date('n'));
                $tahun = date('Y');
                $nomorUrut = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                $kodeSurat = 'AKd';
                
                $pengajuan->nomor_surat_resmi = "$nomorUrut/$kodeSurat/$bulanRomawi/$tahun";
                
                // Generate and store PDF content in BLOB
                $pdfContent = $this->generatePDFContent($pengajuan, $digitalSignature);
                $pengajuan->file_surat_content = $pdfContent;
                $pengajuan->file_surat_name = 'surat-' . $pengajuan->id_pengajuan . '.pdf';
                $pengajuan->file_surat_mime_type = 'application/pdf';
                $pengajuan->digital_signature_id = $digitalSignature->id;
            } else {
                $statusBaru = 'Ditolak';
                $keterangan = 'Pengajuan ditolak oleh pejabat. Alasan: ' . $request->catatan;
            }

            $pengajuan->status_saat_ini = $statusBaru;
            $pengajuan->save();

            // Catat Log
            LogStatusSurat::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status_lama' => $pengajuan->getOriginal('status_saat_ini'),
                'status_baru' => $statusBaru,
                'tgl_perubahan' => now(),
                'diubah_oleh_user' => $user->id_user,
                'keterangan' => $keterangan,
            ]);

            DB::commit();

            if ($request->action == 'approve') {
                return redirect()->route('pejabat.approval')->with('success', 'Surat berhasil disetujui dan ditandatangani secara digital.');
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
        switch($pengajuan->id_jenis_surat) {
            case 1: // Surat Keterangan Aktif Kuliah
                return $this->generateActiveStudentPDFContent($pengajuan, $digitalSignature);
            case 2: // Surat Keterangan Alumni
                return $this->generateAlumniPDFContent($pengajuan, $digitalSignature);
            case 3: // Surat Keterangan Pengunduran Diri
                return $this->generateWithdrawalPDFContent($pengajuan, $digitalSignature);
            case 4: // Statement Letter
                return $this->generateStatementLetterPDFContent($pengajuan, $digitalSignature);
            default:
                throw new \Exception('Jenis surat tidak ditemukan');
        }
    }

    private function prepareDigitalSignatureData($digitalSignature)
    {
        return [
            'type' => $digitalSignature->type,
            'path' => $digitalSignature->path,
            'qr_text' => $digitalSignature->qr_text,
            'base64' => base64_encode(file_get_contents(storage_path('app/public/' . $digitalSignature->path))),
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
                    ->whereNotIn('status_saat_ini', ['Selesai']) // Exclude selesai
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
        
        // Hanya staff yang bisa validasi
        if ($user->id_hak_akses != 3) {
            abort(403, 'Akses ditolak.');
        }

        $pengajuan = PengajuanSurat::findOrFail($id);
        
        // Hanya bisa validasi jika status Menunggu Verifikasi
        if ($pengajuan->status_saat_ini != 'Menunggu Verifikasi') {
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
            
            if ($action === 'approve') {
                // Update status pengajuan
                $pengajuan->update(['status_saat_ini' => 'Diproses']);
                
                // Buat log status
                \App\Models\LogStatusSurat::create([
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'status_lama' => 'Menunggu Verifikasi',
                    'status_baru' => 'Diproses',
                    'tgl_perubahan' => now(),
                    'keterangan' => $catatan ?: 'Disetujui oleh staff',
                ]);
                
                $message = 'Pengajuan berhasil disetujui.';
            } else {
                // Update status pengajuan
                $pengajuan->update(['status_saat_ini' => 'Ditolak']);
                
                // Buat log status
                \App\Models\LogStatusSurat::create([
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'status_lama' => 'Menunggu Verifikasi',
                    'status_baru' => 'Ditolak',
                    'tgl_perubahan' => now(),
                    'keterangan' => $catatan ?: 'Ditolak oleh staff',
                ]);
                
                $message = 'Pengajuan berhasil ditolak.';
            }
            
            DB::commit();
            
            return redirect()->route('admin.submission.index')->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memproses validasi: ' . $e->getMessage());
        }
    }
}
