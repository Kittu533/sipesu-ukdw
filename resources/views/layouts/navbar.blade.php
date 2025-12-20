<header class="sticky top-0 bg-white border-b border-gray-200 z-30">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 -mb-px">

            <div class="flex items-center">
                <button
                    class="text-gray-500 hover:text-gray-600 lg:hidden"
                    aria-controls="sidebar"
                    aria-expanded="false"
                    onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')"
                >
                    <span class="sr-only">Toggle sidebar</span>
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="5" width="16" height="2" />
                        <rect x="4" y="11" width="16" height="2" />
                        <rect x="4" y="17" width="16" height="2" />
                    </svg>
                </button>
            </div>

            <div class="flex items-center space-x-3">
                
                <button class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded-full text-gray-500 relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">3</span>
                </button>

                <div class="relative">
                    <button class="flex items-center space-x-2 text-sm focus:outline-none">
                        <span class="hidden md:block text-gray-700 font-medium">Nama Pengguna</span>
                        <img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name=UKDW&background=00796B&color=fff&size=256" alt="User Avatar">
                    </button>
                    </div>
            </div>

        </div>
    </div>
</header>