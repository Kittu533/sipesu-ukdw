@props(['stat'])

@php
    $bgClasses = [
        'emerald' => 'bg-emerald-100 text-emerald-700',
        'yellow' => 'bg-yellow-100 text-yellow-700',
        'blue' => 'bg-blue-100 text-blue-700',
        'red' => 'bg-red-100 text-red-700',
    ];
@endphp

<div class="bg-white p-5 rounded-xl card-shadow border border-gray-100 flex items-start space-x-4 hover:ring-2 hover:ring-{{ $stat['color'] }}-300 transition duration-150">
    <div class="p-3 rounded-full {{ $bgClasses[$stat['color']] }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            @if($stat['icon'] == 'inbox')
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0h-3.582c-.366 0-.715.119-1.012.337L10 18v-5H4" />
            @elseif($stat['icon'] == 'clock')
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            @elseif($stat['icon'] == 'thumb-up')
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.857 2.382l-1.5 6A2 2 0 0117.138 21H7.5" />
            @elseif($stat['icon'] == 'x-circle')
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            @endif
        </svg>
    </div>
    
    <div>
        <p class="text-sm font-medium text-gray-500">{{ $stat['title'] }}</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stat['value'] }}</p>
    </div>
</div>