@extends('errors.layout')

@section('title', 'Kesalahan Server')

@section('content')
<div class="mb-6">
    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
        <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-2">500</h1>
    <h2 class="text-xl font-semibold text-gray-700 mb-2">Terjadi Kesalahan Server</h2>
    <p class="text-gray-600 mb-4">Maaf, terjadi kesalahan pada server kami. Tim kami telah diberitahu dan akan segera memperbaikinya.</p>
</div>

<div class="bg-gray-50 border-l-4 border-gray-500 p-4 rounded text-left mb-6">
    <p class="text-sm text-gray-700">
        <strong>Saran:</strong>
    </p>
    <ul class="text-sm text-gray-600 mt-2 list-disc list-inside">
        <li>Coba muat ulang halaman beberapa saat lagi</li>
        <li>Jika masalah berlanjut, hubungi administrator</li>
        <li>Pastikan koneksi internet Anda stabil</li>
    </ul>
</div>

<div class="flex flex-col sm:flex-row gap-3 justify-center">
    <a href="javascript:location.reload()" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2" />
        </svg>
        Muat Ulang
    </a>
    <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3" />
        </svg>
        Ke Dashboard
    </a>
</div>
@endsection