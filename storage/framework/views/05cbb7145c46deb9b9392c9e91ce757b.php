<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Laporan Rekapitulasi Aset - Bakesbangpol</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 20px 40px;
            font-size: 11pt;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .preview-container {
            max-width: 960px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
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
            margin-bottom: 20px;
        }

        .doc-title h4 {
            margin: 0;
            font-size: 13pt;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .doc-title p {
            margin: 3px 0 0 0;
            font-size: 9.5pt;
            color: #64748b;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 20px 0 8px 0;
            color: #0f172a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #64748b;
            padding: 8px 10px;
            text-align: left;
            font-size: 10.5pt;
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
            margin-top: 40px;
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

    
    <div class="no-print" style="margin: -20px -40px 25px -40px; background: #ffffff; padding: 12px 30px; border-bottom: 2px solid #e2e8f0; sticky: top; z-index: 100; box-shadow: 0 2px 10px rgba(0,0,0,0.06); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 16px; font-weight: bold; color: #0d6efd;">👁️ Preview Laporan Rekapitulasi Aset</span>
            <span style="font-size: 12px; color: #64748b;">(Tinjau seluruh data di layar sebelum mencetak / men-download)</span>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <button onclick="window.print()" style="padding: 8px 16px; font-size: 13px; font-weight: bold; cursor: pointer; background: #0d6efd; color: white; border: none; border-radius: 6px;">
                🖨️ Cetak / Save PDF
            </button>
            <a href="<?php echo e(route('assets.export-pdf', array_merge(request()->query(), ['mode' => 'rekap', 'download' => '1']))); ?>" style="padding: 8px 16px; font-size: 13px; font-weight: bold; text-decoration: none; background: #dc2626; color: white; border-radius: 6px; display: inline-block;">
                📄 Download PDF
            </a>
            <a href="<?php echo e(route('assets.export-excel', request()->query())); ?>" style="padding: 8px 16px; font-size: 13px; font-weight: bold; text-decoration: none; background: #16a34a; color: white; border-radius: 6px; display: inline-block;">
                🟢 Download Excel
            </a>
            <a href="<?php echo e(route('assets.index')); ?>" style="padding: 8px 16px; font-size: 13px; text-decoration: none; background: #64748b; color: white; border-radius: 6px; display: inline-block;">
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
            <h4>LAPORAN REKAPITULASI ASET (EXECUTIVE SUMMARY)</h4>
            <p>Ringkasan Laporan Barang Milik Daerah (BMD) | Tanggal Cetak: <?php echo e(date('d/m/Y H:i')); ?> WIB</p>
        </div>

        
        <div class="section-title">I. Ringkasan Total Aset</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Total Item Aset</th>
                    <th>Total Jumlah Unit</th>
                    <th>Total Nilai Perolehan Aset</th>
                    <th>Aset Umur >= 10 Thn</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center fw-bold"><?php echo e(number_format($totalCount, 0, ',', '.')); ?> Item</td>
                    <td class="text-center fw-bold"><?php echo e(number_format($totalUnits, 0, ',', '.')); ?> Unit</td>
                    <td class="text-center fw-bold" style="color: #0369a1; font-size: 12pt;">Rp <?php echo e(number_format($totalValue, 0, ',', '.')); ?></td>
                    <td class="text-center fw-bold" style="color: #dc2626;"><?php echo e(number_format($agedAssetsCount, 0, ',', '.')); ?> Item</td>
                </tr>
            </tbody>
        </table>

        
        <div class="section-title">II. Rekapitulasi Berdasarkan Kategori Master Aset</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 35px;">NO</th>
                    <th>KATEGORI ASET</th>
                    <th style="width: 110px;">JUMLAH ITEM</th>
                    <th style="width: 110px;">TOTAL UNIT</th>
                    <th style="width: 180px;">TOTAL NILAI PEROLEHAN</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $byCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($idx + 1); ?></td>
                        <td><strong><?php echo e($cat['name']); ?></strong></td>
                        <td class="text-center"><?php echo e(number_format($cat['count'], 0, ',', '.')); ?></td>
                        <td class="text-center"><?php echo e(number_format($cat['units'], 0, ',', '.')); ?></td>
                        <td class="text-right fw-bold">Rp <?php echo e(number_format($cat['value'], 0, ',', '.')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr style="background-color: #f8fafc;">
                    <td colspan="2" class="text-right fw-bold">TOTAL</td>
                    <td class="text-center fw-bold"><?php echo e(number_format($totalCount, 0, ',', '.')); ?></td>
                    <td class="text-center fw-bold"><?php echo e(number_format($totalUnits, 0, ',', '.')); ?></td>
                    <td class="text-right fw-bold">Rp <?php echo e(number_format($totalValue, 0, ',', '.')); ?></td>
                </tr>
            </tbody>
        </table>

        
        <div class="section-title">III. Rekapitulasi Berdasarkan Unit Kerja / Bidang</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 35px;">NO</th>
                    <th>UNIT KERJA / BIDANG</th>
                    <th style="width: 130px;">JUMLAH ITEM ASET</th>
                    <th style="width: 200px;">TOTAL NILAI PEROLEHAN</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $byBidang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($idx + 1); ?></td>
                        <td><strong><?php echo e($b['name']); ?></strong></td>
                        <td class="text-center"><?php echo e(number_format($b['count'], 0, ',', '.')); ?></td>
                        <td class="text-right fw-bold">Rp <?php echo e(number_format($b['value'], 0, ',', '.')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        
        <div class="section-title">IV. Rekapitulasi Status & Kondisi Aset</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 35px;">NO</th>
                    <th>STATUS / KONDISI ASET</th>
                    <th style="width: 130px;">JUMLAH ITEM</th>
                    <th style="width: 200px;">TOTAL NILAI PEROLEHAN</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $byStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($idx + 1); ?></td>
                        <td><strong><?php echo e($st['name']); ?></strong></td>
                        <td class="text-center"><?php echo e(number_format($st['count'], 0, ',', '.')); ?></td>
                        <td class="text-right fw-bold">Rp <?php echo e(number_format($st['value'], 0, ',', '.')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- Kolom Tanda Tangan -->
        <table class="ttd-table">
            <tr>
                <td></td>
                <td>
                    Bandung, <?php echo e(date('d F Y')); ?><br>
                    Pengelola Barang Milik Daerah<br><br><br><br><br>
                    <strong><u>________________________</u></strong><br>
                    NIP. .....................................
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
<?php /**PATH D:\BakesBangPol\KesbangPol\resources\views/assets/print_preview_summary.blade.php ENDPATH**/ ?>