@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Program Studi</h2>
            <p class="text-gray-500 text-sm mt-1">Perbarui informasi program studi.</p>
        </div>
        <a href="{{ route('admin.prodi.index') }}" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-200 transition flex items-center border border-blue-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.prodi.update', $prodi->id_prodi) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Prodi -->
                <div>
                    <label for="kode_prodi" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Program Studi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="kode_prodi" 
                           name="kode_prodi" 
                           value="{{ old('kode_prodi', $prodi->kode_prodi) }}"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('kode_prodi') border-red-300 bg-red-50 @enderror"
                           placeholder="Contoh: TI, SI, MI"
                           maxlength="10"
                           required>
                    @error('kode_prodi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Prodi -->
                <div>
                    <label for="nama_prodi" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Program Studi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama_prodi" 
                           name="nama_prodi" 
                           value="{{ old('nama_prodi', $prodi->nama_prodi) }}"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('nama_prodi') border-red-300 bg-red-50 @enderror"
                           placeholder="Contoh: Teknik Informatika"
                           maxlength="255"
                           required>
                    @error('nama_prodi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.prodi.index') }}" 
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
