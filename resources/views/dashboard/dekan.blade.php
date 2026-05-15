@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end space-y-4 sm:space-y-0">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Dashboard Dekan Fakultas</h2>
            <p class="text-gray-500 mt-1 sm:mt-2 text-sm sm:text-base">Selamat datang, <span class="font-semibold text-emerald-700">{{ Auth::user()->nama_lengkap }}</span>. Berikut adalah pengajuan surat pengunduran diri yang perlu validasi Anda.</p>
        </div>
        <div class="sm:hidden">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                Dekan Fakultas
            </span>
        </div>
        <div class="hidden sm:block">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                Dekan Fakultas
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:gap-6">
        <!-- Menunggu Validasi Dekan -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-4 sm:p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Menunggu Validasi Dekan</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $data['menunggu_validasi'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Surat pengunduran diri yang perlu ditandatangani</p>
                </div>
                <div class="p-2 sm:p-3 bg-yellow-100 rounded-full text-yellow-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Validation Queue Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Antrean Validasi</h3>
            <a href="{{ route('dekan.validation.index') }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">
                Lihat Semua →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Mahasiswa</th>
                        <th class="px-6 py-3 font-medium">Jenis Surat</th>
                        <th class="px-6 py-3 font-medium">Keterangan</th>
                        <th class="px-6 py-3 font-medium">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data['daftar_pengajuan'] as $pengajuan)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $pengajuan->mahasiswa->user->nama_lengkap ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $pengajuan->mahasiswa->nim ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ $pengajuan->jenisSurat->nama_surat ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm truncate max-w-xs">{{ $pengajuan->keterangan_mahasiswa ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600 text-sm">{{ $pengajuan->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p>Tidak ada surat pengunduran diri yang perlu divalidasi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
