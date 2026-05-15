@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Status Pengajuan Surat</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau proses pengajuan surat Anda yang sedang berjalan.</p>
        </div>
        <a href="{{ route('submission.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat Pengajuan Baru
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium">No</th>
                        <th class="px-6 py-4 font-medium">Jenis Surat</th>
                        <th class="px-6 py-4 font-medium">Tanggal Pengajuan</th>
                        <th class="px-6 py-4 font-medium">Keterangan</th>
                        <th class="px-6 py-4 font-medium">Status Saat Ini</th>
                        <th class="px-6 py-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pengajuan as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-500 text-sm">
                            {{ $pengajuan->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 text-gray-800 font-medium text-sm">
                            {{ $item->jenisSurat->nama_surat ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            {{ $item->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm truncate max-w-xs" title="{{ $item->keterangan_mahasiswa }}">
                            {{ Str::limit($item->keterangan_mahasiswa, 50) }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClass = match($item->status_saat_ini) {
                                    'Menunggu Verifikasi Admin' => 'bg-orange-100 text-orange-700',
                                    'Menunggu Verifikasi' => 'bg-yellow-100 text-yellow-700',
                                    'Diproses' => 'bg-blue-100 text-blue-700',
                                    'Menunggu Tanda Tangan' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $item->status_saat_ini }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <button class="text-gray-400 hover:text-emerald-600 transition" title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-lg font-medium text-gray-900">Tidak ada pengajuan aktif</p>
                                <p class="text-sm text-gray-500 mt-1">Semua pengajuan Anda telah selesai atau belum ada pengajuan baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $pengajuan->links() }}
        </div>
    </div>
</div>
@endsection
