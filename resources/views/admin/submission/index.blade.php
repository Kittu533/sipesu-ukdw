@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Kelola Pengajuan Surat</h2>
            <p class="text-gray-500 text-sm mt-1">Pantau dan kelola seluruh pengajuan surat mahasiswa.</p>
        </div>
        <div class="flex space-x-2">
            <form method="GET" action="{{ route('admin.submission.index') }}" class="flex space-x-2">
                <select name="status" class="rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Menunggu Tanda Tangan" {{ request('status') == 'Menunggu Tanda Tangan' ? 'selected' : '' }}>Menunggu Tanda Tangan</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                @if(request('status'))
                <a href="{{ route('admin.submission.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reset
                </a>
                @endif
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider font-semibold">
                        <th class="px-6 py-4">Mahasiswa</th>
                        <th class="px-6 py-4">Jenis Surat</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pengajuan as $item)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs mr-3">
                                    {{ substr($item->mahasiswa->user->nama_lengkap ?? 'M', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $item->mahasiswa->user->nama_lengkap ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $item->mahasiswa->nim ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-700">{{ $item->jenisSurat->nama_surat ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusClass = match($item->status_saat_ini) {
                                    'Menunggu Verifikasi' => 'bg-yellow-100 text-yellow-800',
                                    'Diproses' => 'bg-blue-100 text-blue-800',
                                    'Menunggu Tanda Tangan' => 'bg-purple-100 text-purple-800',
                                    'Selesai' => 'bg-emerald-100 text-emerald-800',
                                    'Ditolak' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $item->status_saat_ini }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('admin.submission.detail', $item->id_pengajuan) }}" 
                               class="text-blue-600 hover:text-blue-800 transition" 
                               title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            @if(request('status'))
                                Tidak ada pengajuan dengan status "{{ request('status') }}".
                            @else
                                Tidak ada data pengajuan.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengajuan->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $pengajuan->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
