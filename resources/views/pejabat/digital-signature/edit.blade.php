@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('pejabat.digital-signature.index') }}" 
               class="text-gray-600 hover:text-gray-800 mr-4">
                ← Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Edit Tanda Tangan Digital</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('pejabat.digital-signature.update', $signature['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Tanda Tangan</label>
                    <input type="text" name="name" value="{{ old('name', $signature->name) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe: {{ strtoupper($signature->type) }}</label>
                    <div class="border rounded-md p-4 bg-gray-50">
                        <img src="{{ asset('storage/' . $signature->path) }}" 
                             alt="Current Signature" class="h-20 w-auto">
                    </div>
                </div>

                @if($signature->type === 'png')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File PNG Baru (Opsional)</label>
                    <input type="file" name="signature_file" accept=".png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah file</p>
                    @error('signature_file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                @if($signature->type === 'qrcode')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teks QR Code</label>
                    <textarea name="qr_text" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('qr_text', $signature->qr_text) }}</textarea>
                    @error('qr_text')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('pejabat.digital-signature.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
