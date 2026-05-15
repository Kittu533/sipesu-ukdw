<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Services\NpmService;

class AdminController extends Controller
{
    // --- MANAJEMEN MAHASISWA ---

    public function mahasiswaIndex(Request $request)
    {
        $this->authorizeAdmin();

        // Ambil data untuk dropdown filter
        $prodiList = ProgramStudi::all();
        $angkatanList = Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');

        // Query dasar
        $query = Mahasiswa::with(['user', 'prodi']);

        // Filter berdasarkan Prodi
        if ($request->filled('prodi')) {
            $query->where('id_prodi', $request->prodi);
        }

        // Filter berdasarkan Angkatan
        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        // Filter berdasarkan Status
        if ($request->filled('status')) {
            $query->where('status_mahasiswa', $request->status);
        }

        // Filter Pencarian (Nama/NIM)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        }

        // Jika tidak ada filter, jangan tampilkan semua data (kosongkan atau limit)
        // Sesuai request: "nanti menampilkan prodi dulu... baru memunculkan bagian mahasiswanya"
        // Jadi jika tidak ada filter, kita bisa return collection kosong atau paginate kosong
        if (!$request->filled('prodi') && !$request->filled('angkatan') && !$request->filled('search') && !$request->filled('status')) {
            $mahasiswa = collect([]); // Kosongkan jika belum ada filter
            $showData = false;
        } else {
            $mahasiswa = $query->latest()->paginate(20)->withQueryString();
            $showData = true;
        }

        return view('admin.mahasiswa.index', compact('mahasiswa', 'prodiList', 'angkatanList', 'showData'));
    }

    public function mahasiswaEdit($id)
    {
        $this->authorizeAdmin();
        
        $mahasiswa = Mahasiswa::with(['user', 'prodi'])->findOrFail($id);
        $prodiList = ProgramStudi::all();
        
        return view('admin.mahasiswa.edit', compact('mahasiswa', 'prodiList'));
    }

