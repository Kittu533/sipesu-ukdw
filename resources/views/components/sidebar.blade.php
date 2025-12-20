<nav class="flex flex-col bg-white w-64 border-r border-gray-200 card-shadow">
    <div class="p-6">
        <h2 class="text-2xl font-bold text-emerald-700">SIPESU</h2>
        <p class="text-xs text-gray-500">Sistem Pengajuan Surat</p>
    </div>

    <div class="flex-1 space-y-2 p-4">
        @php
            $roleId = Auth::user()->id_hak_akses ?? 0;
            $menus = [];

            if ($roleId == 1) { // Mahasiswa
                $menus = [
                    ['name' => 'Dashboard', 'icon' => 'home', 'link' => route('dashboard')],
                    ['name' => 'Buat Pengajuan', 'icon' => 'plus-circle', 'link' => route('submission.create')],
                    ['name' => 'Status Pengajuan', 'icon' => 'clock', 'link' => route('submission.status')],
                    ['name' => 'Riwayat Selesai', 'icon' => 'archive', 'link' => route('submission.history')],
                    ['name' => 'Panduan Pengajuan', 'icon' => 'book-open', 'link' => route('panduan.pengajuan')],
                ];
            } elseif ($roleId == 2) { // Admin
                $menus = [
                    ['name' => 'Dashboard', 'icon' => 'home', 'link' => route('dashboard')],
                    ['name' => 'Data Mahasiswa', 'icon' => 'users', 'link' => route('admin.mahasiswa.index')], 
                    ['name' => 'Data Prodi', 'icon' => 'academic-cap', 'link' => route('admin.prodi.index')], 
                    ['name' => 'Kelola Pengajuan', 'icon' => 'clipboard-list', 'link' => route('admin.submission.index')],
                    ['name' => 'Arsip Surat', 'icon' => 'archive', 'link' => route('archive.index')],
                ];
            } elseif ($roleId == 3) { // Staff
                $menus = [
                    ['name' => 'Dashboard', 'icon' => 'home', 'link' => route('dashboard')],
                    ['name' => 'Validasi Pengajuan', 'icon' => 'check-square', 'link' => route('staff.validation.index')],
                    ['name' => 'Arsip Jurusan', 'icon' => 'archive', 'link' => route('archive.index')],
                ];
            } elseif ($roleId == 4) { // Pejabat
                $menus = [
                    ['name' => 'Dashboard', 'icon' => 'home', 'link' => route('dashboard')],
                    ['name' => 'Persetujuan Surat', 'icon' => 'pen-tool', 'link' => route('pejabat.approval')],
                    ['name' => 'Riwayat Persetujuan', 'icon' => 'archive', 'link' => route('pejabat.history')],
                    ['name' => 'Tanda Tangan Digital', 'icon' => 'signature', 'link' => route('pejabat.digital-signature.index')],
                ];
            } else {
                $menus = [
                    ['name' => 'Dashboard', 'icon' => 'home', 'link' => route('dashboard')],
                ];
            }
        @endphp

        @foreach ($menus as $menu)
            <a href="{{ $menu['link'] }}" class="flex items-center space-x-3 p-3 rounded-xl transition duration-150 
                @if(request()->url() == $menu['link']) bg-emerald-100 text-emerald-700 font-semibold card-shadow 
                @else text-gray-600 hover:bg-gray-50 hover:text-gray-800 @endif">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    @if($menu['icon'] == 'home')
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 12v10a1 1 0 001 1h3v-7h6v7h3a1 1 0 001-1V12" />
                    @elseif($menu['icon'] == 'plus-circle')
                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    @elseif($menu['icon'] == 'clock')
                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    @elseif($menu['icon'] == 'users')
                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    @elseif($menu['icon'] == 'check-square')
                         <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    @elseif($menu['icon'] == 'pen-tool')
                         <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    @elseif($menu['icon'] == 'archive')
                         <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    @elseif($menu['icon'] == 'academic-cap')
                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    @elseif($menu['icon'] == 'clipboard-list')
                         <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    @elseif($menu['icon'] == 'book-open')
                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    @elseif($menu['icon'] == 'signature')
                         <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                         <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                    @endif
                </svg>
                <span>{{ $menu['name'] }}</span>
            </a>
        @endforeach
    </div>

    <div class="p-4 border-t border-gray-200">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center space-x-3 p-3 rounded-xl text-red-500 hover:bg-red-50 transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-3m0 0v-4a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</nav>