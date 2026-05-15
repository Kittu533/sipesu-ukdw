@extends('errors.layout')

@section('title', 'Akses Ditolak')

@section('content')
<div class="mb-6">
    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-100 mb-4">
        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m2-10V4a2 2 0 00-2-2H8a2 2 0 00-2 2v1m8 0V4a2 2 0 012-2h2a2 2 0 012 2v1M5 9h14a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9a2 2 0 012-2z" />
        </svg>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-2">403</h1>
    <h2 class="text-xl font-semibold text-red-600 mb-2">Akses Ditolak</h2>
    <p class="text-gray-600 mb-4">{{ $message ?? 'Maaf, Anda tidak memiliki akses ke halaman ini.' }}</p>
</div>

<div class="bg-red-50 border-l-4 border-red-500 p-4 rounded text-left mb-6">
    <p class="text-sm text-red-700">
        <strong>Possible Solutions:</strong>
    </p>
    <ul class="text-sm text-red-600 mt-2 list-disc list-inside">
        <li>Pastikan Anda telah login dengan akun yang benar</li>
        <li>Periksa apakah Anda memiliki hak akses yang diperlukan</li>
        <li>Hubungi administrator jika Anda merasa ini adalah kesalahan</li>
    </ul>
</div>

<div class="flex flex-col sm:flex-row gap-3 justify-center">
    <a href="javascript:history.back()" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h18" />
        </svg>
        Kembali
    </a>
    <a href="{{ url('/login') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
        </svg>
        Login Ulang
    </a>
</div>
@endsection