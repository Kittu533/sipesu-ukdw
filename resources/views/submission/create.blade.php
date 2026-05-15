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
        @if($statusMahasiswa === 'lulus')
        <div class="mt-3 p-3 bg-blue-50 border-l-4 border-blue-500 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700"><strong>Catatan:</strong> Status mahasiswa Anda adalah <strong>Lulus</strong>. Anda hanya dapat mengajukan Surat Keterangan Alumni atau Surat Keterangan Lulus.</p>
                </div>
            </div>
        </div>
        @elseif($statusMahasiswa === 'tidak_aktif')
        <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-500 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700"><strong>Catatan:</strong> Status mahasiswa Anda adalah <strong>Tidak Aktif</strong>. Anda hanya dapat mengajukan Surat Keterangan Pengunduran Diri.</p>
                </div>
            </div>
        </div>
        @elseif($statusMahasiswa === 'undur_diri')
        <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-500 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700"><strong>Catatan:</strong> Status mahasiswa Anda adalah <strong>Undur Diri</strong>. Anda hanya dapat mengajukan Surat Keterangan Pengunduran Diri.</p>
                </div>
            </div>
        </div>
        @elseif($statusMahasiswa === 'cuti')
        <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-500 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700"><strong>Catatan:</strong> Status mahasiswa Anda adalah <strong>Cuti</strong>. Anda tidak dapat mengajukan surat apa pun saat berstatus cuti.</p>
                </div>
            </div>
        </div>
        @else
        <div class="mt-3 p-3 bg-green-50 border-l-4 border-green-500 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700"><strong>Catatan:</strong> Status mahasiswa Anda adalah <strong>Aktif</strong>. Anda dapat mengajukan Surat Keterangan Aktif Kuliah, Surat Keterangan Pengunduran Diri, atau Surat Cuti Akademik.</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    @if($statusMahasiswa === 'cuti')
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
        <div class="flex items-start">
            <div class="flex-shrink-0 rounded-lg bg-red-100 p-3 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 115.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-bold text-gray-900">Pengajuan Surat Tidak Tersedia</h2>
                <p class="mt-1 text-sm text-gray-600">Mahasiswa dengan status cuti tidak dapat mengajukan surat melalui sistem. Silakan hubungi Admin Akademik jika status Anda perlu diperbarui.</p>
                <a href="{{ route('dashboard') }}" class="mt-5 inline-flex items-center px-4 py-2 rounded-md bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
    @else
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
                                        <option value="{{ $jenis->id_jenis_surat }}" data-perlu-dekan="{{ $jenis->perlu_validasi_dekan ? 'true' : 'false' }}" {{ old('id_jenis_surat') == $jenis->id_jenis_surat ? 'selected' : '' }}>
                                            {{ $jenis->nama_surat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Pilih jenis surat yang sesuai dengan kebutuhan Anda.</p>
                            
                            <!-- Flow Info -->
                            <div id="flow-info" class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-blue-800">Alur Pengajuan:</span>
                                </div>
                                <p id="flow-text" class="text-sm text-blue-700 mt-1"></p>
                            </div>
                        </div>

                        <!-- Keperluan / Keterangan -->
                        <div>
                            <label for="keterangan_mahasiswa" class="block text-sm font-medium text-gray-700 mb-2">Keperluan / Keterangan Tambahan</label>
                            <textarea id="keterangan_mahasiswa" name="keterangan_mahasiswa" rows="4" required
                                class="shadow-sm focus:ring-emerald-500 focus:border-emerald-500 block w-full sm:text-sm border-gray-300 rounded-md bg-gray-50 hover:bg-white transition duration-150 ease-in-out p-3"
                                placeholder="Contoh: Untuk keperluan magang di PT. XYZ mulai tanggal 1 Januari 2024.">{{ old('keterangan_mahasiswa') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Jelaskan secara singkat dan jelas tujuan pengajuan surat ini.</p>
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
                                <p class="text-xs text-emerald-700 mt-1">Jelaskan tujuan pembuatan surat dengan detail agar Dekan dapat memverifikasi dengan cepat.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
                    <h4 class="font-semibold text-gray-800 mb-2">Butuh Bantuan?</h4>
                    <p class="text-sm text-gray-600 mb-4">Jika Anda mengalami kendala dalam pengajuan surat, silakan hubungi Dekan fakultas.</p>
                    <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 flex items-center">
                        Hubungi Dekan
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    var select = document.getElementById('id_jenis_surat');

    if (select) {
        select.addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var flowInfo = document.getElementById('flow-info');
            var flowText = document.getElementById('flow-text');

            if (this.value) {
                flowInfo.classList.remove('hidden');

                if (selectedOption.dataset.perluDekan === 'true') {
                    flowText.textContent = 'Alur Panjang: Mahasiswa → Admin → Administrasi → Kepala Biro → Dekan. Surat memerlukan validasi Dekan Fakultas setelah ditandatangani.';
                } else {
                    flowText.textContent = 'Alur Pendek: Mahasiswa → Admin → Administrasi → Kepala Biro. Surat tidak memerlukan validasi Dekan dan langsung ditandatangani.';
                }
            } else {
                flowInfo.classList.add('hidden');
            }
        });

        // Trigger change event if option is already selected
        if (select.value) {
            select.dispatchEvent(new Event('change'));
        }
    }
</script>
@endpush
