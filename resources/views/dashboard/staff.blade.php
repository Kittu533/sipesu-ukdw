@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard Staff Pelayanan</h2>
            <p class="text-gray-500 mt-2">Selamat datang, <span class="font-semibold text-emerald-700">{{ Auth::user()->nama_lengkap }}</span>. Berikut adalah pengajuan yang perlu validasi Anda.</p>
        </div>
        <div class="hidden md:block">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                Staf Pelayanan Informatika
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Menunggu Validasi -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Menunggu Validasi</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $data['menunggu_validasi'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Perlu tindakan segera</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full text-yellow-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sedang Diproses -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Sedang Diproses Pejabat</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $data['diproses'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Menunggu tanda tangan</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Validation Queue Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Antrean Validasi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Mahasiswa</th>
                        <th class="px-6 py-3 font-medium">Jenis Surat</th>
                        <th class="px-6 py-3 font-medium">Keperluan</th>
                        <th class="px-6 py-3 font-medium">Tanggal</th>
                        <th class="px-6 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data['daftar_pengajuan'] as $pengajuan)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $pengajuan->mahasiswa->user->nama_lengkap ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $pengajuan->mahasiswa->nim ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600 text-sm truncate max-w-xs">{{ $pengajuan->keterangan_mahasiswa ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600 text-sm">{{ $pengajuan->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <button class="bg-emerald-100 text-emerald-700 hover:bg-emerald-200 px-3 py-1 rounded-md transition mr-2">
                                Validasi
                            </button>
                            <button class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded-md transition">
                                Tolak
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada pengajuan yang perlu divalidasi saat ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
