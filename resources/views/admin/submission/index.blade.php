@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Kelola Pengajuan Surat</h2>
            <p class="text-gray-500 text-sm mt-1">Pantau dan kelola seluruh pengajuan surat mahasiswa.</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('admin.submission.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Filter Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Pengajuan</label>
                <div class="relative">
                    <select name="status" id="status" onchange="this.form.submit()" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors appearance-none bg-white">
                        <option value="">Semua Status</option>
                        <option value="Menunggu Verifikasi Admin" {{ request('status') == 'Menunggu Verifikasi Admin' ? 'selected' : '' }}>Menunggu Verifikasi Admin</option>
                        <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Verifikasi Administrasi</option>
                        <option value="Menunggu Tanda Tangan" {{ request('status') == 'Menunggu Tanda Tangan' ? 'selected' : '' }}>Menunggu Tanda Tangan</option>
                        <option value="Menunggu Validasi Dekan" {{ request('status') == 'Menunggu Validasi Dekan' ? 'selected' : '' }}>Menunggu Validasi Dekan</option>
                        <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
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
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Mahasiswa / Jenis Surat</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Masukkan nama mahasiswa atau jenis surat..." 
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

            <!-- Reset Button -->
            @if(request('status') || request('search'))
            <div class="md:col-span-3 flex justify-end">
                <a href="{{ route('admin.submission.index') }}" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg text-sm font-medium hover:bg-gray-200 transition flex items-center border-2 border-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset Filter
                </a>
            </div>
            @endif
        </form>
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
                                    'Menunggu Verifikasi Admin' => 'bg-orange-100 text-orange-800',
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
