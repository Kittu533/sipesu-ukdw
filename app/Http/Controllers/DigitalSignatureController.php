<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Logo\Logo;
use App\Models\DigitalSignature;
use App\Models\Pejabat;

class DigitalSignatureController extends Controller
{
    private function formattedSignerName(): string
    {
        $name = trim(auth()->user()->nama_lengkap);
        $name = preg_replace('/\b(Drs?|Dra|Prof|Ir|Hj?)\.\s*/', '$1. ', $name);
        $name = preg_replace('/\s*,\s*/', ', ', $name);
        $name = preg_replace('/\s+/', ' ', $name);

        return trim($name);
    }

    public function index()
    {
        $signatures = DigitalSignature::where('user_id', auth()->user()->id_user)
                                    ->where('is_active', true)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        return view('pejabat.digital-signature.index', compact('signatures'));
    }

    public function create()
    {
        return view('pejabat.digital-signature.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:png,canvas',
            'signature_file' => 'required_if:type,png|image|mimes:png|max:2048',
            'canvas_data' => 'required_if:type,canvas|string',
        ]);

        $path = null;
        
        if ($request->type === 'png') {
            $filename = time() . '_signature.png';
            $request->file('signature_file')->storeAs('signatures', $filename, 'public');
            $path = 'signatures/' . $filename;
        } else {
            // Process canvas data and generate QR code
            $canvasData = $request->canvas_data;
            $imageData = str_replace('data:image/png;base64,', '', $canvasData);
            $imageData = str_replace(' ', '+', $imageData);
            $decodedImage = base64_decode($imageData);
            
            // Save canvas signature
            $canvasFilename = time() . '_canvas_signature.png';
            Storage::disk('public')->put('signatures/' . $canvasFilename, $decodedImage);
            
            // Generate QR code with small UKDW logo
            $qrFilename = time() . '_qr_signature.png';
            $qrText = "Digital Signature: " . $this->formattedSignerName() . " - " . now()->format('Y-m-d H:i:s');
            
            $qrCode = new QrCode($qrText);
            
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            
            // Add small UKDW logo manually to center
            $qrImage = imagecreatefromstring($result->getString());
            $logoPath = public_path('logo-ukdw.png');
            
            if (file_exists($logoPath)) {
                $logo = imagecreatefrompng($logoPath);
                $qrWidth = imagesx($qrImage);
                $qrHeight = imagesy($qrImage);
                
                // Make logo small (15% of QR code size)
                $logoSize = min($qrWidth, $qrHeight) * 0.15;
                $logoResized = imagescale($logo, $logoSize, $logoSize);
                
                // Center the logo
                $logoX = ($qrWidth - $logoSize) / 2;
                $logoY = ($qrHeight - $logoSize) / 2;
                
                // Add white background circle for logo
                $white = imagecolorallocate($qrImage, 255, 255, 255);
                imagefilledellipse($qrImage, $qrWidth/2, $qrHeight/2, $logoSize + 10, $logoSize + 10, $white);
                
                // Place logo on QR code
                imagecopy($qrImage, $logoResized, $logoX, $logoY, 0, 0, $logoSize, $logoSize);
                
                // Save the final image
                ob_start();
                imagepng($qrImage);
                $finalImage = ob_get_contents();
                ob_end_clean();
                
                Storage::disk('public')->put('signatures/' . $qrFilename, $finalImage);
                
                imagedestroy($qrImage);
                imagedestroy($logo);
                imagedestroy($logoResized);
            } else {
                Storage::disk('public')->put('signatures/' . $qrFilename, $result->getString());
            }
            $path = 'signatures/' . $qrFilename;
        }

        $signature = DigitalSignature::create([
            'user_id' => auth()->user()->id_user,
            'name' => $request->name,
            'type' => $request->type === 'canvas' ? 'qrcode' : $request->type,
            'path' => $path,
            'qr_text' => $request->type === 'canvas' ? $qrText : null,
        ]);

        Pejabat::where('id_user', auth()->user()->id_user)->update([
            'tanda_tangan_digital_path' => $signature->path,
            'is_aktif_ttd' => true,
        ]);

        return redirect()->route('pejabat.digital-signature.index')
                        ->with('success', 'Tanda tangan digital berhasil ditambahkan');
    }

    public function edit($id)
    {
        $signature = DigitalSignature::where('user_id', auth()->user()->id_user)
                                   ->where('id', $id)
                                   ->firstOrFail();
        return view('pejabat.digital-signature.edit', compact('signature'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'signature_file' => 'nullable|image|mimes:png|max:2048',
            'qr_text' => 'nullable|string|max:500',
        ]);

        $signature = DigitalSignature::where('user_id', auth()->user()->id_user)
                                   ->where('id', $id)
                                   ->firstOrFail();

        // Update file if provided
        if ($request->hasFile('signature_file')) {
            Storage::disk('public')->delete($signature->path);
            
            $filename = time() . '_signature.png';
            $request->file('signature_file')->storeAs('signatures', $filename, 'public');
            $signature->path = 'signatures/' . $filename;
        }

        // Update QR code if provided
        if ($request->qr_text && $signature->type === 'qrcode') {
            Storage::disk('public')->delete($signature->path);
            
            $filename = time() . '_qr_signature.png';
            $qrCode = new QrCode($request->qr_text);
            
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            
            // Add small UKDW logo manually to center
            $qrImage = imagecreatefromstring($result->getString());
            $logoPath = public_path('logo-ukdw.png');
            
            if (file_exists($logoPath)) {
                $logo = imagecreatefrompng($logoPath);
                $qrWidth = imagesx($qrImage);
                $qrHeight = imagesy($qrImage);
                
                // Make logo small (15% of QR code size)
                $logoSize = min($qrWidth, $qrHeight) * 0.15;
                $logoResized = imagescale($logo, $logoSize, $logoSize);
                
                // Center the logo
                $logoX = ($qrWidth - $logoSize) / 2;
                $logoY = ($qrHeight - $logoSize) / 2;
                
                // Add white background circle for logo
                $white = imagecolorallocate($qrImage, 255, 255, 255);
                imagefilledellipse($qrImage, $qrWidth/2, $qrHeight/2, $logoSize + 10, $logoSize + 10, $white);
                
                // Place logo on QR code
                imagecopy($qrImage, $logoResized, $logoX, $logoY, 0, 0, $logoSize, $logoSize);
                
                // Save the final image
                ob_start();
                imagepng($qrImage);
                $finalImage = ob_get_contents();
                ob_end_clean();
                
                Storage::disk('public')->put('signatures/' . $filename, $finalImage);
                
                imagedestroy($qrImage);
                imagedestroy($logo);
                imagedestroy($logoResized);
            } else {
                Storage::disk('public')->put('signatures/' . $filename, $result->getString());
            }
            
            $signature->path = 'signatures/' . $filename;
            $signature->qr_text = $request->qr_text;
        }

        $signature->name = $request->name;
        $signature->save();

        return redirect()->route('pejabat.digital-signature.index')
                        ->with('success', 'Tanda tangan digital berhasil diperbarui');
    }

    public function destroy($id)
    {
        $signature = DigitalSignature::where('user_id', auth()->user()->id_user)
                                   ->where('id', $id)
                                   ->firstOrFail();
        
        Storage::disk('public')->delete($signature->path);
        $signature->delete();

        return redirect()->route('pejabat.digital-signature.index')
                        ->with('success', 'Tanda tangan digital berhasil dihapus');
    }
}
