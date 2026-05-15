<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Alumni</title>
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
            font-size: 14pt;
            font-weight: bold;
            margin: 5px 0 0 0;
            text-transform: uppercase;
        }

        /* TITLE SECTION */
        .title {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 25px;
        }

        .title h3 {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }

        .title p {
            margin: 5px 0 0 0;
            font-size: 12pt;
        }

        /* CONTENT SECTION */
        .content {
            text-align: justify;
        }

        .paragraph {
            margin-bottom: 10px;
            text-align: justify;
        }

        /* TABLES */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-left: 20px;
            margin-bottom: 15px;
        }

        .data-table td {
            vertical-align: top;
            padding: 2px 0;
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
            margin-top: 30px;
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
            padding-left: 50px;
        }

        .signature-space {
            height: 100px;
        }

        /* FOOTER SECTION */
        .footer {
            margin-top: 50px;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-size: 9pt;
            text-align: center;
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
                <td>{{ $alumni['nama'] }}</td>
            </tr>
            <tr>
                <td class="label">Tempat, tanggal lahir</td>
                <td class="colon">:</td>
                <td>{{ $alumni['tempat_lahir'] }}, {{ $alumni['tanggal_lahir'] }}</td>
            </tr>
            <tr>
                <td class="label">Nomor Induk Mahasiswa</td>
                <td class="colon">:</td>
                <td>{{ $alumni['nim'] }}</td>
            </tr>
            <tr>
                <td class="label">Fakultas</td>
                <td class="colon">:</td>
                <td>{{ $alumni['fakultas'] }}</td>
            </tr>
            <tr>
                <td class="label">Prodi</td>
                <td class="colon">:</td>
                <td>{{ $alumni['prodi'] }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td class="colon">:</td>
                <td>{{ $alumni['status'] }}</td> 
                </tr>
            <tr>
                <td class="label">Tanggal Lulus</td>
                <td class="colon">:</td>
                <td>{{ $alumni['tanggal_lulus'] }}</td>
            </tr>
            <tr>
                <td class="label">Nomor Ijazah</td>
                <td class="colon">:</td>
                <td>{{ $alumni['nomor_ijazah'] }}</td>
            </tr>
        </table>
        
        <p class="paragraph">
            Adalah benar alumni dari Universitas Kristen Duta Wacana dengan data yang tercantum di atas.
        </p>
        
        <p class="paragraph">
            Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
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

    <div class="footer">
        <p>Jl. Dr. Wahidin Sudirohusodo No. 5-25, Kota Baru, Gondokusuman, Kota Yogyakarta 55224</p>
        <p>Telp: 0274 563929 Ext. 111 dan 144 | Email: biro1@staff.ukdw.ac.id</p>
        <p>WA: +62 811-9252-1604 (Administrasi) | +62 898-7701-604 (Perkuliahan)</p>
    </div>

</body>
</html>