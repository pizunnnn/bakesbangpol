<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pegawai Penerima Tunjangan - Bakesbangpol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #0f172a;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
        }
        .main-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07);
        }
        .header-title {
            text-align: center;
            border-bottom: 2px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header-title h4 {
            font-size: 15px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-title h5 {
            font-size: 13px;
            font-weight: bold;
            margin: 3px 0;
            text-transform: uppercase;
        }
        .header-title p {
            font-size: 11px;
            margin: 0;
            color: #475569;
        }
        .payroll-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        .payroll-table th, .payroll-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .payroll-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            color: #0f172a;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-start { text-align: left; }
        .fw-bold { font-weight: bold; }
        .stat-badge-card {
            border-radius: 10px;
            padding: 12px 16px;
            background: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        /* ATURAN KHUSUS CETAK (PRINT PREVIEW / PRINTER) */
        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm 8mm 10mm 8mm;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color: #000 !important;
                box-shadow: none !important;
                text-shadow: none !important;
            }
            body {
                padding: 0 !important;
                background: #fff !important;
                color: #000 !important;
                font-size: 8.5pt !important;
            }
            /* SEMBUNYIKAN NAVIGASI, FILTER, DAN KOLOM DETAIL SAAT CETAK */
            .no-print,
            .no-print-col,
            th.no-print-col,
            td.no-print-col {
                display: none !important;
                visibility: hidden !important;
                width: 0 !important;
                padding: 0 !important;
                border: none !important;
            }
            .main-card {
                box-shadow: none !important;
                padding: 0 !important;
                background: transparent !important;
                border: none !important;
            }
            .header-title {
                border-bottom: 2px solid #000 !important;
                margin-bottom: 12px !important;
                padding-bottom: 6px !important;
            }
            .header-title h4, .header-title h5, .header-title p {
                color: #000 !important;
            }
            /* TABEL HITAM PUTIH RESMI */
            .payroll-table {
                width: 100% !important;
                border: 1px solid #000 !important;
                border-collapse: collapse !important;
                color: #000 !important;
                font-size: 8pt !important;
            }
            .payroll-table th, .payroll-table td {
                border: 1px solid #000 !important;
                color: #000 !important;
                background: transparent !important;
                padding: 4px 5px !important;
            }
            .payroll-table th {
                background-color: #f3f4f6 !important;
                font-weight: bold !important;
                color: #000 !important;
            }
            /* HILANGKAN SEMUA WARNA & KOTAK BADGE */
            .badge,
            span.badge {
                background: none !important;
                background-color: transparent !important;
                color: #000 !important;
                border: none !important;
                padding: 0 !important;
                font-size: 8pt !important;
                font-weight: normal !important;
                box-shadow: none !important;
            }
            .badge i {
                display: none !important;
            }
            .text-muted, .text-primary, .text-success, .text-danger, .text-warning, .text-info, .text-dark, .text-secondary, .text-info-emphasis, .text-warning-emphasis {
                color: #000 !important;
            }
        }
    </style>
</head>
<body>

    <div class="container-fluid max-w-7xl mx-auto">
        <!-- TOP ACTIONS (NO PRINT) -->
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Data Pegawai
            </a>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-sm btn-primary">
                    <i class="bi bi-printer me-1"></i>Cetak Daftar Penerima Tunjangan
                </button>
            </div>
        </div>

        <div class="main-card">
            <!-- TITLE HEADER -->
            <div class="header-title">
                <h4>Pemerintah Provinsi Jawa Barat</h4>
                <h5>Badan Kesatuan Bangsa dan Politik</h5>
                <p>DAFTAR PEGAWAI PENERIMA HAK TUNJANGAN KEPEGAWAIAN | BULAN : <?php echo e(strtoupper($periode)); ?></p>
            </div>

            <!-- STATISTIK JUMLAH PENERIMA TUNJANGAN (NO PRINT) -->
            <div class="row g-2 mb-4 no-print">
                <div class="col-md-2 col-6">
                    <div class="stat-badge-card border-start border-4 border-primary">
                        <div class="small text-muted">Total Pegawai</div>
                        <div class="fs-5 fw-bold text-dark"><?php echo e(count($employees)); ?> Orang</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-badge-card border-start border-4 border-success">
                        <div class="small text-muted">Tj. Pasangan</div>
                        <div class="fs-5 fw-bold text-success"><?php echo e($countSuamiIstri); ?> Orang</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-badge-card border-start border-4 border-info">
                        <div class="small text-muted">Tj. Anak</div>
                        <div class="fs-5 fw-bold text-info"><?php echo e($countAnak); ?> Orang</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-badge-card border-start border-4 border-warning">
                        <div class="small text-muted">Tj. Struktural</div>
                        <div class="fs-5 fw-bold text-warning-emphasis"><?php echo e($countStruktural); ?> Pejabat</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-badge-card border-start border-4 border-secondary">
                        <div class="small text-muted">Tj. Fungsional</div>
                        <div class="fs-5 fw-bold text-secondary"><?php echo e($countFungsional); ?> Orang</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-badge-card border-start border-4 border-success">
                        <div class="small text-muted">Tj. Beras</div>
                        <div class="fs-5 fw-bold text-success"><?php echo e($countBeras); ?> Orang</div>
                    </div>
                </div>
            </div>

            <!-- FILTER TABS & PENCARIAN (NO PRINT) -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 no-print">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="<?php echo e(route('employees.payroll-report')); ?>" class="btn <?php echo e(empty($filterType) ? 'btn-primary' : 'btn-outline-primary'); ?>">
                        Semua Pegawai (<?php echo e(count($employees)); ?>)
                    </a>
                    <a href="<?php echo e(route('employees.payroll-report', ['filter' => 'suami_istri'])); ?>" class="btn <?php echo e($filterType === 'suami_istri' ? 'btn-success text-white' : 'btn-outline-success'); ?>">
                        Penerima Tj. Pasangan
                    </a>
                    <a href="<?php echo e(route('employees.payroll-report', ['filter' => 'anak'])); ?>" class="btn <?php echo e($filterType === 'anak' ? 'btn-info text-dark' : 'btn-outline-info'); ?>">
                        Penerima Tj. Anak
                    </a>
                    <a href="<?php echo e(route('employees.payroll-report', ['filter' => 'struktural'])); ?>" class="btn <?php echo e($filterType === 'struktural' ? 'btn-warning text-dark' : 'btn-outline-warning'); ?>">
                        Penerima Tj. Struktural
                    </a>
                    <a href="<?php echo e(route('employees.payroll-report', ['filter' => 'fungsional'])); ?>" class="btn <?php echo e($filterType === 'fungsional' ? 'btn-secondary' : 'btn-outline-secondary'); ?>">
                        Penerima Tj. Fungsional
                    </a>
                </div>

                <form action="<?php echo e(route('employees.payroll-report')); ?>" method="GET" class="d-flex gap-1" style="max-width: 280px;">
                    <?php if(!empty($filterType)): ?> <input type="hidden" name="filter" value="<?php echo e($filterType); ?>"> <?php endif; ?>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama / NIP..." value="<?php echo e($search ?? ''); ?>">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                    <?php if(!empty($search) || !empty($filterType)): ?>
                        <a href="<?php echo e(route('employees.payroll-report')); ?>" class="btn btn-sm btn-outline-secondary" title="Reset"><i class="bi bi-x"></i></a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- MAIN TABLE PENERIMA TUNJANGAN -->
            <div class="table-responsive">
                <table class="payroll-table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 35px;">NO</th>
                            <th style="width: 180px;">NAMA & NIP PEGAWAI</th>
                            <th style="width: 150px;">JABATAN & UNIT KERJA</th>
                            <th style="width: 90px;">PANGKAT / GOL</th>
                            <th style="width: 90px;">STATUS JIWA</th>
                            <th style="width: 95px;">TJ. PASANGAN</th>
                            <th style="width: 95px;">TJ. ANAK</th>
                            <th style="width: 110px;">TJ. JABATAN</th>
                            <th style="width: 85px;">TJ. BERAS</th>
                            <th style="width: 130px;">NO. REKENING BJB</th>
                            <th style="width: 75px;" class="text-center no-print-col">DETAIL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php $al = $emp->allowance; ?>
                            <tr>
                                <td class="text-center fw-bold"><?php echo e($idx + 1); ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo e($emp->full_name); ?></div>
                                    <small class="text-muted font-monospace"><?php echo e($emp->employee_number); ?></small>
                                    <span class="badge bg-secondary-subtle text-secondary small ms-1"><?php echo e($emp->status_pegawai); ?></span>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?php echo e($emp->position->name ?? '-'); ?></div>
                                    <small class="text-muted"><?php echo e($emp->unit_kerja ?: '-'); ?></small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border"><?php echo e($emp->pangkat_golongan ?: '-'); ?></span>
                                    <div class="small text-muted"><?php echo e($al->masker ?? $emp->masa_kerja_tahun ?? 0); ?> Thn</div>
                                </td>
                                <td class="text-center">
                                    <div class="fw-semibold"><?php echo e(($al && $al->status_kawin === 'K') ? 'Kawin (K)' : 'Belum (TK)'); ?></div>
                                    <small class="text-muted">Kode: <strong><?php echo e($al->kd_jiwa ?? '1100'); ?></strong> (<?php echo e($al->jml_jiwa ?? 1); ?> Jiwa)</small>
                                </td>
                                <td class="text-center">
                                    <?php if($al?->has_tj_suami_istri): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            <i class="bi bi-check-circle-fill me-1"></i>Menerima
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($al?->has_tj_anak): ?>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                                            <i class="bi bi-people-fill me-1"></i><?php echo e($al->jumlah_anak_tanggungan); ?> Anak
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($al?->has_tj_struktural): ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                                            <i class="bi bi-award-fill me-1"></i>Struktural
                                        </span>
                                    <?php elseif($al?->has_tj_fungsional): ?>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                            <i class="bi bi-gear-fill me-1"></i>Fungsional
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">Umum</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($al?->has_tj_beras): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            <i class="bi bi-bag-check-fill me-1"></i><?php echo e($al->jml_jiwa ?? 1); ?> Jiwa
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="font-monospace fw-semibold text-primary"><?php echo e($al->nomor_rekening ?? '-'); ?></div>
                                    <small class="text-muted">NPWP: <?php echo e($al->npwp ?? '-'); ?></small>
                                </td>
                                <td class="text-center no-print-col">
                                    <a href="<?php echo e(route('employees.show', ['employee' => $emp, 'tab' => 'allowance'])); ?>" class="btn btn-xs btn-outline-primary py-1 px-2" title="Lihat Hak Tunjangan">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">Tidak ada data pegawai yang sesuai dengan kriteria filter.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- FOOTER SIGNATURE -->
            <div class="row mt-4 pt-2">
                <div class="col-6">
                    <small class="text-muted">Dicetak pada: <?php echo e(date('d F Y H:i')); ?> WIB | SIMPEG-ASSET Bakesbangpol Jawa Barat</small>
                </div>
                <div class="col-6 text-end">
                    <div style="display: inline-block; text-align: center; width: 220px;">
                        <p class="mb-1">Bandung, <?php echo e(date('d F Y')); ?></p>
                        <p class="mb-5">Pengelola Kepegawaian & Tunjangan</p>
                        <p class="fw-bold mb-0">________________________</p>
                        <p class="text-muted small">NIP. .....................................</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html><?php /**PATH D:\BakesBangPol\KesbangPol\resources\views/employees/allowances/payroll_report.blade.php ENDPATH**/ ?>