    public function mahasiswaUpdate(Request $request, $id)
    {
        $this->authorizeAdmin();
        
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'nim' => 'required|string|max:20|unique:mahasiswa,nim,' . $id . ',id_mahasiswa',
            'email' => 'required|email|max:100|unique:users,email,' . Mahasiswa::findOrFail($id)->id_user . ',id_user',
            'id_prodi' => 'required|exists:program_studi,id_prodi',
            'angkatan' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'ipk_terakhir' => 'nullable|numeric|min:0|max:4',
            'status_mahasiswa' => 'required|in:aktif,tidak_aktif,lulus,undur_diri,cuti',
        ]);

        try {
            DB::beginTransaction();
            
            $mahasiswa = Mahasiswa::findOrFail($id);
            
            // Update user
            $mahasiswa->user->update([
                'nama_lengkap' => $request->nama_lengkap,
                'username' => $request->nim,
                'email' => $request->email,
            ]);
            
            // Update mahasiswa
            $mahasiswa->update([
                'nim' => $request->nim,
                'id_prodi' => $request->id_prodi,
                'angkatan' => $request->angkatan,
                'ipk_terakhir' => $request->ipk_terakhir,
                'status_mahasiswa' => $request->status_mahasiswa,
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal update data: ' . $e->getMessage())->withInput();
        }
    }

    public function exportLaporan()
    {
        $this->authorizeAdmin();
        
        // Ambil data statistik
        $data = [
            'total_pengajuan' => \App\Models\PengajuanSurat::count(),
            'total_mahasiswa' => \App\Models\Mahasiswa::count(),
            'total_prodi' => \App\Models\ProgramStudi::count(),
            'pengajuan_per_bulan' => \App\Models\PengajuanSurat::selectRaw('MONTH(created_at) as bulan, MONTHNAME(created_at) as nama_bulan, COUNT(*) as total')
                ->whereYear('created_at', date('Y'))
                ->groupBy('bulan', 'nama_bulan')
                ->orderBy('bulan')
                ->get(),
            'pengajuan_per_status' => \App\Models\PengajuanSurat::selectRaw('status_saat_ini, COUNT(*) as total')
                ->groupBy('status_saat_ini')
                ->get(),
            'mahasiswa_per_prodi' => \App\Models\ProgramStudi::withCount('mahasiswa')->get(),
            'tanggal_export' => date('d/m/Y H:i:s'),
        ];
        
        $pdf = PDF::loadView('pdf.laporan-statistik', $data);
        
        return $pdf->download('laporan_statistik_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function mahasiswaImportForm()
    {
        $this->authorizeAdmin();
        return view('admin.mahasiswa.import');
    }

    public function mahasiswaImport(Request $request)
    {
        $this->authorizeAdmin();
        
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            
            if ($extension === 'csv') {
                $data = $this->parseFromCsv($file);
            } else {
                $data = $this->parseFromExcel($file);
            }

            // Debug: Log raw file content and parsed data
            \Log::info('File extension: ' . $extension);
            \Log::info('Raw file content (first 500 chars): ' . substr(file_get_contents($file->getRealPath()), 0, 500));
            \Log::info('Parsed data count: ' . count($data));
            \Log::info('First 3 rows:', array_slice($data, 0, 3));

            // Store data in session for preview
            session(['import_data' => $data]);
            
            return redirect()->route('admin.mahasiswa.import.preview');
        } catch (\Exception $e) {
            \Log::error('Import error: ' . $e->getMessage());
            return redirect()->route('admin.mahasiswa.import.form')->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }
    }

    public function mahasiswaImportPreview(Request $request)
    {
        $this->authorizeAdmin();
        
        $data = session('import_data');
        if (!$data) {
            return redirect()->route('admin.mahasiswa.import.form')->with('error', 'Data import tidak ditemukan.');
        }

        // Pagination
        $perPage = 20;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $totalData = count($data);
        $totalPages = ceil($totalData / $perPage);
        
        $paginatedData = array_slice($data, $offset, $perPage, true);

        $prodiList = ProgramStudi::all();
        
        return view('admin.mahasiswa.preview', compact('paginatedData', 'prodiList', 'totalData', 'currentPage', 'totalPages', 'perPage'));
    }

    public function mahasiswaImportStore(Request $request)
    {
        $this->authorizeAdmin();
        
        $data = $request->input('data', []);
        
        try {
            DB::beginTransaction();
            
            $successCount = 0;
            foreach ($data as $index => $row) {
                if (!empty($row['nama_lengkap']) && !empty($row['nim'])) {
                    $this->createMahasiswa($row);
                    $successCount++;
                }
            }
            
            DB::commit();
            
            // Remove processed data from session
            $sessionData = session('import_data', []);
            $currentPage = $request->get('current_page', 1);
            $perPage = 20;
            $offset = ($currentPage - 1) * $perPage;
            
            // Remove processed data from session
            $sessionData = session('import_data', []);
            $currentPage = $request->get('current_page', 1);
            $perPage = 20;
            $offset = ($currentPage - 1) * $perPage;
            
            // Remove the exact number of successfully processed items from the beginning of current page
            for ($i = 0; $i < $successCount; $i++) {
                if (isset($sessionData[$offset + $i])) {
                    unset($sessionData[$offset + $i]);
                }
            }
            
            // Reindex array
            $sessionData = array_values($sessionData);
            session(['import_data' => $sessionData]);
            
            if (empty($sessionData)) {
                session()->forget('import_data');
                return redirect()->route('admin.mahasiswa.index')->with('success', 'Semua data berhasil diimport!');
            }
            
            return redirect()->route('admin.mahasiswa.import.preview')->with('success', "$successCount data berhasil diimport. Sisa: " . count($sessionData) . " data.");
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.mahasiswa.import.preview')->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    private function parseFromCsv($file)
    {
        $content = file_get_contents($file->getRealPath());
        
        // Remove BOM if present
        if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
            $content = substr($content, 3);
        }
        
        // Normalize line endings
        $content = str_replace(["\r\n", "\r"], "\n", $content);
        
        $lines = explode("\n", $content);
        $data = [];
        
        if (count($lines) > 0) {
            // Get headers - handle both comma and tab separated
            $headerLine = trim($lines[0]);
            
            // Detect delimiter
            $delimiter = (strpos($headerLine, "\t") !== false) ? "\t" : ',';
            $headers = str_getcsv($headerLine, $delimiter);
            
            // Clean headers - remove BOM and trim
            $headers = array_map(function($h) {
                $h = trim($h);
                // Remove BOM from first header if present
                if (substr($h, 0, 3) === "\xEF\xBB\xBF") {
                    $h = substr($h, 3);
                }
                return $h;
            }, $headers);
            
            // Normalize column names (handle both angkatan and tahun_masuk, nim and npm)
            $normalizedHeaders = [];
            foreach ($headers as $header) {
                $normalizedHeaders[] = $this->normalizeColumnName($header);
            }
            $headers = $normalizedHeaders;
            
            // Process data rows
            for ($i = 1; $i < count($lines); $i++) {
                $line = trim($lines[$i]);
                if (empty($line)) continue;
                
                $row = str_getcsv($line, $delimiter);
                
                if (count($row) >= count($headers)) {
                    $rowData = [];
                    foreach ($headers as $index => $header) {
                        $rowData[$header] = isset($row[$index]) ? trim($row[$index]) : '';
                    }
                    
                    // Only add if has required data
                    if (!empty($rowData['nama_lengkap']) && !empty($rowData['email'])) {
                        // Use tahun_masuk if angkatan exists, or vice versa
                        if (empty($rowData['tahun_masuk']) && !empty($rowData['angkatan'])) {
                            $rowData['tahun_masuk'] = $rowData['angkatan'];
                        }
                        $data[] = $rowData;
                    }
                }
            }
        }
        
        return $data;
    }
    
    private function normalizeColumnName($header)
    {
        $header = trim($header);
        $map = [
            'npm' => 'nim',
            'tahun_masuk' => 'tahun_masuk',
            'angkatan' => 'tahun_masuk',
            'status' => 'status_mahasiswa',
            'ipk' => 'ipk_terakhir',
            'tempat_lahir' => 'tempat_lahir',
            'tanggal_lahir' => 'tanggal_lahir',
        ];
        
        return $map[$header] ?? $header;
    }

    private function parseFromExcel($file)
    {
        // For Excel files (.xlsx, .xls), we still use CSV parsing approach
        // since most Excel exports can be read as text
        // For better Excel support, consider using PhpSpreadsheet package
        return $this->parseFromCsv($file);
    }

    public function mahasiswaTemplate()
    {
        $this->authorizeAdmin();
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_mahasiswa.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers - NPM will be auto-generated
            // Format NPM: [Kode Prodi 2 digit][Tahun Masuk 2 digit][No. Urut 4 digit]
            fputcsv($file, [
                'nama_lengkap', 
                'email', 
                'id_prodi', 
                'tahun_masuk',
                'ipk_terakhir',
                'status_mahasiswa',
                'tempat_lahir',
                'tanggal_lahir',
                'nama_orang_tua',
                'nip_orang_tua',
                'pangkat_orang_tua',
                'instansi_orang_tua'
            ]);
            
            // Sample data with complete information
            // id_prodi: 71=Informatika, 72=Sistem Informasi, 11=Manajemen, dll
            // tahun_masuk: 2 digit tahun (25 = 2025)
            // status_mahasiswa: aktif, tidak_aktif, lulus, undur_diri, cuti
            // NPM akan di-generate otomatis oleh sistem
            fputcsv($file, [
                'Ahmad Fikri Pratama', 
                'ahmad.fikri@students.ukdw.ac.id', 
                '71', 
                '2025', 
                '3.75',
                'aktif',
                'Jakarta',
                '1995-03-15',
                'Dr. Ahmad Pratama',
                '196503151990031001',
                'Pembina Utama Muda',
                'Kementerian Kesehatan RI'
            ]);
            
            fputcsv($file, [
                'Siti Aminah Putri', 
                'siti.aminah@students.ukdw.ac.id', 
                '72', 
                '2025', 
                '3.82',
                'aktif',
                'Bandung',
                '1995-07-22',
                'Ir. Siti Aminah',
                '196507221988121002',
                'Pembina',
                'PT Telkom Indonesia'
            ]);
            
            fputcsv($file, [
                'Budi Santoso', 
                'budi.santoso@students.ukdw.ac.id', 
                '11', 
                '2024', 
                '3.20',
                'aktif',
                'Surabaya',
                '1995-11-08',
                'Drs. Budi Santoso',
                '196511081987032001',
                'Penata Tk I',
                'Dinas Pendidikan Jawa Timur'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function importFromCsv($file)
    {
        $data = array_map('str_getcsv', file($file->getRealPath()));
        $headers = array_shift($data);
        
        foreach ($data as $row) {
            $rowData = array_combine($headers, $row);
            $this->createMahasiswa($rowData);
        }
    }

    private function importFromExcel($file)
    {
        // Simple Excel reader without external library
        $data = [];
        $handle = fopen($file->getRealPath(), 'r');
        
        if ($handle !== FALSE) {
            $headers = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== FALSE) {
                $rowData = array_combine($headers, $row);
                $this->createMahasiswa($rowData);
            }
            fclose($handle);
        }
    }

    private function createMahasiswa($data)
    {
        // Validate required fields
        if (empty($data['nama_lengkap']) || empty($data['email']) || empty($data['id_prodi'])) {
            throw new \Exception("Data tidak lengkap: nama_lengkap, email, dan id_prodi wajib diisi.");
        }

        // Check if email already exists
        if (User::where('email', $data['email'])->exists()) {
            throw new \Exception("Email {$data['email']} sudah ada dalam database.");
        }

        // Find prodi by id_prodi (direct ID) or kode_prodi (fallback)
        $prodi = null;
        if (is_numeric($data['id_prodi'])) {
            $prodi = ProgramStudi::find($data['id_prodi']);
        }
        
        if (!$prodi) {
            $prodi = ProgramStudi::where('kode_prodi', str_pad($data['id_prodi'], 2, '0', STR_PAD_LEFT))->first();
        }
        
        if (!$prodi) {
            throw new \Exception("Program studi dengan ID/kode {$data['id_prodi']} tidak ditemukan.");
        }

        // Validate angkatan (tahun masuk)
        $currentYear = date('Y');
        $tahunMasuk = !empty($data['tahun_masuk']) ? $data['tahun_masuk'] : (!empty($data['angkatan']) ? $data['angkatan'] : date('Y'));
        
        if (!is_numeric($tahunMasuk) || $tahunMasuk < 2000 || $tahunMasuk > ($currentYear + 1)) {
            throw new \Exception("Tahun masuk {$tahunMasuk} tidak valid. Harus antara 2000 - " . ($currentYear + 1));
        }

        // Generate NPM automatically
        $npmService = new NpmService();
        $npm = $npmService->generateNpm($prodi->id_prodi, (int) $tahunMasuk);

        // Validate status_mahasiswa if provided
        $validStatuses = ['aktif', 'tidak_aktif', 'lulus', 'undur_diri', 'cuti'];
        $status = !empty($data['status_mahasiswa']) ? strtolower(trim($data['status_mahasiswa'])) : 'aktif';
        if (!in_array($status, $validStatuses)) {
            throw new \Exception("Status mahasiswa {$data['status_mahasiswa']} tidak valid. Harus salah satu dari: " . implode(', ', $validStatuses));
        }

        // Validate tanggal_lahir format if provided
        $tanggalLahir = null;
        if (!empty($data['tanggal_lahir'])) {
            try {
                $tanggalLahir = \Carbon\Carbon::createFromFormat('Y-m-d', $data['tanggal_lahir'])->format('Y-m-d');
            } catch (\Exception $e) {
                throw new \Exception("Format tanggal lahir tidak valid. Gunakan format YYYY-MM-DD (contoh: 1995-03-15)");
            }
        }

        // Create user first
        $user = User::create([
            'nama_lengkap' => trim($data['nama_lengkap']),
            'username' => $npm,
            'email' => strtolower(trim($data['email'])),
            'password_hash' => Hash::make($npm), // Password default = NPM
            'id_hak_akses' => 1, // Mahasiswa
            'status_aktif' => true,
        ]);

        // Create mahasiswa with all schema fields
        Mahasiswa::create([
            'id_user' => $user->id_user,
            'nim' => $npm,
            'id_prodi' => $prodi->id_prodi,
            'angkatan' => $tahunMasuk,
            'ipk_terakhir' => !empty($data['ipk_terakhir']) ? floatval($data['ipk_terakhir']) : null,
            'status_mahasiswa' => $status,
            'tempat_lahir' => !empty($data['tempat_lahir']) ? trim($data['tempat_lahir']) : null,
            'tanggal_lahir' => $tanggalLahir,
            'nama_orang_tua' => !empty($data['nama_orang_tua']) ? trim($data['nama_orang_tua']) : null,
            'nip_orang_tua' => !empty($data['nip_orang_tua']) ? trim($data['nip_orang_tua']) : null,
            'pangkat_orang_tua' => !empty($data['pangkat_orang_tua']) ? trim($data['pangkat_orang_tua']) : null,
            'instansi_orang_tua' => !empty($data['instansi_orang_tua']) ? trim($data['instansi_orang_tua']) : null,
        ]);

        return [
            'nim' => $npm,
            'nama' => $data['nama_lengkap'],
            'prodi' => $prodi->nama_prodi
        ];
    }

    // --- MANAJEMEN PRODI ---

    public function prodiIndex()
    {
        $this->authorizeAdmin();
        $prodi = ProgramStudi::withCount('mahasiswa')->get();
        return view('admin.prodi.index', compact('prodi'));
    }

    public function prodiCreate()
    {
        $this->authorizeAdmin();
        return view('admin.prodi.create');
    }

    public function prodiStore(Request $request)
    {
        $this->authorizeAdmin();
        
        $request->validate([
            'kode_prodi' => 'required|string|max:10|unique:program_studi,kode_prodi',
            'nama_prodi' => 'required|string|max:255',
        ]);

        ProgramStudi::create([
            'kode_prodi' => $request->kode_prodi,
            'nama_prodi' => $request->nama_prodi,
        ]);

        return redirect()->route('admin.prodi.index')->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function prodiEdit($id)
    {
        $this->authorizeAdmin();
        $prodi = ProgramStudi::findOrFail($id);
        return view('admin.prodi.edit', compact('prodi'));
    }

    public function prodiUpdate(Request $request, $id)
    {
        $this->authorizeAdmin();
        
        $prodi = ProgramStudi::findOrFail($id);
        
        $request->validate([
            'kode_prodi' => 'required|string|max:10|unique:program_studi,kode_prodi,' . $id . ',id_prodi',
            'nama_prodi' => 'required|string|max:255',
        ]);

        $prodi->update([
            'kode_prodi' => $request->kode_prodi,
            'nama_prodi' => $request->nama_prodi,
        ]);

        return redirect()->route('admin.prodi.index')->with('success', 'Program studi berhasil diperbarui.');
    }

    public function prodiDestroy($id)
    {
        $this->authorizeAdmin();
        
        $prodi = ProgramStudi::findOrFail($id);
        
        // Cek apakah ada mahasiswa yang terdaftar
        if ($prodi->mahasiswa()->count() > 0) {
            return redirect()->route('admin.prodi.index')->with('error', 'Program studi tidak dapat dihapus karena masih ada mahasiswa yang terdaftar.');
        }

        $prodi->delete();

        return redirect()->route('admin.prodi.index')->with('success', 'Program studi berhasil dihapus.');
    }

    // --- HELPER ---

    private function authorizeAdmin()
    {
        if (Auth::user()->id_hak_akses != 2) {
            abort(403, 'Akses ditolak.');
        }
    }
}
