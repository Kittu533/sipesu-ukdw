@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('pejabat.digital-signature.index') }}" 
               class="text-gray-600 hover:text-gray-800 mr-4">
                ← Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Tanda Tangan Digital</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('pejabat.digital-signature.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Tanda Tangan</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                           placeholder="Contoh: TTD Resmi, TTD Surat Keterangan">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Tanda Tangan</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="type" value="png" class="mr-2" {{ old('type') === 'png' ? 'checked' : '' }}>
                            <span>Upload Gambar PNG</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="type" value="qrcode" class="mr-2" {{ old('type') === 'qrcode' ? 'checked' : '' }}>
                            <span>Generate QR Code</span>
                        </label>
                    </div>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="png-upload" class="mb-4" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File PNG</label>
                    <input type="file" name="signature_file" accept=".png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <p class="text-sm text-gray-500 mt-1">Format: PNG, Maksimal 2MB</p>
                    @error('signature_file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="qr-text" class="mb-4" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teks untuk QR Code</label>
                    <textarea name="qr_text" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                              placeholder="Masukkan teks yang akan dijadikan QR Code">{{ old('qr_text') }}</textarea>
                    @error('qr_text')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('pejabat.digital-signature.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="type"]');
    const pngUpload = document.getElementById('png-upload');
    const qrText = document.getElementById('qr-text');

    typeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'png') {
                pngUpload.style.display = 'block';
                qrText.style.display = 'none';
            } else if (this.value === 'qrcode') {
                pngUpload.style.display = 'none';
                qrText.style.display = 'block';
            }
        });
    });

    // Trigger change event for pre-selected option
    const checkedRadio = document.querySelector('input[name="type"]:checked');
    if (checkedRadio) {
        checkedRadio.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
