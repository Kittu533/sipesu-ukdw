@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header & Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Data Mahasiswa</h2>
                <p class="text-gray-500 text-sm mt-1">Cari dan kelola data mahasiswa berdasarkan Program Studi dan Angkatan.</p>
            </div>
            <button class="mt-4 md:mt-0 bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Mahasiswa
            </button>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('admin.mahasiswa.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Filter Prodi -->
            <div>
                <label for="prodi" class="block text-xs font-medium text-gray-700 mb-1">Program Studi</label>
                <select name="prodi" id="prodi" onchange="this.form.submit()" class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach($prodiList as $prodi)
                        <option value="{{ $prodi->id_prodi }}" {{ request('prodi') == $prodi->id_prodi ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Angkatan -->
            <div>
                <label for="angkatan" class="block text-xs font-medium text-gray-700 mb-1">Angkatan</label>
                <select name="angkatan" id="angkatan" onchange="this.form.submit()" class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500" {{ !request('prodi') ? 'disabled' : '' }}>
                    <option value="">-- Pilih Angkatan --</option>
                    @foreach($angkatanList as $angkatan)
                        <option value="{{ $angkatan }}" {{ request('angkatan') == $angkatan ? 'selected' : '' }}>
                            {{ $angkatan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search -->
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-medium text-gray-700 mb-1">Cari Nama / NIM</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Masukkan Nama atau NIM..." class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500 pl-10">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button type="submit" class="absolute inset-y-0 right-0 px-4 text-sm font-medium text-white bg-emerald-600 rounded-r-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Cari
                    </button>
                </div>
            </div>
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
                            <button class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <button class="text-red-600 hover:text-red-900">Hapus</button>
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
