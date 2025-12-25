<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statement Letter</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm 2cm;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt; /* Diperkecil agar muat 1 lembar */
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* HEADER SECTION */
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 3px double black;
            padding-bottom: 10px;
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
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .header-text h2 {
            font-size: 13pt;
            font-weight: bold;
            margin: 2px 0 0 0;
            text-transform: uppercase;
        }

        /* TITLE SECTION */
        .title {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 25px;
        }

        .title h3 {
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }

        .title p {
            margin: 5px 0 0 0;
            font-size: 11pt;
        }

        /* CONTENT SECTION */
        .content {
            text-align: justify;
        }

        .paragraph {
            margin-bottom: 10px;
        }

        /* TABLES (Borderless sesuai PDF) */
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
            width: 180px;
            font-weight: normal;
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
            width: 45%;
        }

        .signature-content {
            display: table-cell;
            width: 55%;
            text-align: left;
        }

        .signature-space {
            height: 100px;
        }

        /* FOOTER SECTION */
        .footer {
            margin-top: 40px;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-size: 9pt;
            text-align: left; /* Alamat di PDF rata kiri */
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
        <h3>STATEMENT LETTER</h3>
        <p>Number: {{ $document_number }}</p>
    </div>

    <div class="content">
        <p class="paragraph">This is to certify that:</p>
        
        <table class="data-table">
            <tr>
                <td class="label">Full Name</td>
                <td class="colon">:</td>
                <td><strong>{{ $student['full_name'] }}</strong></td>
            </tr>
            <tr>
                <td class="label">Date of Birth</td>
                <td class="colon">:</td>
                <td>{{ $student['date_of_birth'] }}</td>
            </tr>
            <tr>
                <td class="label">Student ID</td>
                <td class="colon">:</td>
                <td>{{ $student['student_id'] }}</td>
            </tr>
            <tr>
                <td class="label">Institution</td>
                <td class="colon">:</td>
                <td>{{ $student['institution'] }}</td>
            </tr>
            <tr>
                <td class="label">Faculty</td>
                <td class="colon">:</td>
                <td>{{ $student['faculty'] }}</td>
            </tr>
            <tr>
                <td class="label">Department</td>
                <td class="colon">:</td>
                <td>{{ $student['department'] }}</td>
            </tr>
            <tr>
                <td class="label">Period of Study</td>
                <td class="colon">:</td>
                <td>{{ $student['study_period'] }}</td>
            </tr>
            <tr>
                <td class="label">Degree Awarded</td>
                <td class="colon">:</td>
                <td>{{ $student['degree_awarded'] }}</td>
            </tr>
        </table>

        <p class="paragraph">
            has successfully completed all academic and administrative requirements at Universitas Kristen Duta Wacana and has been officially awarded the degree of {{ $student['degree_awarded'] }} by the Faculty of {{ $student['faculty'] }}.
        </p>

        <p class="paragraph">
            The degree was conferred in {{ $student['graduation_date'] }} and {{ $student['full_name'] }} is hereby recognized as a graduate of Universitas Kristen Duta Wacana.
        </p>

        <p class="paragraph">
            This statement is issued to be used as necessary.
        </p>
    </div>

    <div class="signature-container">
        <div class="signature-box"></div>
        <div class="signature-content">
            <p>Yogyakarta, {{ $issue_date }}</p>
            <p>Head of Academic Administration Bureau</p>
            <p>Universitas Kristen Duta Wacana,</p>
            <div class="signature-space">
                @if(isset($digital_signature) && $digital_signature['type'] == 'qrcode')
                    <img src="data:image/png;base64,{{ $digital_signature['base64'] }}" alt="Digital Signature QR Code" style="width: 80px; height: 80px; margin: 5px auto 15px auto; display: block;">
                @elseif(isset($digital_signature) && $digital_signature['type'] == 'png')
                    <img src="data:image/png;base64,{{ $digital_signature['base64'] }}" alt="Digital Signature" style="width: 120px; height: 60px; margin: 5px auto 15px auto; display: block;">
                @endif
            </div>
            <p><strong>{{ $signatory['name'] }}</strong></p>
        </div>
    </div>

    <div class="footer">
        <p>Alamat : Jl. Dr. Wahidin Sudirohusodo No. 5-25, Kota Baru, Gondokusuman, Kota Yogyakarta 55224</p>
        <p>Telp: 0274 563929 Ext. 111 dan 144 | Email: biro1@staff.ukdw.ac.id</p>
        <p>WA: +62 813-9252-1604 (Administrasi) | +62 898-7701-604 (Perkuliahan)</p>
    </div>

</body>
</html>