@extends('layouts.app')

@section('content')
<div class="space-y-6">
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-green-800 text-sm">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="text-red-800 text-sm">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Detail Arsip Surat</h2>
            <p class="text-gray-500 text-sm mt-1">Detail lengkap surat yang telah selesai diproses.</p>
        </div>
        <div class="flex space-x-2">
            @if($pengajuan->file_surat_content)
            <form action="{{ route('archive.send_email', $pengajuan->id_pengajuan) }}" method="POST" style="display: inline;" onsubmit="return confirm('Kirim surat ke email mahasiswa ({{ $pengajuan->mahasiswa->user->email ?? 'email tidak tersedia' }})?');">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Kirim Email
                </button>
            </form>
            @endif
            <a href="{{ route('archive.print', $pengajuan->id_pengajuan) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center"
               target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak
            </a>
            <a href="{{ route('archive.download', $pengajuan->id_pengajuan) }}" 
               class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download
            </a>
            <a href="{{ route('archive.index') }}" 
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

            <!-- Data Surat -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Surat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Jenis Surat</label>
                        <p class="text-gray-900 font-medium">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nomor Surat</label>
                        <p class="text-gray-900 font-mono">{{ $pengajuan->nomor_surat_resmi ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tanggal Pengajuan</label>
                        <p class="text-gray-900">{{ $pengajuan->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tanggal Selesai</label>
                        <p class="text-gray-900">{{ $pengajuan->updated_at->format('d/m/Y H:i') }}</p>
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
            <!-- Status Surat -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Surat</h3>
                <div class="text-center">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Selesai
                    </span>
                </div>
            </div>

            <!-- File Surat -->
            @if($pengajuan->file_surat_content)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">File Surat</h3>
                <div class="text-center space-y-3">
                    <div class="text-gray-600 text-sm">
                        <p>{{ $pengajuan->file_surat_name ?? 'surat.pdf' }}</p>
                        <p class="text-xs text-gray-500">{{ strtoupper($pengajuan->file_surat_mime_type ?? 'PDF') }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('archive.print', $pengajuan->id_pengajuan) }}" 
                           class="flex-1 bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition text-center"
                           target="_blank">
                            Cetak
                        </a>
                        <a href="{{ route('archive.download', $pengajuan->id_pengajuan) }}" 
                           class="flex-1 bg-emerald-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition text-center">
                            Download
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Timeline Status -->
            @if($pengajuan->logStatusSurat->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline Proses</h3>
                <div class="space-y-4">
                    @foreach($pengajuan->logStatusSurat->sortBy('created_at') as $log)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-emerald-500 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $log->status_baru }}</p>
                            <p class="text-xs text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                            @if($log->keterangan)
                            <p class="text-xs text-gray-600 mt-1">{{ $log->keterangan }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
