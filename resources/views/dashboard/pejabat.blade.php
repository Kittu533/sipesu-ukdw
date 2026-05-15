@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end space-y-4 sm:space-y-0">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Dashboard Pejabat</h2>
            <p class="text-gray-500 mt-1 sm:mt-2 text-sm sm:text-base">Selamat datang, <span class="font-semibold text-emerald-700">{{ Auth::user()->nama_lengkap }}</span>. Berikut adalah ringkasan dokumen yang perlu perhatian Anda.</p>
        </div>
        <div class="sm:hidden">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                Pejabat
            </span>
        </div>
        <div class="hidden sm:block">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                {{ Auth::user()->pejabat->jabatan->nama_jabatan ?? 'Pejabat Berwenang' }}
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
        <!-- Menunggu Tanda Tangan -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 hover:shadow-md transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Menunggu Tanda Tangan</p>
                    <p class="text-2xl sm:text-4xl font-bold text-gray-900 mt-1 sm:mt-2">{{ $data['menunggu_persetujuan'] }}</p>
                    <p class="text-xs sm:text-sm text-yellow-600 mt-1 sm:mt-2 flex items-center">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                        Dokumen perlu tindakan
                    </p>
                </div>
                <div class="p-3 sm:p-4 bg-yellow-50 rounded-xl text-yellow-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Telah Disetujui -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 hover:shadow-md transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Telah Disetujui</p>
                    <p class="text-2xl sm:text-4xl font-bold text-gray-900 mt-1 sm:mt-2">{{ $data['disetujui'] }}</p>
                    <p class="text-xs sm:text-sm text-emerald-600 mt-1 sm:mt-2 flex items-center">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                        Total dokumen selesai
                    </p>
                </div>
                <div class="p-3 sm:p-4 bg-emerald-50 rounded-xl text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Queue Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Antrean Dokumen</h3>
                <p class="text-sm text-gray-500 mt-1">Daftar dokumen yang menunggu tanda tangan digital Anda.</p>
            </div>
            <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 flex items-center transition">
                Lihat Semua
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider font-semibold">
                        <th class="px-8 py-4">Mahasiswa</th>
                        <th class="px-6 py-4">Jenis Surat</th>
                        <th class="px-6 py-4">Nomor Surat</th>
                        <th class="px-6 py-4">Tanggal Pengajuan</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data['daftar_persetujuan'] as $pengajuan)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-8 py-5">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm mr-3">
                                    {{ substr($pengajuan->mahasiswa->user->nama_lengkap ?? 'M', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $pengajuan->mahasiswa->user->nama_lengkap ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $pengajuan->mahasiswa->nim ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                {{ $pengajuan->jenisSurat->nama_surat ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-gray-600 text-sm font-mono">{{ $pengajuan->nomor_surat_resmi ?? 'Draft' }}</td>
                        <td class="px-6 py-5 text-gray-600 text-sm">{{ $pengajuan->created_at->format('d M Y') }}</td>
                        <td class="px-8 py-5 text-right text-sm font-medium">
                            <a href="{{ route('submission.approve', $pengajuan->id_pengajuan) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Review & TTD
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-lg font-medium text-gray-900">Semua Beres!</p>
                                <p class="text-sm text-gray-500 mt-1">Tidak ada dokumen yang menunggu tanda tangan Anda saat ini.</p>
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
