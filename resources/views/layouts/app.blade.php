<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengajuan Surat | Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Gaya Kustom untuk nuansa Dribbble (Shadows, Green Emerald) */
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }
        .bg-emerald { background-color: #047857; } /* Tailwind emerald-700 */
        .text-emerald-button { color: #059669; } /* Tailwind emerald-600 */
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        @include('components.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white card-shadow p-4 flex justify-between items-center z-10">
                <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Selamat datang, {{ Auth::user()->nama_lengkap ?? 'User' }}</span>
                    <button class="text-gray-500 hover:text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                @yield('content')
            </main>

        </div>
    </div>
</body>
</html>