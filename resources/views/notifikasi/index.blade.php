@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Notifikasi</h2>
            <p class="text-gray-500 text-sm mt-1">Daftar notifikasi untuk Anda.</p>
        </div>
        @if($notifikasi->where('is_read', false)->count() > 0)
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                Tandai semua dibaca
            </button>
        </form>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @forelse($notifikasi as $item)
        <a href="{{ $item->link ? route('notifications.read', $item->id_notifikasi) : 'javascript:void(0)' }}" 
           class="block px-6 py-4 hover:bg-gray-50 border-b border-gray-100 last:border-0 transition-colors {{ !$item->is_read ? 'bg-blue-50' : '' }}">
            <div class="flex items-start">
                <div class="flex-shrink-0 mt-1">
                    @if($item->type === 'success')
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    @elseif($item->type === 'error')
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    @elseif($item->type === 'warning')
                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="ml-4 flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-gray-900">{{ $item->judul }}</p>
                        <span class="text-xs text-gray-400">{{ $item->tgl_kirim->format('d/m/Y H:i') }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">{{ $item->pesan }}</p>
                    @if(!$item->is_read)
                        <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Baru
                        </span>
                    @endif
                </div>
            </div>
        </a>
        @empty
        <div class="px-6 py-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <p class="text-gray-500 font-medium">Tidak ada notifikasi</p>
            <p class="text-gray-400 text-sm mt-1">Notifikasi akan muncul ketika ada aktivitas terkait akun Anda.</p>
        </div>
        @endforelse
    </div>

    @if($notifikasi->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $notifikasi->links() }}
    </div>
    @endif
</div>
@endsection
