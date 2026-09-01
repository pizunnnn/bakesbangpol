<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Laporan Rincian Aset - Bakesbangpol</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 20px 40px;
            font-size: 10.5pt;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .preview-container {
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
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

        .header-title h4 {
            margin: 0;
            font-size: 13pt;
            text-transform: uppercase;
        }

        .header-title h3 {
            margin: 2px 0;
            font-size: 14pt;
            font-weight: bold;
        }

        .header-title p {
            margin: 0;
            font-size: 9pt;
            font-weight: normal;
            color: #475569;
        }

        .doc-title {
            text-align: center;
            margin-bottom: 15px;
        }

        .doc-title h4 {
            margin: 0;
            font-size: 12pt;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .doc-title p {
            margin: 3px 0 0 0;
            font-size: 9pt;
            color: #64748b;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #64748b;
            padding: 6px 8px;
            text-align: left;
            font-size: 9.5pt;
        }

        .data-table th {
            text-align: center;
            background-color: #f1f5f9;
            font-weight: bold;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }

        .ttd-table {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .ttd-table td {
            text-align: center;
            vertical-align: top;
            width: 50%;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .preview-container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    {{-- Top Action Toolbar --}}
    <div class="no-print" style="margin: -20px -40px 25px -40px; background: #ffffff; padding: 12px 30px; border-bottom: 2px solid #e2e8f0; sticky: top; z-index: 100; box-shadow: 0 2px 10px rgba(0,0,0,0.06); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 16px; font-weight: bold; color: #0d6efd;">👁️ Preview Laporan Rincian Aset</span>
            <span style="font-size: 12px; color: #64748b;">(Tinjau seluruh data di layar sebelum mencetak / men-download)</span>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <button onclick="window.print()" style="padding: 8px 16px; font-size: 13px; font-weight: bold; cursor: pointer; background: #0d6efd; color: white; border: none; border-radius: 6px;">
                🖨️ Cetak / Save PDF
            </button>
            <a href="{{ route('assets.export-pdf', array_merge(request()->query(), ['mode' => 'detail', 'download' => '1'])) }}" style="padding: 8px 16px; font-size: 13px; font-weight: bold; text-decoration: none; background: #dc2626; color: white; border-radius: 6px; display: inline-block;">
                📄 Download PDF
            </a>
            <a href="{{ route('assets.export-excel', request()->query()) }}" style="padding: 8px 16px; font-size: 13px; font-weight: bold; text-decoration: none; background: #16a34a; color: white; border-radius: 6px; display: inline-block;">
                🟢 Download Excel
            </a>
            <a href="{{ route('assets.index') }}" style="padding: 8px 16px; font-size: 13px; text-decoration: none; background: #64748b; color: white; border-radius: 6px; display: inline-block;">
                ⬅️ Kembali
            </a>
        </div>
    </div>

    <div class="preview-container">
        <!-- Kop Surat Resmi -->
        <table class="header-table">
            <tr>
                <td class="header-title">
                    <h4>PEMERINTAH PROVINSI JAWA BARAT</h4>
                    <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
                    <p>Jl. Supratman No. 44, Cihapit, Kec. Bandung Wetan, Kota Bandung, Jawa Barat 40114</p>
                    <p>Website: bakesbangpol.jabarprov.go.id | Email: bakesbangpol@jabarprov.go.id</p>
                </td>
            </tr>
        </table>

        <div class="doc-title">
            <h4>LAPORAN RINCIAN DATA ASET</h4>
            <p>
                @if($filterCategory) Kategori: <strong>{{ $filterCategory }}</strong> | @endif
                @if($filterBidang) Unit Kerja: <strong>{{ $filterBidang }}</strong> | @endif
                @if($filterStatus) Status: <strong>{{ $filterStatus }}</strong> | @endif
                @if($search) Pencarian: "<strong>{{ $search }}</strong>" | @endif
                Total Data: <strong>{{ count($assets) }} Baris</strong> | Tanggal Cetak: {{ date('d/m/Y H:i') }} WIB
            </p>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 30px;">NO</th>
                    <th style="width: 100px;">KODE ASET</th>
                    <th style="width: 100px;">KODE BARANG</th>
                    <th>NAMA BARANG / ASET</th>
                    <th style="width: 110px;">KATEGORI</th>
                    <th style="width: 120px;">MERK / TIPE</th>
                    <th style="width: 100px;">UNIT KERJA</th>
                    <th style="width: 50px;">TAHUN</th>
                    <th style="width: 110px;">NILAI PEROLEHAN</th>
                    <th style="width: 75px;">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets as $idx => $asset)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="text-center"><strong>{{ $asset->asset_code }}</strong></td>
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
                        <td colspan="10" class="text-center" style="padding: 20px;">Tidak ada data aset yang memenuhi kriteria filter.</td>
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

        <!-- Kolom Tanda Tangan -->
        <table class="ttd-table">
            <tr>
                <td></td>
                <td>
                    Bandung, {{ date('d F Y') }}<br>
                    Pengelola Barang Milik Daerah<br><br><br><br><br>
                    <strong><u>________________________</u></strong><br>
                    NIP. .....................................
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
