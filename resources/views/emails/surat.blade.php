<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat {{ $pengajuan->jenisSurat->nama_surat ?? 'Keterangan' }}</title>
</head>
<body>
    <h2>Surat {{ $pengajuan->jenisSurat->nama_surat ?? 'Keterangan' }}</h2>
    
    <p>Yth. {{ $pengajuan->mahasiswa->user->nama_lengkap ?? 'Mahasiswa' }},</p>
    
    <p>Dengan hormat,</p>
    <p>Surat {{ $pengajuan->jenisSurat->nama_surat ?? 'keterangan' }} dengan nomor: <strong>{{ $pengajuan->nomor_surat_resmi ?? '-' }}</strong> telah selesai diproses dan dapat diambil di kantor administrasi.</p>
    
    <p>Jika surat dilampirkan pada email ini, surat tersebut telah ditandatangani secara digital oleh pihak yang berwenang.</p>
    
    <br>
    <p>Hormat kami,<br>Admin SIPESU</p>
</body>
</html>
