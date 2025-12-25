@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Mahasiswa</h2>
            <p class="text-gray-500 text-sm mt-1">Ubah data mahasiswa yang sudah ada.</p>
        </div>
        <a href="{{ route('admin.mahasiswa.index') }}" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-200 transition flex items-center border-2 border-blue-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.mahasiswa.update', $mahasiswa->id_mahasiswa) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" 
                           value="{{ old('nama_lengkap', $mahasiswa->user->nama_lengkap) }}" 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('nama_lengkap') border-red-300 @enderror" 
                           required>
                    @error('nama_lengkap')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">NIM</label>
                    <input type="text" name="nim" id="nim" 
                           value="{{ old('nim', $mahasiswa->nim) }}" 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('nim') border-red-300 @enderror" 
                           required>
                    @error('nim')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" 
                           value="{{ old('email', $mahasiswa->user->email) }}" 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('email') border-red-300 @enderror" 
                           required>
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="id_prodi" class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                    <select name="id_prodi" id="id_prodi" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('id_prodi') border-red-300 @enderror" 
                            required>
                        <option value="">Pilih Program Studi</option>
                        @foreach($prodiList as $prodi)
                        <option value="{{ $prodi->id_prodi }}" {{ old('id_prodi', $mahasiswa->id_prodi) == $prodi->id_prodi ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_prodi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="angkatan" class="block text-sm font-medium text-gray-700 mb-2">Angkatan</label>
                    <input type="number" name="angkatan" id="angkatan" 
                           value="{{ old('angkatan', $mahasiswa->angkatan) }}" 
                           min="2000" max="{{ date('Y') + 1 }}"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('angkatan') border-red-300 @enderror" 
                           required>
                    @error('angkatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ipk_terakhir" class="block text-sm font-medium text-gray-700 mb-2">IPK Terakhir</label>
                    <input type="number" name="ipk_terakhir" id="ipk_terakhir" 
                           value="{{ old('ipk_terakhir', $mahasiswa->ipk_terakhir) }}" 
                           step="0.01" min="0" max="4"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('ipk_terakhir') border-red-300 @enderror">
                    @error('ipk_terakhir')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.mahasiswa.index') }}" 
                   class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium">
                    Update Mahasiswa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
