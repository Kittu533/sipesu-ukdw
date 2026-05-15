<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Aktif Kuliah</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm 2cm;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.2;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* HEADER SECTION */
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            border-bottom: 2px double black;
            padding-bottom: 5px;
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
            margin: 2px 0 0 0;
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
            margin: 2px 0 0 0;
            font-size: 11pt;
        }

        /* CONTENT SECTION */
        .content {
            text-align: justify;
        }

        .paragraph {
            margin-bottom: 8px; /* Jarak antar paragraf diperkecil */
        }

        /* TABLES */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-left: 15px;
            margin-bottom: 10px;
        }

        .data-table td {
            vertical-align: top;
            padding: 1px 0; /* Padding sel dipersempit */
        }

        .data-table .label {
            width: 180px; /* Lebar label dipersempit */
        }

        .data-table .colon {
            width: 15px;
            text-align: center;
        }

        /* SIGNATURE SECTION */
        .signature-container {
            margin-top: 15px;
            width: 100%;
            display: table;
        }

        .signature-box {
            display: table-cell;
            width: 55%;
        }

        .signature-content {
            display: table-cell;
            width: 45%;
            text-align: left;
        }

        .signature-space {
            height: 100px; /* Diperbesar untuk QR code */
        }

        /* FOOTER SECTION */
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-size: 8.5pt; /* Font footer diperkecil */
            text-align: center;
        }
        
        .footer p {
            margin: 1px 0;
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
                <td class="label">Pangkat</td>
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

        <p class="paragraph">Dan Orang tua anak tersebut adalah :</p>
        <table class="data-table">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td>{{ $parent['nama'] }}</td>
            </tr>
            <tr>
                <td class="label">NIP/No. Pensiun/NRP</td>
                <td class="colon">:</td>
                <td>{{ $parent['nip'] }}</td>
            </tr>
            <tr>
                <td class="label">Pangkat/Golongan/Jabatan</td>
                <td class="colon">:</td>
                <td>{{ $parent['pangkat'] }}</td>
            </tr>
            <tr>
                <td class="label">Instansi Kerja</td>
                <td class="colon">:</td>
                <td>{{ $parent['instansi'] }}</td>
            </tr>
        </table>

        <p class="paragraph">
            Adalah benar-benar terdaftar dan aktif sebagai mahasiswa Universitas Kristen Duta Wacana pada Semester {{ $student['semester_aktif'] }}.
        </p>

        <p class="paragraph">
            Demikian Surat keterangan ini dibuat dengan sesungguhnya, untuk dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <div class="signature-container">
        <div class="signature-box"></div> 
        <div class="signature-content">
            <p>Yogyakarta, {{ $tanggal_surat }}</p>
            <div class="signature-space">
                @if($digital_signature['type'] == 'qrcode')
                    <img src="data:image/png;base64,{{ $digital_signature['base64'] }}" alt="Digital Signature QR Code" style="width: 80px; height: 80px; margin: 5px auto 15px auto; display: block;">
                @elseif($digital_signature['type'] == 'png')
                    <img src="data:image/png;base64,{{ $digital_signature['base64'] }}" alt="Digital Signature" style="width: 120px; height: 60px; margin: 5px auto 15px auto; display: block;">
                @endif
            </div>
            <p><strong>{{ $signatory['nama'] }}</strong></p>
        </div>
    </div>

    <div class="footer">
        <p>Jl. Dr. Wahidin Sudirohusodo No. 5-25, Kota Baru, Gondokusuman, Kota Yogyakarta 55224 [cite: 14]</p>
        <p>Telp: 0274 563929 Ext. 111 dan 144 | Website: birostaff.ukdw.ac.id [cite: 15]</p>
        <p>WA: +62 811-9252-1604 (Administrasi) | +62 898-7701-604 (Perkuliahan) [cite: 16]</p>
    </div>

</body>
</html>