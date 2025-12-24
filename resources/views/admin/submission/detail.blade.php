@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Detail Pengajuan Surat</h2>
            <p class="text-gray-500 text-sm mt-1">Detail lengkap pengajuan surat mahasiswa.</p>
        </div>
        <div class="flex space-x-2">
            @if($pengajuan->status_saat_ini === 'Selesai' && $pengajuan->file_surat_content)
            <a href="{{ route('admin.submission.print', $pengajuan->id_pengajuan) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center"
               target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Surat
            </a>
            @endif
            <a href="{{ route('admin.submission.index') }}" 
               class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Utama -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Mahasiswa -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Mahasiswa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama Lengkap</label>
                        <p class="text-gray-900 font-medium">{{ $pengajuan->mahasiswa->user->nama_lengkap ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">NIM</label>
                        <p class="text-gray-900 font-mono">{{ $pengajuan->mahasiswa->nim ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Program Studi</label>
                        <p class="text-gray-900">{{ $pengajuan->mahasiswa->prodi->nama_prodi ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Angkatan</label>
                        <p class="text-gray-900">{{ $pengajuan->mahasiswa->angkatan ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Data Pengajuan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Pengajuan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Jenis Surat</label>
                        <p class="text-gray-900 font-medium">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tanggal Pengajuan</label>
                        <p class="text-gray-900">{{ $pengajuan->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nomor Surat</label>
                        <p class="text-gray-900 font-mono">{{ $pengajuan->nomor_surat_resmi ?? 'Belum ada' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status Saat Ini</label>
                        @php
                            $statusClass = match($pengajuan->status_saat_ini) {
                                'Menunggu Verifikasi' => 'bg-yellow-100 text-yellow-800',
                                'Diproses' => 'bg-blue-100 text-blue-800',
                                'Menunggu Tanda Tangan' => 'bg-purple-100 text-purple-800',
                                'Selesai' => 'bg-emerald-100 text-emerald-800',
                                'Ditolak' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                            {{ $pengajuan->status_saat_ini }}
                        </span>
                    </div>
                </div>
                
                @if($pengajuan->keterangan_mahasiswa)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-500">Keterangan Mahasiswa</label>
                    <p class="text-gray-900 bg-gray-50 p-3 rounded-lg mt-1">{{ $pengajuan->keterangan_mahasiswa }}</p>
                </div>
                @endif
            </div>

            <!-- Detail Pengajuan -->
            @if($pengajuan->detailPengajuan->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Pengajuan</h3>
                <div class="space-y-3">
                    @foreach($pengajuan->detailPengajuan as $detail)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                        <span class="text-sm font-medium text-gray-500">{{ $detail->label_field }}</span>
                        <span class="text-gray-900">{{ $detail->nilai_field }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Timeline Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline Status</h3>
                <div class="space-y-4">
                    @forelse($pengajuan->logStatusSurat->sortBy('created_at') as $log)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $log->status_baru }}</p>
                            <p class="text-xs text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                            @if($log->keterangan)
                            <p class="text-xs text-gray-600 mt-1">{{ $log->keterangan }}</p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">Belum ada log status.</p>
                    @endforelse
                </div>
            </div>

            <!-- Validasi Staff -->
            @if($pengajuan->validasiStaff->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Validasi Staff</h3>
                @foreach($pengajuan->validasiStaff as $validasi)
                <div class="mb-3 last:mb-0">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900">{{ $validasi->user->nama_lengkap ?? 'Staff' }}</span>
                        <span class="text-xs px-2 py-1 rounded-full {{ $validasi->status_validasi == 'Disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $validasi->status_validasi }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500">{{ $validasi->created_at->format('d/m/Y H:i') }}</p>
                    @if($validasi->catatan)
                    <p class="text-xs text-gray-600 mt-1">{{ $validasi->catatan }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            <!-- Persetujuan Pejabat -->
            @if($pengajuan->persetujuanPejabat->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Persetujuan Pejabat</h3>
                @foreach($pengajuan->persetujuanPejabat as $persetujuan)
                <div class="mb-3 last:mb-0">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900">{{ $persetujuan->pejabat->user->nama_lengkap ?? 'Pejabat' }}</span>
                        <span class="text-xs px-2 py-1 rounded-full {{ $persetujuan->status_persetujuan == 'Disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $persetujuan->status_persetujuan }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500">{{ $persetujuan->created_at->format('d/m/Y H:i') }}</p>
                    @if($persetujuan->catatan)
                    <p class="text-xs text-gray-600 mt-1">{{ $persetujuan->catatan }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
