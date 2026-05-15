@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Detail Pengajuan Surat</h2>
            <p class="text-gray-500 text-sm mt-1">Detail lengkap pengajuan surat mahasiswa.</p>
        </div>
        <div class="flex space-x-2">
            @if($pengajuan->status_saat_ini === 'Selesai' && $pengajuan->file_surat_content)
            <a href="{{ route('admin.submission.print', $pengajuan->id_pengajuan) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center"
               target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Surat
            </a>
            @endif
            
            @if(Auth::user()->id_hak_akses == 2 && $pengajuan->status_saat_ini == 'Menunggu Verifikasi Admin')
            <form action="{{ route('admin.submission.verify', $pengajuan->id_pengajuan) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah data sudah lengkap dan valid?');">
                @csrf
                <input type="hidden" name="action" value="approve">
                <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Verifikasi
                </button>
            </form>
            <button onclick="openRejectModal('{{ $pengajuan->id_pengajuan }}', 'admin')" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Tolak
            </button>
            @endif
            
            @if(Auth::user()->id_hak_akses == 3 && $pengajuan->status_saat_ini == 'Menunggu Validasi Dekan')
            <form action="{{ route('dekan.validation.process', $pengajuan->id_pengajuan) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah data sudah lengkap dan valid?');">
                @csrf
                <input type="hidden" name="action" value="accept">
                <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Validasi Dekan
                </button>
            </form>
            <button onclick="openRejectModal('{{ $pengajuan->id_pengajuan }}', 'dekan')" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Tolak
            </button>
            @endif
            
            @if(Auth::user()->id_hak_akses == 2 && $pengajuan->status_saat_ini == 'Menunggu Proses Admin')
            <div class="flex space-x-2">
                <form action="{{ route('admin.submission.final_process', $pengajuan->id_pengajuan) }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="action" value="cetak_email">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak & Kirim Email
                    </button>
                </form>
                <form action="{{ route('admin.submission.final_process', $pengajuan->id_pengajuan) }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="action" value="cetak">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Saja
                    </button>
                </form>
                <form action="{{ route('admin.submission.final_process', $pengajuan->id_pengajuan) }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="action" value="kirim_email">
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-purple-700 transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Kirim Email Saja
                    </button>
                </form>
            </div>
            @endif
            
            <a href="{{ Auth::user()->id_hak_akses == 3 ? route('dekan.validation.index') : route('admin.submission.index') }}" 
               class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Utama -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Mahasiswa -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Mahasiswa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama Lengkap</label>
                        <p class="text-gray-900 font-medium">{{ $pengajuan->mahasiswa->user->nama_lengkap ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">NIM</label>
                        <p class="text-gray-900 font-mono">{{ $pengajuan->mahasiswa->nim ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Program Studi</label>
                        <p class="text-gray-900">{{ $pengajuan->mahasiswa->prodi->nama_prodi ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Angkatan</label>
                        <p class="text-gray-900">{{ $pengajuan->mahasiswa->angkatan ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Data Pengajuan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Pengajuan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Jenis Surat</label>
                        <p class="text-gray-900 font-medium">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Alur Pengajuan</label>
                        <p class="text-gray-900 font-medium">
                            @if($pengajuan->jenisSurat->perlu_validasi_dekan ?? true)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Panjang (Admin → Kepala Biro → Dekan)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Pendek (Admin → Kepala Biro)
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tanggal Pengajuan</label>
                        <p class="text-gray-900">{{ $pengajuan->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nomor Surat</label>
                        <p class="text-gray-900 font-mono">{{ $pengajuan->nomor_surat_resmi ?? 'Belum ada' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500">Status Saat Ini</label>
                        @php
                            $statusClass = match($pengajuan->status_saat_ini) {
                                'Menunggu Verifikasi Admin' => 'bg-orange-100 text-orange-800',
                                'Menunggu Verifikasi' => 'bg-yellow-100 text-yellow-800',
                                'Menunggu Tanda Tangan' => 'bg-purple-100 text-purple-800',
                                'Menunggu Validasi Dekan' => 'bg-purple-100 text-purple-800',
                                'Menunggu Proses Admin' => 'bg-blue-100 text-blue-800',
                                'Selesai' => 'bg-emerald-100 text-emerald-800',
                                'Ditolak' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                            {{ $pengajuan->status_saat_ini }}
                        </span>
                    </div>
                </div>
                
                @if($pengajuan->keterangan_mahasiswa)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-500">Keterangan Mahasiswa</label>
                    <p class="text-gray-900 bg-gray-50 p-3 rounded-lg mt-1">{{ $pengajuan->keterangan_mahasiswa }}</p>
                </div>
                @endif
            </div>

            <!-- Detail Pengajuan -->
            @if($pengajuan->detailPengajuan->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Pengajuan</h3>
                <div class="space-y-3">
                    @foreach($pengajuan->detailPengajuan as $detail)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                        <span class="text-sm font-medium text-gray-500">{{ $detail->label_field }}</span>
                        <span class="text-gray-900">{{ $detail->nilai_field }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Timeline Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline Status</h3>
                <div class="space-y-4">
                    @forelse($pengajuan->logStatusSurat->sortBy('created_at') as $log)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $log->status_baru }}</p>
                            <p class="text-xs text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                            @if($log->keterangan)
                            <p class="text-xs text-gray-600 mt-1">{{ $log->keterangan }}</p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">Belum ada log status.</p>
                    @endforelse
                </div>
            </div>

            <!-- Validasi Dekan -->
            @if($pengajuan->validasiStaff->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Validasi Dekan</h3>
                @foreach($pengajuan->validasiStaff as $validasi)
                <div class="mb-3 last:mb-0">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900">{{ $validasi->user->nama_lengkap ?? 'Dekan' }}</span>
                        <span class="text-xs px-2 py-1 rounded-full {{ $validasi->status_validasi == 'Disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $validasi->status_validasi }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500">{{ $validasi->created_at->format('d/m/Y H:i') }}</p>
                    @if($validasi->catatan)
                    <p class="text-xs text-gray-600 mt-1">{{ $validasi->catatan }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            <!-- Persetujuan Pejabat -->
            @if($pengajuan->persetujuanPejabat->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Persetujuan Pejabat</h3>
                @foreach($pengajuan->persetujuanPejabat as $persetujuan)
                <div class="mb-3 last:mb-0">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900">{{ $persetujuan->pejabat->user->nama_lengkap ?? 'Pejabat' }}</span>
                        <span class="text-xs px-2 py-1 rounded-full {{ $persetujuan->status_persetujuan == 'Disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $persetujuan->status_persetujuan }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500">{{ $persetujuan->created_at->format('d/m/Y H:i') }}</p>
                    @if($persetujuan->catatan)
                    <p class="text-xs text-gray-600 mt-1">{{ $persetujuan->catatan }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>


<!-- Modal Tolak -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeRejectModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="rejectForm" method="POST" action="">
                @csrf
                <input type="hidden" name="action" value="reject">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tolak Pengajuan</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">Silakan masukkan alasan penolakan untuk mahasiswa.</p>
                                <textarea name="catatan" rows="3" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Contoh: Dokumen lampiran buram / tidak lengkap..." required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Tolak Pengajuan
                    </button>
                    <button type="button" onclick="closeRejectModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRejectModal(id, type) {
        if (type === 'admin') {
            document.getElementById('rejectForm').action = "/admin/submissions/" + id + "/verify";
        } else {
            document.getElementById('rejectForm').action = "/dekan/validation/" + id;
        }
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection
