<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Nominatif Kenaikan Pangkat Pegawai - Bakesbangpol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 15px;
        }
        .header-title {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }
        .header-title h4 {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .header-title h5 {
            font-size: 12px;
            font-weight: bold;
            margin: 3px 0;
            text-transform: uppercase;
        }
        .header-title p {
            font-size: 10.5px;
            margin: 0;
            font-weight: bold;
        }
        .nominative-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .nominative-table th, .nominative-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: middle;
            color: #000;
        }
        .nominative-table th {
            background-color: #f3f4f6;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        .signature-box {
            width: 240px;
            float: right;
            text-align: center;
            font-size: 11px;
            margin-top: 30px;
        }
        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm 8mm 10mm 8mm;
            }
            body { padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="container-fluid mb-3 no-print">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('employees.promotions') }}" class="btn btn-sm btn-outline-secondary">
                &larr; Kembali ke Monitoring Kenaikan Pangkat
            </a>
            <button onclick="window.print()" class="btn btn-sm btn-primary">
                Cetak Daftar Nominatif (A4 Landscape)
            </button>
        </div>
    </div>

    <!-- KOP RESMI -->
    <div class="header-title">
        <h4>Pemerintah Provinsi Jawa Barat</h4>
        <h5>Badan Kesatuan Bangsa dan Politik</h5>
        <p>DAFTAR NOMINATIF PEGAWAI USULAN KENAIKAN PANGKAT | PERIODE: {{ $periodeText }}</p>
    </div>

    <table class="nominative-table">
        <thead>
            <tr>
                <th style="width: 30px;">NO</th>
                <th style="width: 180px;">NAMA & NIP PEGAWAI</th>
                <th style="width: 160px;">JABATAN & UNIT KERJA</th>
                <th style="width: 110px;">PANGKAT / GOL SAAT INI</th>
                <th style="width: 140px;">USULAN PANGKAT BARU</th>
                <th style="width: 90px;">TMT PANGKAT TERAKHIR</th>
                <th style="width: 90px;">TMT JATUH TEMPO KP</th>
                <th style="width: 90px;">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($candidates as $idx => $emp)
                <tr>
                    <td class="text-center fw-bold">{{ $idx + 1 }}</td>
                    <td>
                        <div class="fw-bold">{{ $emp->full_name }}</div>
                        <div>NIP. {{ $emp->employee_number }}</div>
                    </td>
                    <td>
                        <div>{{ $emp->position->name ?? '-' }}</div>
                        <div class="text-muted">{{ $emp->unit_kerja ?: '-' }}</div>
                    </td>
                    <td class="text-center">{{ $emp->pangkat_golongan ?: '-' }}</td>
                    <td class="text-center fw-bold">{{ $emp->pangkat_berikutnya_estimasi }}</td>
                    <td class="text-center">{{ $emp->pangkat_terakhir_tmt ? $emp->pangkat_terakhir_tmt->format('d/m/Y') : '-' }}</td>
                    <td class="text-center fw-bold">{{ $emp->tanggal_kenaikan_pangkat_berikutnya ? $emp->tanggal_kenaikan_pangkat_berikutnya->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">Memenuhi Syarat (4 Tahun)</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4">Tidak ada data pegawai yang memenuhi syarat kenaikan pangkat pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-box">
        <p class="mb-1">Bandung, {{ date('d F Y') }}</p>
        <p class="mb-5">Pengelola Kepegawaian,</p>
        <p class="fw-bold mb-0">________________________</p>
        <p class="text-muted small">NIP. .....................................</p>
    </div>
    <div style="clear: both;"></div>

</body>
</html>