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

        <!-- Mobile menu overlay -->
        <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

        @include('components.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white card-shadow p-4 flex justify-between items-center z-10">
                <div class="flex items-center space-x-3">
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="lg:hidden text-gray-600 hover:text-emerald-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-800">Dashboard</h1>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <span class="text-xs sm:text-sm text-gray-600 hidden sm:block">Selamat datang, {{ Auth::user()->nama_lengkap ?? 'User' }}</span>
                    <span class="text-xs text-gray-600 sm:hidden">{{ explode(' ', Auth::user()->nama_lengkap ?? 'User')[0] }}</span>
                    
                    <!-- Notification Bell -->
                    <div class="relative">
                        @php
                            $roleName = App\Models\Notifikasi::getRoleName(Auth::user()->id_hak_akses);
                            $unreadCount = App\Models\Notifikasi::where(function($q) use ($roleName) {
                                $q->where('id_user_penerima', Auth::user()->id_user)
                                  ->orWhere('role_penerima', $roleName);
                            })->where('is_read', false)->count();
                            $recentNotifications = App\Models\Notifikasi::where(function($q) use ($roleName) {
                                $q->where('id_user_penerima', Auth::user()->id_user)
                                  ->orWhere('role_penerima', $roleName);
                            })->orderBy('tgl_kirim', 'desc')->take(5)->get();
                        @endphp
                        @include('components.notification-bell')
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-3 sm:p-6">
                @yield('content')
            </main>

        </div>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const sidebar = document.getElementById('sidebar');

        mobileMenuButton.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            mobileMenuOverlay.classList.toggle('hidden');
        });

        mobileMenuOverlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            mobileMenuOverlay.classList.add('hidden');
        });

        // Close mobile menu when clicking on menu items
        const menuItems = sidebar.querySelectorAll('a');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    sidebar.classList.add('-translate-x-full');
                    mobileMenuOverlay.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>