<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Pernah Kuliah (Pengunduran Diri)</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .header p {
            margin: 2px 0;
            font-size: 11pt;
        }
        
        .divider {
            border-bottom: 2px solid black;
            margin: 20px 0;
        }
        
        .title {
            text-align: center;
            margin: 30px 0;
        }
        
        .title h2 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0;
        }
        
        .content {
            text-align: justify;
            margin: 20px 0;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .data-table td {
            padding: 3px 0;
            vertical-align: top;
            border: none;
        }
        
        .data-table .label {
            width: 200px;
        }
        
        .data-table .colon {
            width: 20px;
            text-align: center;
        }
        
        .signature {
            margin-top: 50px;
            text-align: right;
            width: 300px;
            float: right;
        }
        
        .signature-space {
            height: 80px;
            margin: 20px 0;
        }
        
        .tembusan {
            margin-top: 100px;
            clear: both;
        }
        
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <h1>UNIVERSITAS KRISTEN DUTA WACANA</h1>
        <h1>BIRO ADMINISTRASI AKADEMIK</h1>
        <p>Jl. Dr. Wahidin Sudirohusodo No. 5-25, Yogyakarta 55224</p>
        <p>Telp: 0274-563929 Fax: 0274-513235 | Email: biro1@staff.ukdw.ac.id</p>
    </div>
    
    <div class="divider"></div>
    
    <!-- TITLE -->
    <div class="title">
        <h2>SURAT KETERANGAN</h2>
        <p>Nomor: {{ $nomor_surat }}</p>
    </div>
    
    <!-- CONTENT -->
    <div class="content">
        <p>Yang bertanda tangan di bawah ini:</p>
        
        <table class="data-table">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td>{{ $signatory['nama'] }}</td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td class="colon">:</td>
                <td>{{ $signatory['nik'] }}</td>
            </tr>
            <tr>
                <td class="label">Pangkat, Golongan ruang</td>
                <td class="colon">:</td>
                <td>{{ $signatory['pangkat'] }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="colon">:</td>
                <td>{{ $signatory['jabatan'] }} Universitas Kristen Duta Wacana Yogyakarta</td>
            </tr>
        </table>
        
        <p>Menerangkan bahwa mahasiswa dengan identitas:</p>
        
        <table class="data-table">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td>{{ $student['nama'] }}</td>
            </tr>
            <tr>
                <td class="label">Tempat, tanggal lahir</td>
                <td class="colon">:</td>
                <td>{{ $student['tempat_lahir'] }}, {{ $student['tanggal_lahir'] }}</td>
            </tr>
            <tr>
                <td class="label">Nomor Induk Mahasiswa</td>
                <td class="colon">:</td>
                <td>{{ $student['nim'] }}</td>
            </tr>
            <tr>
                <td class="label">Fakultas</td>
                <td class="colon">:</td>
                <td>{{ $student['fakultas'] }}</td>
            </tr>
            <tr>
                <td class="label">Program Studi</td>
                <td class="colon">:</td>
                <td>{{ $student['prodi'] }}</td>
            </tr>
        </table>
        
        <p>Adalah benar pernah menempuh studi di Program Studi {{ $student['prodi'] }}, Fakultas {{ $student['fakultas'] }}, Universitas Kristen Duta Wacana yang memiliki peringkat akreditasi <strong>{{ $student['akreditasi'] }}</strong> berdasarkan SK BAN-PT Nomor {{ $student['sk_ban_pt'] }}.</p>
        
        <p>Yang bersangkutan aktif kuliah mulai Semester {{ $student['semester_awal'] }} sampai dengan Semester {{ $student['semester_akhir'] }}, dan mengundurkan diri terhitung sejak tanggal {{ $student['tanggal_mundur'] }} berdasarkan {{ $student['referensi_surat'] }}.</p>
        
        <p>Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>
    
    <!-- SIGNATURE -->
    <div class="signature">
        <p>Yogyakarta, {{ $tanggal_surat }}</p>
        <p>Kepala,</p>
        <div class="signature-space">
            <p style="font-style: italic; color: #666; margin-top: 30px;">(Tanda Tangan & Stempel)</p>
        </div>
        <p><strong>{{ $signatory['nama'] }}</strong></p>
    </div>
    
    <!-- TEMBUSAN -->
    <div class="tembusan">
        <p><strong>Tembusan:</strong></p>
        <p>1. Wakil Rektor Bidang Akademik dan Riset UKDW</p>
        <p>2. Dekan Fakultas {{ $student['fakultas'] }}</p>
        <p>3. Arsip</p>
    </div>
    
    <div class="clear"></div>
</body>
</html>
