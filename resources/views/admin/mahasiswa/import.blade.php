@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-green-800 text-sm">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="text-red-800 text-sm">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Import Data Mahasiswa</h2>
            <p class="text-gray-500 text-sm mt-1">Upload file Excel atau CSV untuk menambahkan data mahasiswa secara bulk.</p>
        </div>
        <a href="{{ route('admin.mahasiswa.index') }}" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-200 transition flex items-center border border-blue-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Upload -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form action="{{ route('admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Upload File Excel/CSV</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-emerald-400 transition-colors">
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="hidden" id="fileInput" onchange="updateFileName(this)">
                            <label for="fileInput" class="cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="text-lg text-gray-600 font-medium">Klik untuk pilih file atau drag & drop</p>
                                <p class="text-sm text-gray-500 mt-2">Mendukung format Excel (.xlsx, .xls) dan CSV</p>
                                <p class="text-xs text-gray-400 mt-1">Maksimal ukuran file: 10MB</p>
                            </label>
                        </div>
                        <div id="fileName" class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg hidden">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span id="fileNameText" class="text-green-800 text-sm font-medium"></span>
                            </div>
                        </div>
                        @error('file')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.mahasiswa.index') }}" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                            Batal
                        </a>
                        <button type="submit" class="bg-emerald-600 text-white px-6 py-3 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                            Import Data Mahasiswa
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Format File -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Kolom Template CSV
                </h3>
                <div class="text-sm text-blue-700 space-y-3">
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="bg-blue-100">
                                    <th class="px-2 py-1 text-left">Kolom</th>
                                    <th class="px-2 py-1 text-left">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-blue-100">
                                <tr>
                                    <td class="px-2 py-1"><code class="bg-blue-100 px-1 rounded">nama_lengkap</code></td>
                                    <td class="px-2 py-1">Nama lengkap mahasiswa (wajib)</td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1"><code class="bg-blue-100 px-1 rounded">email</code></td>
                                    <td class="px-2 py-1">Email student (@students.ukdw.ac.id) (wajib)</td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1"><code class="bg-blue-100 px-1 rounded">id_prodi</code></td>
                                    <td class="px-2 py-1">Kode Prodi 2 digit (wajib)</td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1"><code class="bg-blue-100 px-1 rounded">tahun_masuk</code></td>
                                    <td class="px-2 py-1">Tahun masuk 4 digit, misal 2025 (wajib)</td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1"><code class="bg-blue-100 px-1 rounded">ipk_terakhir</code></td>
                                    <td class="px-2 py-1">IPK terakhir (0.00 - 4.00, opsional)</td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1"><code class="bg-blue-100 px-1 rounded">status_mahasiswa</code></td>
                                    <td class="px-2 py-1">aktif, tidak_aktif, lulus, undur_diri, cuti (default: aktif)</td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1"><code class="bg-blue-100 px-1 rounded">tempat_lahir</code></td>
                                    <td class="px-2 py-1">Tempat lahir (opsional)</td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1"><code class="bg-blue-100 px-1 rounded">tanggal_lahir</code></td>
                                    <td class="px-2 py-1">Format YYYY-MM-DD, contoh: 1995-03-15 (opsional)</td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1"><code class="bg-blue-100 px-1 rounded">nama_orang_tua</code></td>
                                    <td class="px-2 py-1">Nama orang tua/wali (opsional)</td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1"><code class="bg-blue-100 px-1 rounded">nip_orang_tua</code></td>
                                    <td class="px-2 py-1">NIP orang tua (opsional)</td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1"><code class="bg-blue-100 px-1 rounded">pangkat_orang_tua</code></td>
                                    <td class="px-2 py-1">Pangkat/golongan orang tua (opsional)</td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1"><code class="bg-blue-100 px-1 rounded">instansi_orang_tua</code></td>
                                    <td class="px-2 py-1">Instansi kerja orang tua (opsional)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-xs mt-2 p-2 bg-blue-100 rounded"><strong>Catatan:</strong> NIM/NPM akan di-generate otomatis sistem: [Kode Prodi 2 digit][Tahun Masuk 2 digit][No. Urut 4 digit]. Contoh: 71250001 = Informatika(71) + 2025(25) + 0001</p>
                </div>
            </div>

            <!-- Kode Program Studi -->
            <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-purple-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Kode Program Studi
                </h3>
                <div class="text-sm text-purple-700 space-y-1">
                    <p><code class="bg-purple-100 px-1 rounded">61</code> = Arsitektur</p>
                    <p><code class="bg-purple-100 px-1 rounded">62</code> = Desain Produk</p>
                    <p><code class="bg-purple-100 px-1 rounded">71</code> = Informatika</p>
                    <p><code class="bg-purple-100 px-1 rounded">72</code> = Sistem Informasi</p>
                </div>
            </div>

            <!-- Download Template -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Template File</h3>
                <p class="text-sm text-gray-600 mb-4">Download template untuk memastikan format file yang benar.</p>
                <a href="{{ route('admin.mahasiswa.template') }}" class="w-full bg-emerald-600 text-white px-4 py-3 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download Template CSV
                </a>
            </div>

            <!-- Tips -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-yellow-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    Tips Import
                </h3>
                <div class="text-sm text-yellow-700 space-y-2">
                    <p>• <strong>NIM di-generate otomatis</strong> - jangan填写 kolom NIM di file</p>
                    <p>• id_prodi gunakan kode 2 digit (71=Informatika, 72=Sistem Informasi)</p>
                    <p>• Email harus unik dan berakhiran @students.ukdw.ac.id</p>
                    <p>• Password default user = NIM yang di-generate</p>
                    <p>• Format tanggal lahir: <strong>YYYY-MM-DD</strong> (contoh: 1995-03-15)</p>
                    <p>• IPK harus bernilai 0.00 - 4.00</p>
                    <p>• Status default = <strong>aktif</strong> jika tidak diisi</p>
                    <p>• Sistem otomatis membuat akun user mahasiswa</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = document.getElementById('fileName');
    const fileNameText = document.getElementById('fileNameText');
    
    if (input.files.length > 0) {
        const file = input.files[0];
        fileNameText.textContent = `File dipilih: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        fileName.classList.remove('hidden');
    } else {
        fileName.classList.add('hidden');
    }
}
</script>
@endsection
