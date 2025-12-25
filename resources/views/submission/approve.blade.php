@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-green-800 text-sm">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="text-red-800 text-sm">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <div class="flex items-center mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="text-red-800 text-sm font-medium">Terjadi kesalahan:</span>
        </div>
        <ul class="list-disc list-inside text-red-700 text-sm">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-emerald-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                    Dashboard
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Review Dokumen</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 bg-emerald-700 text-white flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Review & Tanda Tangan</h2>
                <p class="text-emerald-100 mt-1">Tinjau detail pengajuan sebelum memberikan persetujuan.</p>
            </div>
            <div class="bg-white/10 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>

        <div class="p-8">
            <!-- Informasi Mahasiswa -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Informasi Pemohon</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</label>
                        <p class="mt-1 text-base font-medium text-gray-900">{{ $pengajuan->mahasiswa->user->nama_lengkap }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</label>
                        <p class="mt-1 text-base font-medium text-gray-900 font-mono">{{ $pengajuan->mahasiswa->nim }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</label>
                        <p class="mt-1 text-base font-medium text-gray-900">{{ $pengajuan->mahasiswa->prodi->nama_prodi ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</label>
                        <p class="mt-1 text-base font-medium text-gray-900">{{ $pengajuan->created_at->format('d F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Surat -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Detail Surat</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Surat</label>
                        <p class="mt-1 text-lg font-medium text-emerald-700">{{ $pengajuan->jenisSurat->nama_surat }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Keterangan / Keperluan</label>
                        <p class="text-gray-700 whitespace-pre-line">{{ $pengajuan->keterangan_mahasiswa }}</p>
                    </div>
                    @if($pengajuan->file_lampiran_path)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Lampiran</label>
                        <a href="#" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            Lihat Lampiran
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Form -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tindakan Persetujuan</h3>
                <form action="{{ route('submission.process_approval', $pengajuan->id_pengajuan) }}" method="POST">
                    @csrf
                    
                    <!-- Digital Signature Selection -->
                    <div class="mb-6" id="signature-selection">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Tanda Tangan Digital</label>
                        @if($digitalSignatures->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($digitalSignatures as $signature)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="digital_signature_id" value="{{ $signature->id }}" 
                                           class="sr-only peer" {{ $loop->first ? 'checked' : '' }}>
                                    <div class="border-2 border-gray-200 rounded-lg p-4 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-gray-300 transition">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ asset('storage/' . $signature->path) }}" 
                                                 alt="{{ $signature->name }}" class="h-12 w-auto">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $signature->name }}</p>
                                                <p class="text-sm text-gray-500">{{ strtoupper($signature->type) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-800">
                                            Anda belum memiliki tanda tangan digital. 
                                            <a href="{{ route('pejabat.digital-signature.create') }}" class="font-medium underline hover:text-yellow-900">
                                                Buat tanda tangan digital
                                            </a> terlebih dahulu.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional untuk persetujuan, Wajib untuk penolakan)</label>
                        <textarea id="catatan" name="catatan" rows="3" class="shadow-sm focus:ring-emerald-500 focus:border-emerald-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <button type="submit" name="action" value="reject" class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Tolak Pengajuan
                        </button>
                        <button type="submit" name="action" value="approve" 
                                class="inline-flex items-center px-6 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition transform hover:scale-105"
                                {{ $digitalSignatures->count() == 0 ? 'disabled' : '' }}>
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Setujui & Tanda Tangani
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
