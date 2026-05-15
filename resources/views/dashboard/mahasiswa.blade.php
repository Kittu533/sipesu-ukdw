@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end space-y-4 sm:space-y-0">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Dashboard Mahasiswa</h2>
            <p class="text-gray-500 mt-1 sm:mt-2 text-sm sm:text-base">Selamat datang, <span class="font-semibold text-emerald-700">{{ Auth::user()->nama_lengkap }}</span>. Berikut adalah ringkasan pengajuan surat Anda.</p>
        </div>
        <div class="sm:hidden">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                Mahasiswa
            </span>
        </div>
        <div class="hidden sm:block">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                Mahasiswa
            </span>
        </div>
    </div>
    
    <div class="flex justify-end">
        <a href="{{ route('submission.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-3 sm:py-2 sm:px-4 rounded shadow-lg transition duration-200 flex items-center text-sm sm:text-base">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-1 sm:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="hidden sm:inline">Buat Pengajuan Baru</span>
            <span class="sm:hidden">Buat Pengajuan</span>
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
        <!-- Total Pengajuan -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Total Pengajuan</p>
                    <p class="text-xl sm:text-3xl font-bold text-gray-800">{{ $data['total_pengajuan'] }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-blue-100 rounded-full text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Menunggu -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Menunggu Proses</p>
                    <p class="text-xl sm:text-3xl font-bold text-gray-800">{{ $data['menunggu'] }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-yellow-100 rounded-full text-yellow-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-6 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Selesai</p>
                    <p class="text-xl sm:text-3xl font-bold text-gray-800">{{ $data['selesai'] }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-emerald-100 rounded-full text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Ditolak -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Ditolak</p>
                    <p class="text-xl sm:text-3xl font-bold text-gray-800">{{ $data['ditolak'] }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-red-100 rounded-full text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Submissions Table (Left Column) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden h-fit">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Pengajuan Terbaru</h3>
                <a href="{{ route('submission.status') }}" class="text-sm text-emerald-600 hover:text-emerald-800 font-medium">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                            <th class="px-6 py-3 font-medium">Jenis Surat</th>
                            <th class="px-6 py-3 font-medium">Tanggal</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                            <th class="px-6 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($data['pengajuan_terbaru'] as $pengajuan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-800 font-medium">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $pengajuan->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClass = match($pengajuan->status_saat_ini) {
                                        'Selesai' => 'bg-emerald-100 text-emerald-700',
                                        'Ditolak' => 'bg-red-100 text-red-700',
                                        'Menunggu Verifikasi' => 'bg-yellow-100 text-yellow-700',
                                        'Diproses' => 'bg-blue-100 text-blue-700',
                                        'Menunggu Tanda Tangan' => 'bg-purple-100 text-purple-700',
                                        default => 'bg-gray-100 text-gray-700'
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ $pengajuan->status_saat_ini }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($pengajuan->status_saat_ini === 'Selesai')
                                    <a href="{{ route('submission.download', $pengajuan->id_pengajuan) }}" 
                                       class="text-emerald-600 hover:text-emerald-800 font-medium text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Unduh
                                    </a>
                                @else
                                    <a href="#" class="text-gray-400 hover:text-emerald-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada pengajuan surat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity (Right Column) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit">
            <h3 class="font-bold text-gray-900 mb-4">Aktifitas Terkini</h3>
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @forelse($data['aktifitas_terkini'] as $log)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white 
                                        @if($log->status_baru == 'Selesai') bg-emerald-500 
                                        @elseif($log->status_baru == 'Ditolak') bg-red-500
                                        @else bg-blue-500 @endif">
                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            @if($log->status_baru == 'Selesai')
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            @elseif($log->status_baru == 'Ditolak')
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            @else
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            @endif
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-500">
                                            Status surat <span class="font-medium text-gray-900">{{ $log->pengajuanSurat->jenisSurat->nama_surat ?? 'Surat' }}</span> berubah menjadi 
                                            <span class="font-medium 
                                                @if($log->status_baru == 'Selesai') text-emerald-600 
                                                @elseif($log->status_baru == 'Ditolak') text-red-600
                                                @else text-blue-600 @endif">
                                                {{ $log->status_baru }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        <time datetime="{{ $log->tgl_perubahan }}">{{ $log->tgl_perubahan->diffForHumans() }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="py-4 text-center text-sm text-gray-500">Belum ada aktifitas terbaru.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>


</div>
@endsection
