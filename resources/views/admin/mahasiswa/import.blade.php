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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Format File
                </h3>
                <div class="text-sm text-blue-700 space-y-2">
                    <p class="font-medium">Kolom yang diperlukan:</p>
                    <ul class="space-y-1 ml-4">
                        <li>• <code class="bg-blue-100 px-1 rounded">nama_lengkap</code></li>
                        <li>• <code class="bg-blue-100 px-1 rounded">nim</code></li>
                        <li>• <code class="bg-blue-100 px-1 rounded">email</code></li>
                        <li>• <code class="bg-blue-100 px-1 rounded">id_prodi</code></li>
                        <li>• <code class="bg-blue-100 px-1 rounded">angkatan</code></li>
                        <li>• <code class="bg-blue-100 px-1 rounded">ipk_terakhir</code> (opsional)</li>
                    </ul>
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
                    <p>• Pastikan format kolom sesuai template</p>
                    <p>• ID Program Studi harus valid</p>
                    <p>• NIM harus unik (tidak boleh duplikat)</p>
                    <p>• Email harus valid dan unik</p>
                    <p>• Password default = NIM mahasiswa</p>
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
