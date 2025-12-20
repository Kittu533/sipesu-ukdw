@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Riwayat Pengajuan Selesai</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar pengajuan surat yang telah selesai diproses atau ditolak.</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium">No</th>
                        <th class="px-6 py-4 font-medium">Jenis Surat</th>
                        <th class="px-6 py-4 font-medium">Tanggal Selesai</th>
                        <th class="px-6 py-4 font-medium">Nomor Surat</th>
                        <th class="px-6 py-4 font-medium">Status Akhir</th>
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
                            {{ $item->updated_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm font-mono">
                            {{ $item->nomor_surat_resmi ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClass = match($item->status_saat_ini) {
                                    'Selesai' => 'bg-emerald-100 text-emerald-700',
                                    'Ditolak' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $item->status_saat_ini }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            @if($item->status_saat_ini == 'Selesai')
                            <a href="{{ route('submission.download', $item->id_pengajuan) }}" class="text-emerald-600 hover:text-emerald-800 font-medium mr-3 flex items-center justify-end">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Unduh PDF
                            </a>
                            @else
                            <button class="text-gray-400 hover:text-gray-600" title="Lihat Alasan Penolakan">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                <p class="text-lg font-medium text-gray-900">Belum ada riwayat</p>
                                <p class="text-sm text-gray-500 mt-1">Surat yang telah selesai atau ditolak akan muncul di sini.</p>
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
