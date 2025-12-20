<ol class="relative border-s border-gray-200 ml-3">                  
    @php
        $activities = [
            ['time' => '10:30 WIB', 'action' => 'Pejabat Berwenang memberikan persetujuan pada surat "Izin Penelitian" (Siti Aisyah).', 'color' => 'blue'],
            ['time' => '09:15 WIB', 'action' => 'Admin Akademik mencetak dan mengirim surat "Keterangan Aktif Kuliah" (Budi Santoso).', 'color' => 'emerald'],
            ['time' => 'Kemarin', 'action' => 'Staff Jurusan memvalidasi surat "Cuti Akademik" (Joko Permana).', 'color' => 'yellow'],
            ['time' => '2 Hari Lalu', 'action' => 'Mahasiswa mengajukan surat baru "Permohonan Magang" (Maya Sari).', 'color' => 'gray'],
        ];
    @endphp

    @foreach ($activities as $activity)
    <li class="mb-8 ms-6">
        <span class="absolute flex items-center justify-center w-3 h-3 rounded-full -start-1.5 ring-4 ring-white 
            @if($activity['color'] == 'emerald') bg-emerald-600 @elseif($activity['color'] == 'blue') bg-blue-600 @elseif($activity['color'] == 'yellow') bg-yellow-600 @else bg-gray-400 @endif">
        </span>
        <time class="mb-1 text-xs font-normal leading-none text-gray-400">{{ $activity['time'] }}</time>
        <p class="text-sm font-normal text-gray-700 mt-1">{{ $activity['action'] }}</p>
    </li>
    @endforeach
</ol>