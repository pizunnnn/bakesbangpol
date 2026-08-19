<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pegawai Bakesbangpol</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header-title {
            text-align: center;
        }
        .header-title h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header-title h3 {
            margin: 3px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .header-title p {
            margin: 0;
            font-size: 10px;
            color: #555;
        }
        .doc-title {
            text-align: center;
            margin-bottom: 15px;
        }
        .doc-title h4 {
            margin: 0;
            font-size: 13px;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .doc-title p {
            margin: 3px 0 0 0;
            font-size: 10px;
            color: #666;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .content-table th, .content-table td {
            border: 1px solid #777;
            padding: 6px 8px;
            text-align: left;
        }
        .content-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 10px;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 3px;
            background-color: #e2e8f0;
            color: #1e293b;
        }
        .footer-table {
            width: 100%;
            margin-top: 30px;
        }
        .signature-box {
            width: 250px;
            float: right;
            text-align: center;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-title">
                <h2>Pemerintah Provinsi Jawa Barat</h2>
                <h3>Badan Kesatuan Bangsa dan Politik</h3>
                <p>Jl. Supratman No.44, Cihapit, Kec. Bandung Wetan, Kota Bandung, Jawa Barat 40114</p>
            </td>
        </tr>
    </table>

    <div class="doc-title">
        <h4>Laporan Data Pegawai</h4>
        <p>
            @if($statusPegawai) Filter Status: <strong>{{ $statusPegawai }}</strong> | @endif
            @if($filterUnit) Unit Kerja: <strong>{{ $filterUnit }}</strong> | @endif
            @if($filterJabatan) Jabatan: <strong>{{ $filterJabatan }}</strong> | @endif
            Tanggal Cetak: {{ date('d/m/Y H:i') }}
        </p>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Nama Pegawai</th>
                <th>NIP / NIK</th>
                <th>Status</th>
                <th>Pangkat / Gol.</th>
                <th>Jabatan</th>
                <th>Unit Kerja</th>
                <th>TMT Bergabung</th>
                <th>Masa Kerja</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $index => $emp)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $emp->full_name }}</strong></td>
                    <td>{{ $emp->employee_number ?: '-' }}</td>
                    <td class="text-center">{{ $emp->status_pegawai }}</td>
                    <td class="text-center">{{ $emp->pangkat_golongan ?: '-' }}</td>
                    <td>{{ $emp->position->name ?? '-' }}</td>
                    <td>{{ $emp->unit_kerja ?: $emp->department->name ?? '-' }}</td>
                    <td class="text-center">{{ $emp->join_date ? $emp->join_date->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $emp->masa_kerja }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px;">Tidak ada data pegawai yang sesuai dengan kriteria filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td></td>
            <td class="signature-box">
                <p>Bandung, {{ date('d F Y') }}</p>
                <p style="margin-bottom: 60px;">Kepala Sub Bagian Kepegawaian</p>
                <p><strong>________________________</strong></p>
                <p>NIP. .....................................</p>
            </td>
        </tr>
    </table>

</body>
</html>
