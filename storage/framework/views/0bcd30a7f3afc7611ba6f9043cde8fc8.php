<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Aset Bakesbangpol</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header-title {
            text-align: center;
        }
        .header-title h2 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-title h3 {
            margin: 2px 0;
            font-size: 12px;
            font-weight: bold;
        }
        .header-title p {
            margin: 0;
            font-size: 9px;
            color: #475569;
        }
        .doc-title {
            text-align: center;
            margin-bottom: 12px;
        }
        .doc-title h4 {
            margin: 0;
            font-size: 12px;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .doc-title p {
            margin: 2px 0 0 0;
            font-size: 9px;
            color: #64748b;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            margin: 12px 0 6px 0;
            color: #0f172a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .content-table th, .content-table td {
            border: 1px solid #94a3b8;
            padding: 5px 6px;
            text-align: left;
        }
        .content-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 9px;
            color: #0f172a;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .footer-table {
            width: 100%;
            margin-top: 20px;
        }
        .signature-box {
            width: 230px;
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
        <h4>Laporan Rekapitulasi Aset (Executive Summary)</h4>
        <p>Ringkasan Laporan Barang Milik Daerah (BMD) | Tanggal Cetak: <?php echo e(date('d/m/Y H:i')); ?> WIB</p>
    </div>

    
    <div class="section-title">I. Ringkasan Total Aset</div>
    <table class="content-table">
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
                <td class="text-center fw-bold" style="color: #0369a1;">Rp <?php echo e(number_format($totalValue, 0, ',', '.')); ?></td>
                <td class="text-center fw-bold" style="color: #dc2626;"><?php echo e(number_format($agedAssetsCount, 0, ',', '.')); ?> Item</td>
            </tr>
        </tbody>
    </table>

    
    <div class="section-title">II. Rekapitulasi Berdasarkan Kategori Master Aset</div>
    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Kategori Aset</th>
                <th style="width: 90px;">Jumlah Item</th>
                <th style="width: 90px;">Total Unit</th>
                <th style="width: 150px;">Total Nilai Perolehan (Rp)</th>
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
    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Unit Kerja / Bidang</th>
                <th style="width: 100px;">Jumlah Item Aset</th>
                <th style="width: 160px;">Total Nilai Perolehan (Rp)</th>
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
    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Status / Kondisi Aset</th>
                <th style="width: 100px;">Jumlah Item</th>
                <th style="width: 160px;">Total Nilai Perolehan (Rp)</th>
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

    <table class="footer-table">
        <tr>
            <td></td>
            <td class="signature-box">
                <p>Bandung, <?php echo e(date('d F Y')); ?></p>
                <p style="margin-bottom: 50px;">Pengelola Barang Milik Daerah</p>
                <p><strong>________________________</strong></p>
                <p>NIP. .....................................</p>
            </td>
        </tr>
    </table>

</body>
</html>
<?php /**PATH C:\laragon\www\bakesbangpol\resources\views/assets/pdf_summary.blade.php ENDPATH**/ ?>