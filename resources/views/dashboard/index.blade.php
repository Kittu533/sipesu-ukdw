@extends('layouts.app')

@section('content')

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
            <h4 class="font-semibold text-sm text-emerald-700">Role Anda: Admin Akademik</h4>
            <p class="text-xs text-gray-500 mt-1">Mengelola Mahasiswa, program studi, dan pengarsipan.</p>
        </div>
        </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @php
            $stats = [
                ['title' => 'Total Pengajuan Surat', 'value' => '1.250', 'icon' => 'inbox', 'color' => 'emerald'],
                ['title' => 'Surat Menunggu Validasi', 'value' => '45', 'icon' => 'clock', 'color' => 'yellow'],
                ['title' => 'Surat Disetujui', 'value' => '1.105', 'icon' => 'thumb-up', 'color' => 'blue'],
                ['title' => 'Surat Ditolak', 'value' => '100', 'icon' => 'x-circle', 'color' => 'red'],
            ];
        @endphp

        @foreach ($stats as $stat)
            @include('components.stat-card', ['stat' => $stat])
        @endforeach
    </div>

    <div class="grid grid-cols-3 gap-6">
        
        <div class="col-span-3 lg:col-span-2 bg-white p-6 rounded-xl card-shadow border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Pengajuan Terbaru</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $submissions = [
                                ['nama' => 'Budi Santoso', 'jenis' => 'Keterangan Aktif Kuliah', 'tanggal' => '25/11/2025', 'status' => 'Disetujui', 'color' => 'green'],
                                ['nama' => 'Siti Aisyah', 'jenis' => 'Izin Penelitian', 'tanggal' => '26/11/2025', 'status' => 'Menunggu', 'color' => 'yellow'],
                                ['nama' => 'Joko Permana', 'jenis' => 'Cuti Akademik', 'tanggal' => '27/11/2025', 'status' => 'Diproses', 'color' => 'blue'],
                                ['nama' => 'Maya Sari', 'jenis' => 'Permohonan Magang', 'tanggal' => '27/11/2025', 'status' => 'Ditolak', 'color' => 'red'],
                            ];
                        @endphp

                        @foreach ($submissions as $sub)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $sub['nama'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sub['jenis'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sub['tanggal'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                @php
                                    $statusClasses = [
                                        'Menunggu' => 'bg-yellow-100 text-yellow-800',
                                        'Diproses' => 'bg-blue-100 text-blue-800',
                                        'Disetujui' => 'bg-emerald-100 text-emerald-800',
                                        'Ditolak' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$sub['status']] }}">
                                    {{ $sub['status'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 text-right">
                <a href="#" class="text-sm text-emerald-600 hover:text-emerald-800 font-medium">Lihat Semua Pengajuan &rarr;</a>
            </div>
        </div>

        <div class="col-span-3 lg:col-span-1 bg-white p-6 rounded-xl card-shadow border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Aktivitas Terbaru</h3>
            
            @include('components.activity-timeline')

        </div>
    </div>

@endsection