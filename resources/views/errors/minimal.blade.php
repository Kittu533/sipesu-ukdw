@extends('errors.layout')

@section('title', 'Kesalahan')

@section('content')
<div class="mb-6">
    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
        <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $code ?? 'Error' }}</h1>
    <h2 class="text-xl font-semibold text-gray-700 mb-2">{{ $title ?? 'Terjadi Kesalahan' }}</h2>
    <p class="text-gray-600 mb-4">{{ $message ?? 'Maaf, terjadi kesalahan yang tidak terduga. Silakan coba lagi nanti.' }}</p>
</div>

<div class="flex flex-col sm:flex-row gap-3 justify-center">
    <a href="javascript:history.back()" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h18" />
        </svg>
        Kembali
    </a>
    <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3" />
        </svg>
        Ke Dashboard
    </a>
</div>
@endsection