<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Cuti Akademik</title>
    <style>
        @page {
            size: A4;
            margin: 1.7cm 2cm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 11pt;
            line-height: 1.35;
            color: #000;
            margin: 0;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 2px double #000;
            padding-bottom: 8px;
            margin-bottom: 18px;
        }

        .logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .logo img {
            width: 65px;
        }

        .header-text {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
            padding-right: 65px;
        }

        .header-text h1,
        .header-text h2 {
            font-size: 12pt;
            margin: 0;
            text-transform: uppercase;
        }

        .title {
            text-align: center;
            margin-bottom: 18px;
        }

        .title h3 {
            font-size: 12pt;
            text-decoration: underline;
            margin: 0;
            text-transform: uppercase;
        }

        .title p {
            margin: 3px 0 0;
        }

        .content {
            text-align: justify;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 12px 18px;
        }

        .data-table td {
            vertical-align: top;
            padding: 2px 0;
        }

        .label {
            width: 170px;
        }

        .colon {
            width: 14px;
            text-align: center;
        }

        .signature-container {
            margin-top: 30px;
            display: table;
            width: 100%;
        }

        .signature-spacer {
            display: table-cell;
            width: 55%;
        }

        .signature-content {
            display: table-cell;
            width: 45%;
            text-align: left;
        }

        .signature-space {
            height: 95px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('logo-ukdw.png') }}" alt="Logo UKDW">
        </div>
        <div class="header-text">
            <h1>Universitas Kristen Duta Wacana</h1>
            <h2>Biro Administrasi Akademik</h2>
        </div>
    </div>

    <div class="title">
        <h3>Surat Cuti Akademik</h3>
        <p>Nomor: {{ $nomor_surat }}</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini menerangkan bahwa mahasiswa berikut:</p>

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
            <tr>
                <td class="label">Angkatan</td>
                <td class="colon">:</td>
                <td>{{ $student['angkatan'] }}</td>
            </tr>
        </table>

        <p>
            Berdasarkan permohonan mahasiswa, yang bersangkutan diberikan keterangan cuti akademik
            dengan rincian sebagai berikut:
        </p>

        <table class="data-table">
            <tr>
                <td class="label">Keterangan</td>
                <td class="colon">:</td>
                <td>{{ $leave['keterangan'] }}</td>
            </tr>
        </table>

        <p>
            Surat ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <div class="signature-container">
        <div class="signature-spacer"></div>
        <div class="signature-content">
            <p>Yogyakarta, {{ $tanggal_surat }}</p>
            <p>{{ $signatory['jabatan'] ?? 'Pejabat Berwenang' }}</p>
            <div class="signature-space">
                @if($digital_signature['type'] == 'qrcode')
                    <img src="data:image/png;base64,{{ $digital_signature['base64'] }}" alt="Digital Signature QR Code" style="width: 80px; height: 80px; margin: 5px auto 10px auto; display: block;">
                @elseif($digital_signature['type'] == 'png')
                    <img src="data:image/png;base64,{{ $digital_signature['base64'] }}" alt="Digital Signature" style="width: 120px; height: 60px; margin: 5px auto 10px auto; display: block;">
                @endif
            </div>
            <p><strong>{{ $signatory['nama'] }}</strong></p>
            <p>NIK/NIP: {{ $signatory['nik'] ?? '-' }}</p>
        </div>
    </div>
</body>
</html>
