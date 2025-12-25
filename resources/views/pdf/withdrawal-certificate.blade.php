<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Mengundurkan Diri</title>
    <style>
        @page {
            size: A4;
            margin: 1cm 1.5cm;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 10pt;
            line-height: 1.1;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* HEADER SECTION */
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 6px;
            border-bottom: 2px double black;
            padding-bottom: 3px;
        }

        .logo {
            display: table-cell;
            vertical-align: middle;
            width: 80px;
        }

        .logo img {
            width: 65px;
            height: auto;
        }

        .header-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding-right: 65px;
        }

        .header-text h1 {
            font-size: 12pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .header-text h2 {
            font-size: 12pt;
            font-weight: bold;
            margin: 5px 0 0 0;
            text-transform: uppercase;
        }

        /* TITLE SECTION */
        .title {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .title h3 {
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }

        .title p {
            margin: 3px 0 0 0;
            font-size: 10pt;
        }

        /* CONTENT SECTION */
        .content {
            text-align: justify;
        }

        .paragraph {
            margin-bottom: 6px;
            text-align: justify;
            line-height: 1.2;
        }

        /* TABLES */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-left: 20px;
            margin-bottom: 8px;
        }

        .data-table td {
            vertical-align: top;
            padding: 1px 0;
        }

        .data-table .label {
            width: 220px;
        }

        .data-table .colon {
            width: 20px;
            text-align: center;
        }

        /* SIGNATURE SECTION */
        .signature-container {
            margin-top: 20px;
            width: 100%;
            display: table;
        }

        .signature-box {
            display: table-cell;
            width: 40%;
        }

        .signature-content {
            display: table-cell;
            width: 60%;
            text-align: left;
            padding-left: 30px;
        }

        .signature-space {
            height: 80px;
        }

        /* TEMBUSAN SECTION */
        .tembusan {
            margin-top: 10px;
            font-size: 10pt;
        }
        
        .tembusan p {
            margin: 0;
        }
        
        .tembusan ul {
            margin: 0;
            padding-left: 17px;
            list-style-type: decimal;
        }

        /* FOOTER SECTION */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #000;
            padding-top: 3px;
            font-size: 8pt;
            text-align: center;
            background: white;
        }
        
        .footer p {
            margin: 2px 0;
        }

    </style>
</head>
<body>

    <div class="header-container">
        <div class="logo">
            <img src="logo-ukdw.png" alt="Logo UKDW">
        </div>
        <div class="header-text">
            <h1>UNIVERSITAS KRISTEN DUTA WACANA</h1>
            <h2>BIRO ADMINISTRASI AKADEMIK</h2>
        </div>
    </div>

    <div class="title">
        <h3>SURAT KETERANGAN</h3>
        <p>Nomor: {{ $nomor_surat }}</p>
    </div>

    <div class="content">
        <p class="paragraph">Yang bertanda tangan di bawah ini:</p>
        
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
        
        <p class="paragraph">Menerangkan bahwa mahasiswa dengan identitas:</p>
        
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
                <td class="label">Prodi</td>
                <td class="colon">:</td>
                <td>{{ $student['prodi'] }}</td>
            </tr>
        </table>
        
        <p class="paragraph">
            adalah benar pernah menjadi mahasiswa Program Studi {{ $student['prodi'] }} Fakultas {{ $student['fakultas'] }} Universitas Kristen Duta Wacana (UKDW) yang terakreditasi <strong>{{ $student['akreditasi'] }}</strong> berdasarkan keputusan BAN-PT No. {{ $student['sk_ban_pt'] }}.
        </p>
        
        <p class="paragraph">
            Yang bersangkutan aktif kuliah sejak Semester {{ $student['semester_awal'] }} sampai dengan Semester {{ $student['semester_akhir'] }} dan telah menyatakan mengundurkan diri terhitung sejak tanggal {{ $student['tanggal_mundur'] }}, sebagaimana tercantum dalam {{ $student['referensi_surat'] }}.
        </p>
        
        <p class="paragraph">
            Demikian Surat keterangan ini dibuat dengan sesungguhnya, untuk dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>
    
    <div class="signature-container">
        <div class="signature-box"></div>
        <div class="signature-content">
            <p>Yogyakarta, {{ $tanggal_surat }}</p>
            <p>Kepala,</p>
            <div class="signature-space">
                @if(isset($digital_signature) && $digital_signature['type'] == 'qrcode')
                    <img src="data:image/png;base64,{{ $digital_signature['base64'] }}" alt="Digital Signature QR Code" style="width: 80px; height: 80px; margin: 5px auto 15px auto; display: block;">
                @elseif(isset($digital_signature) && $digital_signature['type'] == 'png')
                    <img src="data:image/png;base64,{{ $digital_signature['base64'] }}" alt="Digital Signature" style="width: 120px; height: 60px; margin: 5px auto 15px auto; display: block;">
                @endif
            </div>
            <p><strong>{{ $signatory['nama'] }}</strong></p>
        </div>
    </div>

    <div class="tembusan">
        <p>Tembusan</p>
        <ul>
            <li>Wakil Rektor Bidang Akademik dan Riset</li>
            </ul>
    </div>

    <div class="footer">
        <p>Jl. Dr. Wahidin Sudirohusodo No. 5-25, Kota Baru, Gondokusuman, Kota Yogyakarta 55224</p>
        <p>Telp: 0274 563929 Ext. 111 dan 144 | Email: biro1@staff.ukdw.ac.id</p>
        <p>WA: +62 813-9252-1604 (Administrasi) | +62 898-7701-604 (Perkuliahan)</p>
    </div>

</body>
</html>