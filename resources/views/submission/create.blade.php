@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb & Header -->
    <div class="mb-8">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-emerald-600 transition">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        Dashboard
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-sm font-medium text-gray-800 md:ml-2">Buat Pengajuan Baru</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Formulir Pengajuan Surat</h1>
        <p class="mt-2 text-gray-600">Lengkapi data di bawah ini untuk memulai proses pengajuan surat akademik Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Left Column: Form -->
        <div class="lg:col-span-2">
            <!-- Card Container -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada inputan Anda:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('submission.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Jenis Surat -->
                        <div>
                            <label for="id_jenis_surat" class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                            <div class="relative">
                                <select id="id_jenis_surat" name="id_jenis_surat" required
                                    class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md shadow-sm transition duration-150 ease-in-out bg-gray-50 hover:bg-white border">
                                    <option value="" disabled selected>-- Pilih Jenis Surat --</option>
                                    @foreach($jenisSurat as $jenis)
                                        <option value="{{ $jenis->id_jenis_surat }}" {{ old('id_jenis_surat') == $jenis->id_jenis_surat ? 'selected' : '' }}>
                                            {{ $jenis->nama_surat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Pilih jenis surat yang sesuai dengan kebutuhan Anda.</p>
                        </div>

                        <!-- Keperluan / Keterangan -->
                        <div>
                            <label for="keterangan_mahasiswa" class="block text-sm font-medium text-gray-700 mb-2">Keperluan / Keterangan Tambahan</label>
                            <textarea id="keterangan_mahasiswa" name="keterangan_mahasiswa" rows="4" required
                                class="shadow-sm focus:ring-emerald-500 focus:border-emerald-500 block w-full sm:text-sm border-gray-300 rounded-md bg-gray-50 hover:bg-white transition duration-150 ease-in-out p-3"
                                placeholder="Contoh: Untuk keperluan magang di PT. XYZ mulai tanggal 1 Januari 2024.">{{ old('keterangan_mahasiswa') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Jelaskan secara singkat dan jelas tujuan pengajuan surat ini.</p>
                        </div>

                        <!-- Upload Lampiran (Opsional) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lampiran Pendukung (Opsional)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-emerald-500 transition duration-150 ease-in-out group bg-gray-50 hover:bg-emerald-50">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-emerald-500 transition" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="lampiran" class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-emerald-500 px-2">
                                            <span>Upload file</span>
                                            <input id="lampiran" name="lampiran" type="file" class="sr-only">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PDF, JPG, PNG hingga 2MB
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800 font-medium text-sm px-4 py-2 rounded-md transition">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-200 transform hover:scale-105">
                                Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Guide -->
        <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-6">
                <!-- Guide Card -->
                <div class="bg-emerald-50 rounded-xl p-6 border border-emerald-100 shadow-sm">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-emerald-900">Panduan Pengisian</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex">
                            <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-emerald-200 text-emerald-800 font-bold text-xs mt-0.5">1</div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-emerald-900">Pilih Jenis Surat</p>
                                <p class="text-xs text-emerald-700 mt-1">Pastikan Anda memilih jenis surat yang tepat sesuai kebutuhan akademik Anda.</p>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-emerald-200 text-emerald-800 font-bold text-xs mt-0.5">2</div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-emerald-900">Isi Keterangan</p>
                                <p class="text-xs text-emerald-700 mt-1">Jelaskan tujuan pembuatan surat dengan detail agar staff dapat memverifikasi dengan cepat.</p>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-emerald-200 text-emerald-800 font-bold text-xs mt-0.5">3</div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-emerald-900">Upload Lampiran</p>
                                <p class="text-xs text-emerald-700 mt-1">Jika diperlukan, lampirkan dokumen pendukung (KTM, Bukti Pembayaran, dll).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
                    <h4 class="font-semibold text-gray-800 mb-2">Butuh Bantuan?</h4>
                    <p class="text-sm text-gray-600 mb-4">Jika Anda mengalami kendala dalam pengajuan surat, silakan hubungi staff tata usaha.</p>
                    <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 flex items-center">
                        Hubungi Staff
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection