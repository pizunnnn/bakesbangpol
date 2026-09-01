<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rincian Data Aset Bakesbangpol</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .header-title {
            text-align: center;
        }
        .header-title h2 {
            margin: 0;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-title h3 {
            margin: 2px 0;
            font-size: 11px;
            font-weight: bold;
        }
        .header-title p {
            margin: 0;
            font-size: 8.5px;
            color: #475569;
        }
        .doc-title {
            text-align: center;
            margin-bottom: 10px;
        }
        .doc-title h4 {
            margin: 0;
            font-size: 11px;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .doc-title p {
            margin: 2px 0 0 0;
            font-size: 8.5px;
            color: #64748b;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .content-table th, .content-table td {
            border: 1px solid #94a3b8;
            padding: 4px 5px;
            text-align: left;
        }
        .content-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 8.5px;
            color: #0f172a;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .footer-table {
            width: 100%;
            margin-top: 15px;
        }
        .signature-box {
            width: 220px;
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
                <p>Jl. Supratman No. 44, Cihapit, Kec. Bandung Wetan, Kota Bandung, Jawa Barat 40114</p>
            </td>
        </tr>
    </table>

    <div class="doc-title">
        <h4>Laporan Rincian Data Aset</h4>
        <p>
            @if($filterCategory) Kategori: <strong>{{ $filterCategory }}</strong> | @endif
            @if($filterBidang) Unit Kerja: <strong>{{ $filterBidang }}</strong> | @endif
            @if($filterStatus) Status: <strong>{{ $filterStatus }}</strong> | @endif
            @if($search) Pencarian: "<strong>{{ $search }}</strong>" | @endif
            Total Data: <strong>{{ count($assets) }} Baris</strong> | Tanggal Cetak: {{ date('d/m/Y H:i') }} WIB
        </p>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 85px;">Kode Aset</th>
                <th style="width: 85px;">Kode Barang</th>
                <th>Nama Barang / Aset</th>
                <th style="width: 100px;">Kategori</th>
                <th style="width: 110px;">Merk / Tipe</th>
                <th style="width: 85px;">Unit Kerja</th>
                <th style="width: 45px;">Tahun</th>
                <th style="width: 90px;">Nilai Perolehan</th>
                <th style="width: 65px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $idx => $asset)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center font-monospace"><strong>{{ $asset->asset_code }}</strong></td>
                    <td class="text-center">{{ $asset->kode_barang ?: '-' }}</td>
                    <td><strong>{{ $asset->nama_barang }}</strong></td>
                    <td>{{ $asset->categoryRelation->name ?? $asset->category ?? '-' }}</td>
                    <td>{{ $asset->merk_tipe ?: ($asset->brand . ' ' . $asset->model) ?: '-' }}</td>
                    <td>{{ $asset->bidang ?: '-' }}</td>
                    <td class="text-center">{{ $asset->tahun_perolehan ?: ($asset->purchase_date ? $asset->purchase_date->format('Y') : '-') }}</td>
                    <td class="text-right fw-bold">Rp {{ number_format((float)($asset->nilai_perolehan ?: $asset->purchase_price), 0, ',', '.') }}</td>
                    <td class="text-center">{{ $asset->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 15px;">Tidak ada data aset yang memenuhi kriteria filter.</td>
                </tr>
            @endforelse
            @if(count($assets) > 0)
                <tr style="background-color: #f8fafc;">
                    <td colspan="8" class="text-right fw-bold">TOTAL NILAI PEROLEHAN</td>
                    <td class="text-right fw-bold" style="color: #0369a1;">Rp {{ number_format($totalValue, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td></td>
            <td class="signature-box">
                <p>Bandung, {{ date('d F Y') }}</p>
                <p style="margin-bottom: 45px;">Pengelola Barang Milik Daerah</p>
                <p><strong>________________________</strong></p>
                <p>NIP. .....................................</p>
            </td>
        </tr>
    </table>

</body>
</html>
