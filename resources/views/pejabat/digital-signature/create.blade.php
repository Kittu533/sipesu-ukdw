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
                            <input type="radio" name="type" value="png" class="mr-2" {{ old('type') === 'canvas' ? '' : 'checked' }}>
                            <span>Upload Gambar PNG</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="type" value="canvas" class="mr-2" {{ old('type') === 'canvas' ? 'checked' : '' }}>
                            <span>Tanda Tangan Canvas</span>
                        </label>
                    </div>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="png-upload" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File PNG</label>
                    <input type="file" name="signature_file" accept=".png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <p class="text-sm text-gray-500 mt-1">Format: PNG, Maksimal 2MB</p>
                    @error('signature_file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="qr-text" class="mb-4" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Canvas</label>
                    <div class="border border-gray-300 rounded-md p-4">
                        <canvas id="signature-canvas" width="500" height="200" class="border border-gray-200 cursor-crosshair bg-white"></canvas>
                        <div class="mt-2 flex space-x-2">
                            <button type="button" id="clear-canvas" class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">
                                Hapus
                            </button>
                            <button type="button" id="save-canvas" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                                Simpan Tanda Tangan
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Gambar tanda tangan Anda di area canvas</p>
                    </div>
                    <input type="hidden" name="canvas_data" id="canvas-data">
                    @error('canvas_data')
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
    const canvas = document.getElementById('signature-canvas');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;

    function startDrawing(e) {
        isDrawing = true;
        draw(e);
    }

    function draw(e) {
        if (!isDrawing) return;
        
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#000';
        
        ctx.lineTo(x, y);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(x, y);
    }

    function stopDrawing() {
        if (isDrawing) {
            isDrawing = false;
            ctx.beginPath();
        }
    }

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);

    canvas.addEventListener('touchstart', function(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousedown', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    });

    canvas.addEventListener('touchmove', function(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousemove', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    });

    canvas.addEventListener('touchend', function(e) {
        e.preventDefault();
        const mouseEvent = new MouseEvent('mouseup', {});
        canvas.dispatchEvent(mouseEvent);
    });

    document.getElementById('clear-canvas').addEventListener('click', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        document.getElementById('canvas-data').value = '';
    });

    document.getElementById('save-canvas').addEventListener('click', function() {
        const dataURL = canvas.toDataURL('image/png');
        document.getElementById('canvas-data').value = dataURL;
        alert('Tanda tangan berhasil disimpan!');
    });

    typeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'png') {
                pngUpload.style.display = 'block';
                qrText.style.display = 'none';
            } else if (this.value === 'canvas') {
                pngUpload.style.display = 'none';
                qrText.style.display = 'block';
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
        });
    });

    const checkedRadio = document.querySelector('input[name="type"]:checked');
    if (checkedRadio) {
        checkedRadio.dispatchEvent(new Event('change'));
    }

    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const selectedType = document.querySelector('input[name="type"]:checked');
        if (selectedType && selectedType.value === 'canvas') {
            const canvasData = canvas.toDataURL('image/png');
            document.getElementById('canvas-data').value = canvasData;
        }
    });
});
</script>
@endsection
