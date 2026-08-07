<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Kinerja PPPK</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 30px;
            font-size: 12pt;
        }

        .header-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo {
            width: 80px;
        }

        .kop-title {
            text-align: center;
            font-weight: bold;
        }

        .kop-title h4 {
            margin: 0;
            font-size: 13pt;
        }

        .kop-title h3 {
            margin: 0;
            font-size: 14pt;
        }

        .kop-title p {
            margin: 0;
            font-size: 9pt;
            font-weight: normal;
        }

        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 3px 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            font-size: 11pt;
        }

        .data-table th {
            text-align: center;
            background-color: #f2f2f2;
        }

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
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()"
            style="padding: 8px 15px; font-size: 14px; cursor: pointer; background: #0d6efd; color: white; border: none; border-radius: 4px;">
            🖨️ Cetak / Save PDF
        </button>
        <button onclick="window.location.href='<?php echo e(route('reviews.index', ['periode' => $review->id])); ?>'"
            style="padding: 8px 15px; font-size: 14px; cursor: pointer;">
            ⬅️ Kembali
        </button>
    </div>

    <!-- Kop Surat Resmi -->
    <table class="header-table">
        <tr>
            <td style="width: 15%; text-align: center;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Coat_of_arms_of_West_Java.svg"
                    class="logo" alt="Logo Pemprov Jabar">
            </td>
            <td class="kop-title">
                <h4>PEMERINTAH PROVINSI JAWA BARAT</h4>
                <h3>BADAN KESATUAN BANGSA DAN POLITIK</h3>
                <p>Jalan Supratman No. 44 Tlp. (022) 7206174 - 7205759 Fax. (022) 7106286</p>
                <p>Website : bakesbangpol.jabarprov.go.id &nbsp; e-mail : bakesbangpol@jabarprov.go.id</p>
                <p>Bandung - 40121</p>
            </td>
        </tr>
    </table>

    <h3 style="text-align: center; margin-bottom: 20px; text-transform: uppercase;">LAPORAN KINERJA</h3>

    <!-- Meta Info Pegawai -->
    <table class="info-table">
        <tr>
            <td style="width: 15%;"><strong>Nama</strong></td>
            <td style="width: 2%;">:</td>
            <td style="width: 43%;"><?php echo e($review->nama); ?></td>
            <td style="width: 12%;"><strong>Periode</strong></td>
            <td style="width: 2%;">:</td>
            <td style="width: 26%;"><?php echo e($review->evaluation_period); ?></td>
        </tr>
        <tr>
            <td><strong>Jabatan</strong></td>
            <td>:</td>
            <td colspan="4"><?php echo e($review->jabatan); ?></td>
        </tr>
    </table>

    <!-- Tabel Kegiatan -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">NO</th>
                <th style="width: 25%;">HARI / TANGGAL</th>
                <th style="width: 20%;">WAKTU</th>
                <th>URAIAN KEGIATAN</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $review->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $hari = \Carbon\Carbon::parse($row->kegiatan_date)->format('l');
                    $daftar_hari = [
                        'Sunday' => 'Minggu',
                        'Monday' => 'Senin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis',
                        'Friday' => 'Jumat',
                        'Saturday' => 'Sabtu',
                    ];
                    $tgl_indo =
                        $daftar_hari[$hari] . ', ' . \Carbon\Carbon::parse($row->kegiatan_date)->format('d-m-Y');
                ?>
                <tr>
                    <td style="text-align: center;"><?php echo e($idx + 1); ?></td>
                    <td><?php echo e($tgl_indo); ?></td>
                    <td style="text-align: center;"><?php echo e($row->kegiatan_time); ?></td>
                    <td><?php echo e(nl2br(e($row->uraian))); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" style="text-align: center;">Belum ada kegiatan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p style="margin-bottom: 30px;"><strong>Catatan:</strong></p>

    <!-- Kolom Tanda Tangan -->
    <table class="ttd-table">
        <tr>
            <td>
                Pembuat Laporan<br><br><br><br><br>
                <strong><u><?php echo e($review->nama); ?></u></strong><br>
                NIPKKK. <?php echo e($review->nipkkk); ?>

            </td>
            <td>
                Mengetahui,<br>
                Pejabat Pelaksana Teknis Kegiatan<br><br><br><br>
                <strong><u><?php echo e($review->pptk_nama); ?></u></strong><br>
                NIP. <?php echo e($review->pptk_nip); ?>

            </td>
        </tr>
    </table>
</body>

</html>
<?php /**PATH C:\laragon\www\bakesbangpol\resources\views\reviews\print.blade.php ENDPATH**/ ?>