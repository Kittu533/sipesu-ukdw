@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard Admin Akademik</h2>
            <p class="text-gray-500 mt-2">Selamat datang, <span class="font-semibold text-emerald-700">{{ Auth::user()->nama_lengkap }}</span>. Berikut adalah ringkasan data akademik.</p>
        </div>
        <div class="hidden md:block">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                Admin Akademik
            </span>
        </div>
    </div>
    
    <div class="flex justify-end">
        <a href="{{ route('admin.export.laporan') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded shadow-lg transition duration-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export Laporan
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Pengajuan -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full text-blue-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pengajuan</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $data['total_pengajuan'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Mahasiswa -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-full text-indigo-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Mahasiswa</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $data['total_mahasiswa'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Prodi -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full text-purple-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Program Studi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $data['total_prodi'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pengajuan per Bulan Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengajuan per Bulan ({{ date('Y') }})</h3>
            <canvas id="pengajuanChart" width="400" height="200"></canvas>
        </div>

        <!-- Status Pengajuan Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Pengajuan</h3>
            <canvas id="statusChart" width="400" height="200"></canvas>
        </div>

        <!-- Mahasiswa per Prodi Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Mahasiswa per Program Studi</h3>
            <canvas id="prodiChart" width="800" height="300"></canvas>
        </div>
    </div>

    <!-- Recent Submissions Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Pengajuan Masuk Terbaru</h3>
            <form method="GET" action="{{ route('dashboard') }}" class="flex space-x-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari mahasiswa atau jenis surat..." class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:outline-none focus:ring-1 focus:ring-emerald-500 w-64">
                <button type="submit" class="text-gray-500 hover:text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Mahasiswa</th>
                        <th class="px-6 py-3 font-medium">Jenis Surat</th>
                        <th class="px-6 py-3 font-medium">Tanggal</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data['pengajuan_terbaru'] as $pengajuan)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 mr-3">
                                    {{ substr($pengajuan->mahasiswa->user->nama_lengkap ?? 'M', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $pengajuan->mahasiswa->user->nama_lengkap ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $pengajuan->mahasiswa->nim ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600 text-sm">{{ $pengajuan->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                {{ $pengajuan->status_saat_ini }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada data pengajuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Data dari controller
const pengajuanPerBulan = @json($data['pengajuan_per_bulan']);
const pengajuanPerStatus = @json($data['pengajuan_per_status']);
const mahasiswaPerProdi = @json($data['mahasiswa_per_prodi']);

// Chart 1: Pengajuan per Bulan
const bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
const pengajuanData = new Array(12).fill(0);
pengajuanPerBulan.forEach(item => {
    pengajuanData[item.bulan - 1] = item.total;
});

new Chart(document.getElementById('pengajuanChart'), {
    type: 'line',
    data: {
        labels: bulanNames,
        datasets: [{
            label: 'Jumlah Pengajuan',
            data: pengajuanData,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Chart 2: Status Pengajuan
const statusLabels = pengajuanPerStatus.map(item => item.status_saat_ini);
const statusData = pengajuanPerStatus.map(item => item.total);
const statusColors = ['#3b82f6', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'];

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusData,
            backgroundColor: statusColors.slice(0, statusLabels.length),
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Chart 3: Mahasiswa per Prodi
const prodiLabels = mahasiswaPerProdi.map(item => item.nama_prodi);
const prodiData = mahasiswaPerProdi.map(item => item.mahasiswa_count);

new Chart(document.getElementById('prodiChart'), {
    type: 'bar',
    data: {
        labels: prodiLabels,
        datasets: [{
            label: 'Jumlah Mahasiswa',
            data: prodiData,
            backgroundColor: '#6366f1',
            borderColor: '#4f46e5',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
@endsection
