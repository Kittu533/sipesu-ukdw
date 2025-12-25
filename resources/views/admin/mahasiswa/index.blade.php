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

    <!-- Header & Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Data Mahasiswa</h2>
                <p class="text-gray-500 text-sm mt-1">Cari dan kelola data mahasiswa berdasarkan Program Studi dan Angkatan.</p>
            </div>
            <a href="{{ route('admin.mahasiswa.import.form') }}" class="mt-4 md:mt-0 bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                </svg>
                Import Mahasiswa
            </a>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('admin.mahasiswa.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Filter Prodi -->
                <div>
                    <label for="prodi" class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                    <div class="relative">
                        <select name="prodi" id="prodi" onchange="this.form.submit()" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors appearance-none bg-white">
                            <option value="">Pilih Program Studi</option>
                            @foreach($prodiList as $prodi)
                                <option value="{{ $prodi->id_prodi }}" {{ request('prodi') == $prodi->id_prodi ? 'selected' : '' }}>
                                    {{ $prodi->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filter Angkatan -->
                <div>
                    <label for="angkatan" class="block text-sm font-medium text-gray-700 mb-2">Angkatan</label>
                    <div class="relative">
                        <select name="angkatan" id="angkatan" onchange="this.form.submit()" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors appearance-none bg-white" {{ !request('prodi') ? 'disabled' : '' }}>
                            <option value="">Pilih Angkatan</option>
                            @foreach($angkatanList as $angkatan)
                                <option value="{{ $angkatan }}" {{ request('angkatan') == $angkatan ? 'selected' : '' }}>
                                    {{ $angkatan }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Nama / NIM</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Masukkan Nama atau NIM..." 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors pr-20 pl-12">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <button type="submit" class="absolute inset-y-0 right-0 px-4 text-sm font-medium text-white bg-emerald-600 rounded-r-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 border-2 border-emerald-600">
                            Cari
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reset Button -->
            @if(request()->hasAny(['prodi', 'angkatan', 'search']))
            <div class="flex justify-end">
                <a href="{{ route('admin.mahasiswa.index') }}" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg text-sm font-medium hover:bg-gray-200 transition flex items-center border-2 border-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reset Filter
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    @if($showData)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider font-semibold">
                        <th class="px-6 py-4">Nama / NIM</th>
                        <th class="px-6 py-4">Program Studi</th>
                        <th class="px-6 py-4">Angkatan</th>
                        <th class="px-6 py-4">IPK</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($mahasiswa as $mhs)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm mr-3">
                                    {{ substr($mhs->user->nama_lengkap ?? 'M', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $mhs->user->nama_lengkap ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $mhs->nim }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $mhs->prodi->nama_prodi ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $mhs->angkatan ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $mhs->ipk_terakhir ?? '-' }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex justify-end">
                                <a href="{{ route('admin.mahasiswa.edit', $mhs->id_mahasiswa) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition" 
                                   title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <p class="font-medium text-gray-900">Data tidak ditemukan.</p>
                            <p class="text-sm">Coba ubah filter pencarian Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($mahasiswa instanceof \Illuminate\Pagination\LengthAwarePaginator && $mahasiswa->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $mahasiswa->links() }}
        </div>
        @endif
    </div>
    @else
    <!-- Empty State / Instruction -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="mx-auto h-24 w-24 bg-emerald-50 rounded-full flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900">Mulai Pencarian Data</h3>
        <p class="text-gray-500 mt-2 max-w-md mx-auto">Silakan pilih <strong>Program Studi</strong> dan <strong>Angkatan</strong> terlebih dahulu untuk menampilkan daftar mahasiswa.</p>
    </div>
    @endif
</div>
@endsection
