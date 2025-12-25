<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Statistik Akademik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .stats-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .stats-number {
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
        }
        .stats-label {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN STATISTIK AKADEMIK</h1>
        <p>Tanggal Export: {{ $tanggal_export }}</p>
    </div>

    <!-- Ringkasan Umum -->
    <div class="section">
        <div class="section-title">RINGKASAN UMUM</div>
        <div class="stats-grid">
            <div class="stats-item">
                <div class="stats-number">{{ $total_pengajuan }}</div>
                <div class="stats-label">Total Pengajuan</div>
            </div>
            <div class="stats-item">
                <div class="stats-number">{{ $total_mahasiswa }}</div>
                <div class="stats-label">Total Mahasiswa</div>
            </div>
            <div class="stats-item">
                <div class="stats-number">{{ $total_prodi }}</div>
                <div class="stats-label">Program Studi</div>
            </div>
        </div>
    </div>

    <!-- Pengajuan per Bulan -->
    <div class="section">
        <div class="section-title">PENGAJUAN PER BULAN ({{ date('Y') }})</div>
        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="text-center">Jumlah Pengajuan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan_per_bulan as $item)
                <tr>
                    <td>{{ $item->nama_bulan }}</td>
                    <td class="text-center">{{ $item->total }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pengajuan per Status -->
    <div class="section">
        <div class="section-title">PENGAJUAN PER STATUS</div>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th class="text-center">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan_per_status as $item)
                <tr>
                    <td>{{ $item->status_saat_ini }}</td>
                    <td class="text-center">{{ $item->total }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mahasiswa per Prodi -->
    <div class="section">
        <div class="section-title">MAHASISWA PER PROGRAM STUDI</div>
        <table>
            <thead>
                <tr>
                    <th>Program Studi</th>
                    <th class="text-center">Jumlah Mahasiswa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswa_per_prodi as $item)
                <tr>
                    <td>{{ $item->nama_prodi }}</td>
                    <td class="text-center">{{ $item->mahasiswa_count }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
