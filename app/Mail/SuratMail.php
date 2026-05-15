<?php

namespace App\Mail;

use App\Models\PengajuanSurat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuratMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengajuan;

    public function __construct(PengajuanSurat $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Surat ' . ($this->pengajuan->jenisSurat->nama_surat ?? 'Keterangan') . ' - ' . $this->pengajuan->nomor_surat_resmi,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.surat',
            with: [
                'pengajuan' => $this->pengajuan,
            ],
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        
        if ($this->pengajuan->file_surat_content) {
            $attachments[] = Attachment::fromData(
                fn () => $this->pengajuan->file_surat_content,
                $this->pengajuan->file_surat_name ?? 'surat.pdf'
            )->withMime($this->pengajuan->file_surat_mime_type ?? 'application/pdf');
        }
        
        return $attachments;
    }
}
