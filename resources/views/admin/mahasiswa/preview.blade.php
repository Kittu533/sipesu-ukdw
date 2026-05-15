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
            <h2 class="text-2xl font-bold text-gray-900">Preview Data Import</h2>
            <p class="text-gray-500 text-sm mt-1">Periksa data sebelum disimpan ke database. Total: {{ $totalData }} data (Halaman {{ $currentPage }} dari {{ $totalPages }})</p>
            <p class="text-blue-600 text-xs mt-1">NPM akan di-generate otomatis oleh sistem: [Kode Prodi 2 digit][Tahun Masuk 2 digit][No. Urut 4 digit]</p>
            @if(config('app.debug'))
            <details class="mt-2">
                <summary class="text-xs text-gray-400 cursor-pointer">Debug Info</summary>
                <pre class="text-xs bg-gray-100 p-2 rounded mt-1 overflow-auto max-h-32">{{ json_encode($paginatedData, JSON_PRETTY_PRINT) }}</pre>
            </details>
            @endif
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.mahasiswa.import.form') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition flex items-center border border-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('admin.mahasiswa.import.store') }}" method="POST" id="importForm">
        @csrf
        <input type="hidden" name="current_page" value="{{ $currentPage }}">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider font-semibold">
                            <th class="px-4 py-4 w-8">#</th>
                            <th class="px-4 py-4">Nama Lengkap</th>
                            <th class="px-4 py-4">Email</th>
                            <th class="px-4 py-4">Prodi (Kode)</th>
                            <th class="px-4 py-4">Tahun Masuk</th>
                            <th class="px-4 py-4">IPK</th>
                            <th class="px-4 py-4">Status</th>
                            <th class="px-4 py-4">Tempat Lahir</th>
                            <th class="px-4 py-4">Tanggal Lahir</th>
                            <th class="px-4 py-4">Nama Orang Tua</th>
                            <th class="px-4 py-4 w-16">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($paginatedData as $index => $row)
                        @php $actualIndex = ($currentPage - 1) * $perPage + $loop->iteration - 1; @endphp
                        <tr class="hover:bg-gray-50 transition duration-150" id="row-{{ $actualIndex }}">
                            <td class="px-4 py-4 text-sm text-gray-500">{{ ($currentPage - 1) * $perPage + $loop->iteration }}</td>
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $row['nama_lengkap'] ?? '-' }}</div>
                                <input type="hidden" name="data[{{ $actualIndex }}][nama_lengkap]" value="{{ $row['nama_lengkap'] ?? '' }}">
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900">{{ $row['email'] ?? '-' }}</div>
                                <input type="hidden" name="data[{{ $actualIndex }}][email]" value="{{ $row['email'] ?? '' }}">
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $prodiId = $row['id_prodi'] ?? '';
                                    $prodi = $prodiList->firstWhere('kode_prodi', $prodiId) ?? $prodiList->find($prodiId);
                                @endphp
                                <div class="text-sm text-gray-900">
                                    {{ $prodi->nama_prodi ?? ($prodiId ? "ID: $prodiId" : '-') }}
                                    <span class="text-gray-400 text-xs">({{ str_pad($prodiId, 2, '0', STR_PAD_LEFT) }})</span>
                                </div>
                                <input type="hidden" name="data[{{ $actualIndex }}][id_prodi]" value="{{ $prodiId }}">
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900">{{ $row['tahun_masuk'] ?? ($row['angkatan'] ?? '-') }}</div>
                                <input type="hidden" name="data[{{ $actualIndex }}][tahun_masuk]" value="{{ $row['tahun_masuk'] ?? ($row['angkatan'] ?? '') }}">
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900">{{ $row['ipk_terakhir'] ?? '-' }}</div>
                                <input type="hidden" name="data[{{ $actualIndex }}][ipk_terakhir]" value="{{ $row['ipk_terakhir'] ?? '' }}">
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $statusClass = match(strtolower($row['status_mahasiswa'] ?? 'aktif')) {
                                        'aktif' => 'bg-green-100 text-green-800',
                                        'tidak_aktif' => 'bg-gray-100 text-gray-800',
                                        'lulus' => 'bg-blue-100 text-blue-800',
                                        'undur_diri' => 'bg-red-100 text-red-800',
                                        'cuti' => 'bg-yellow-100 text-yellow-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                    $statusLabel = match(strtolower($row['status_mahasiswa'] ?? 'aktif')) {
                                        'aktif' => 'Aktif',
                                        'tidak_aktif' => 'Tidak Aktif',
                                        'lulus' => 'Lulus',
                                        'undur_diri' => 'Undur Diri',
                                        'cuti' => 'Cuti',
                                        default => $row['status_mahasiswa'] ?? 'Aktif'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                                <input type="hidden" name="data[{{ $actualIndex }}][status_mahasiswa]" value="{{ $row['status_mahasiswa'] ?? 'aktif' }}">
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900">{{ $row['tempat_lahir'] ?? '-' }}</div>
                                <input type="hidden" name="data[{{ $actualIndex }}][tempat_lahir]" value="{{ $row['tempat_lahir'] ?? '' }}">
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900">{{ $row['tanggal_lahir'] ?? '-' }}</div>
                                <input type="hidden" name="data[{{ $actualIndex }}][tanggal_lahir]" value="{{ $row['tanggal_lahir'] ?? '' }}">
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900">{{ $row['nama_orang_tua'] ?? '-' }}</div>
                                <input type="hidden" name="data[{{ $actualIndex }}][nama_orang_tua]" value="{{ $row['nama_orang_tua'] ?? '' }}">
                                <input type="hidden" name="data[{{ $actualIndex }}][nip_orang_tua]" value="{{ $row['nip_orang_tua'] ?? '' }}">
                                <input type="hidden" name="data[{{ $actualIndex }}][pangkat_orang_tua]" value="{{ $row['pangkat_orang_tua'] ?? '' }}">
                                <input type="hidden" name="data[{{ $actualIndex }}][instansi_orang_tua]" value="{{ $row['instansi_orang_tua'] ?? '' }}">
                            </td>
                            <td class="px-4 py-4">
                                <button type="button" onclick="removeRow({{ $actualIndex }})" 
                                        class="text-red-600 hover:text-red-800 transition" 
                                        title="Hapus Baris">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="px-6 py-12 text-center text-gray-500">
                                <p class="font-medium text-gray-900">Tidak ada data untuk ditampilkan.</p>
                                <p class="text-sm">Pastikan file Excel/CSV memiliki data yang valid.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ ($currentPage - 1) * $perPage + 1 }} - {{ min($currentPage * $perPage, $totalData) }} dari {{ $totalData }} data
                    </div>
                    
                    <!-- Pagination Controls -->
                    @if($totalPages > 1)
                    <div class="flex items-center space-x-2">
                        <!-- Previous Button -->
                        @if($currentPage > 1)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}" 
                           class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Sebelumnya
                        </a>
                        @endif
                        
                        <!-- Page Numbers -->
                        @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" 
                           class="px-3 py-2 text-sm font-medium {{ $i == $currentPage ? 'text-emerald-600 bg-emerald-50 border-emerald-300' : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50' }} border rounded-lg">
                            {{ $i }}
                        </a>
                        @endfor
                        
                        <!-- Next Button -->
                        @if($currentPage < $totalPages)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}" 
                           class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Selanjutnya
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </form>

    <!-- Submit Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-600">
                <span id="totalRows">{{ $totalData }}</span> data siap diimport
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.mahasiswa.import.form') }}" 
                   class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" form="importForm"
                        class="bg-emerald-600 text-white px-6 py-3 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan ke Database
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function removeRow(index) {
    const row = document.getElementById('row-' + index);
    if (row) {
        row.remove();
        updateRowCount();
    }
}

function updateRowCount() {
    const rows = document.querySelectorAll('tbody tr:not([style*="display: none"])');
    document.getElementById('totalRows').textContent = rows.length;
}
</script>
@endsection
