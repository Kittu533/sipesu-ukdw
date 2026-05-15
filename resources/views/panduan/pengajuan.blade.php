@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold text-gray-900">Panduan Pengajuan Surat</h1>
        <p class="text-gray-500 mt-2">Ikuti langkah-langkah berikut untuk mengajukan surat akademik dengan benar.</p>
    </div>

    <!-- Alur Proses (Flowchart Style) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
            <span class="bg-emerald-100 text-emerald-700 p-2 rounded-lg mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </span>
            Alur Proses Pengajuan
        </h2>
        
        <div class="relative border-l-2 border-emerald-200 ml-6 space-y-8">
            <!-- Step 1 -->
            <div class="relative pl-8">
                <span class="absolute -left-3 top-0 h-6 w-6 rounded-full bg-emerald-600 border-4 border-white"></span>
                <h3 class="font-bold text-gray-900 text-lg">1. Login & Isi Formulir</h3>
                <p class="text-gray-600 mt-1">Masuk ke sistem menggunakan akun mahasiswa Anda. Pilih menu <strong>"Buat Pengajuan"</strong> dan lengkapi formulir sesuai jenis surat yang dibutuhkan.</p>
            </div>

            <!-- Step 2 -->
            <div class="relative pl-8">
                <span class="absolute -left-3 top-0 h-6 w-6 rounded-full bg-emerald-600 border-4 border-white"></span>
                <h3 class="font-bold text-gray-900 text-lg">2. Upload Dokumen Pendukung</h3>
                <p class="text-gray-600 mt-1">Unggah berkas persyaratan (seperti scan KTM, bukti pembayaran, dll) dalam format PDF atau JPG. Pastikan dokumen terbaca jelas.</p>
            </div>

            <!-- Step 3 -->
            <div class="relative pl-8">
                <span class="absolute -left-3 top-0 h-6 w-6 rounded-full bg-orange-500 border-4 border-white"></span>
                <h3 class="font-bold text-gray-900 text-lg">3. Verifikasi Admin (Menunggu Verifikasi Admin)</h3>
                <p class="text-gray-600 mt-1">Admin akan melakukan verifikasi awal terhadap pengajuan Anda sebelum diteruskan ke Administrasi.</p>
            </div>

            <!-- Step 4 -->
            <div class="relative pl-8">
                <span class="absolute -left-3 top-0 h-6 w-6 rounded-full bg-yellow-500 border-4 border-white"></span>
                <h3 class="font-bold text-gray-900 text-lg">4. Verifikasi Administrasi (Menunggu Verifikasi)</h3>
                <p class="text-gray-600 mt-1">Administrasi akan melakukan verifikasi lanjutan sebelum surat diteruskan ke Kepala Biro untuk ditandatangani.</p>
            </div>

            <!-- Step 5 -->
            <div class="relative pl-8">
                <span class="absolute -left-3 top-0 h-6 w-6 rounded-full bg-blue-500 border-4 border-white"></span>
                <h3 class="font-bold text-gray-900 text-lg">5. Persetujuan Kepala Biro (Menunggu Tanda Tangan)</h3>
                <p class="text-gray-600 mt-1">Surat akan diteruskan ke Pejabat Berwenang (Kepala Biro) untuk ditandatangani secara digital.</p>
            </div>

            <!-- Step 6 -->
            <div class="relative pl-8">
                <span class="absolute -left-3 top-0 h-6 w-6 rounded-full bg-purple-500 border-4 border-white"></span>
                <h3 class="font-bold text-gray-900 text-lg">6. Validasi Dekan (Menunggu Validasi Dekan)</h3>
                <p class="text-gray-600 mt-1">Dekan Fakultas akan memvalidasi surat setelah ditandatangani oleh Kepala Biro. Jika ada kekurangan, pengajuan akan ditolak dengan catatan perbaikan.</p>
            </div>

            <!-- Step 7 -->
            <div class="relative pl-8">
                <span class="absolute -left-3 top-0 h-6 w-6 rounded-full bg-emerald-600 border-4 border-white"></span>
                <h3 class="font-bold text-gray-900 text-lg">7. Selesai & Unduh</h3>
                <p class="text-gray-600 mt-1">Surat resmi diterbitkan. Anda akan menerima notifikasi dan dapat mengunduh surat di menu <strong>"Riwayat Selesai"</strong>.</p>
            </div>
        </div>
    </div>

    <!-- FAQ / Prasyarat -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4">Prasyarat Umum</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-600 text-sm">
                <li>Mahasiswa Aktif pada semester berjalan.</li>
                <li>Tidak memiliki tunggakan administrasi keuangan.</li>
                <li>Data profil mahasiswa sudah lengkap (NIM, Prodi, Angkatan).</li>
            </ul>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4">Bantuan</h3>
            <p class="text-gray-600 text-sm mb-4">Jika mengalami kendala teknis atau pertanyaan lebih lanjut, silakan hubungi bagian pelayanan.</p>
            <a href="#" class="text-emerald-600 font-medium hover:underline">Hubungi Admin &rarr;</a>
        </div>
    </div>
</div>
@endsection
