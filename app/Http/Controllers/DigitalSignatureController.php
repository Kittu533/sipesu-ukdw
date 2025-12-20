<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Models\DigitalSignature;

class DigitalSignatureController extends Controller
{
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
            'type' => 'required|in:png,qrcode',
            'signature_file' => 'required_if:type,png|image|mimes:png|max:2048',
            'qr_text' => 'required_if:type,qrcode|string|max:500',
        ]);

        $path = null;
        
        if ($request->type === 'png') {
            $filename = time() . '_signature.png';
            $request->file('signature_file')->storeAs('signatures', $filename, 'public');
            $path = 'signatures/' . $filename;
        } else {
            $filename = time() . '_qr_signature.png';
            $qrCode = new QrCode($request->qr_text);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            Storage::disk('public')->put('signatures/' . $filename, $result->getString());
            $path = 'signatures/' . $filename;
        }

        DigitalSignature::create([
            'user_id' => auth()->user()->id_user,
            'name' => $request->name,
            'type' => $request->type,
            'path' => $path,
            'qr_text' => $request->qr_text,
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
            Storage::disk('public')->put('signatures/' . $filename, $result->getString());
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
