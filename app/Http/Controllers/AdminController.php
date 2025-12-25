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
        if (!$request->filled('prodi') && !$request->filled('angkatan') && !$request->filled('search')) {
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
        $lines = explode("\n", $content);
        $data = [];
        
        if (count($lines) > 0) {
            // Get headers - handle both comma and tab separated
            $headerLine = trim($lines[0]);
            if (strpos($headerLine, "\t") !== false) {
                $headers = explode("\t", $headerLine);
            } else {
                $headers = str_getcsv($headerLine);
            }
            
            // Clean headers
            $headers = array_map('trim', $headers);
            
            // Process data rows
            for ($i = 1; $i < count($lines); $i++) {
                $line = trim($lines[$i]);
                if (empty($line)) continue;
                
                // Handle both comma and tab separated
                if (strpos($headerLine, "\t") !== false) {
                    $row = explode("\t", $line);
                } else {
                    $row = str_getcsv($line);
                }
                
                if (count($row) >= count($headers)) {
                    $rowData = [];
                    foreach ($headers as $index => $header) {
                        $rowData[$header] = isset($row[$index]) ? trim($row[$index]) : '';
                    }
                    
                    // Only add if has required data
                    if (!empty($rowData['nama_lengkap']) || !empty($rowData['nim'])) {
                        $data[] = $rowData;
                    }
                }
            }
        }
        
        return $data;
    }

    private function parseFromExcel($file)
    {
        // For Excel files, treat as CSV for now
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
            
            // Headers
            fputcsv($file, ['nama_lengkap', 'nim', 'email', 'id_prodi', 'angkatan', 'ipk_terakhir']);
            
            // Sample data
            fputcsv($file, ['Ahmad Fikri', '71230001', '71230001@students.ukdw.ac.id', '71', '2023', '3.75']);
            fputcsv($file, ['Siti Aminah', '71230002', '71230002@students.ukdw.ac.id', '71', '2023', '3.82']);
            fputcsv($file, ['Budi Santoso', '72240001', '72240001@students.ukdw.ac.id', '72', '2024', '3.20']);
            
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
        // Check if NIM already exists
        if (Mahasiswa::where('nim', $data['nim'])->exists()) {
            throw new \Exception("NIM {$data['nim']} sudah ada dalam database.");
        }

        // Check if email already exists
        if (User::where('email', $data['email'])->exists()) {
            throw new \Exception("Email {$data['email']} sudah ada dalam database.");
        }

        // Find prodi by kode_prodi
        $prodi = ProgramStudi::where('kode_prodi', $data['id_prodi'])->first();
        if (!$prodi) {
            throw new \Exception("Program studi dengan kode {$data['id_prodi']} tidak ditemukan.");
        }

        // Create user first
        $user = User::create([
            'nama_lengkap' => $data['nama_lengkap'],
            'username' => $data['nim'], // Use NIM as username
            'email' => $data['email'],
            'password_hash' => Hash::make($data['nim']), // Use password_hash field
            'id_hak_akses' => 1, // Mahasiswa
        ]);

        // Create mahasiswa
        Mahasiswa::create([
            'id_user' => $user->id_user, // Use id_user from created user
            'nim' => $data['nim'],
            'id_prodi' => $prodi->id_prodi, // Use actual id_prodi from database
            'angkatan' => $data['angkatan'],
            'ipk_terakhir' => $data['ipk_terakhir'] ?? null,
        ]);
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
