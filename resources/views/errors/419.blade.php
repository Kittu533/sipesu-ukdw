@extends('errors.layout')

@section('title', 'Halaman Kadaluarsa')

@section('content')
<div class="mb-6">
    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-orange-100 mb-4">
        <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-2">419</h1>
    <h2 class="text-xl font-semibold text-orange-600 mb-2">Halaman Kadaluarsa</h2>
    <p class="text-gray-600 mb-4">Halaman ini telah kadaluarsa karena sesi Anda sudah tidak valid. Silakan muat ulang halaman dan coba lagi.</p>
</div>

<div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded text-left mb-6">
    <p class="text-sm text-orange-700">
        <strong>Cara Mengatasi:</strong>
    </p>
    <ul class="text-sm text-orange-600 mt-2 list-disc list-inside">
        <li>Segarkan halaman dan coba kirim ulang formulir</li>
        <li>Jika masalah berlanjut, coba logout dan login kembali</li>
        <li>Pastikan Anda tidak membuka formulir terlalu lama sebelum mengirim</li>
    </ul>
</div>

<div class="flex flex-col sm:flex-row gap-3 justify-center">
    <a href="javascript:location.reload()" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2" />
        </svg>
        Muat Ulang
    </a>
    <a href="{{ url('/login') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
        </svg>
        Login Ulang
    </a>
</div>
@endsection