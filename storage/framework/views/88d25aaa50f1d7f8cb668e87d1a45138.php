<?php $__env->startSection('title', 'Monitoring & Notifikasi Kenaikan Pangkat Pegawai - Bakesbangpol'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-3 py-2">
    <!-- BREADCRUMB & HEADER -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="bi bi-award text-primary me-2"></i>Monitoring & Notifikasi Kenaikan Pangkat Pegawai
            </h4>
            <p class="text-muted small mb-0">
                Pantau proyeksi dan jadwal jatuh tempo kenaikan pangkat (KP) pegawai Bakesbangpol per bulan dan per tahun.
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('employees.promotions.print', ['month' => $selectedMonth, 'year' => $selectedYear, 'preset' => $preset])); ?>" target="_blank" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center">
                <i class="bi bi-printer me-1"></i>Cetak Daftar Nominatif
            </a>
            <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center">
                <i class="bi bi-people me-1"></i>Data Pegawai
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI BULAN SEKARANG BANNER -->
    <?php if($currentMonthCount > 0): ?>
        <div class="alert alert-warning border-warning-subtle shadow-sm rounded-4 d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="bi bi-bell-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1 text-dark">
                        Pemberitahuan: Terdapat <?php echo e($currentMonthCount); ?> Pegawai Siap / Jatuh Tempo Naik Pangkat Bulan Ini!
                    </h6>
                    <p class="mb-0 text-dark-emphasis small">
                        Pegawai yang telah memenuhi masa kerja 4 tahun pada pangkat terakhir dapat segera diproses berkas usulan kenaikan pangkatnya.
                    </p>
                </div>
            </div>
            <div>
                <a href="<?php echo e(route('employees.promotions', ['preset' => 'current_month'])); ?>" class="btn btn-sm btn-dark fw-semibold px-3">
                    <i class="bi bi-eye me-1"></i>Lihat Pegawai Bulan Ini
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-success border-success-subtle shadow-sm rounded-4 d-flex align-items-center gap-3 mb-4 p-3">
            <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="bi bi-check2-circle fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-success">Seluruh Kenaikan Pangkat Bulan Ini Telah Terkelola</h6>
                <small class="text-muted">Tidak ada berkas kenaikan pangkat yang tertunda untuk bulan ini.</small>
            </div>
        </div>
    <?php endif; ?>

    <!-- FILTER PEMILIH BULAN & TAHUN -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 p-md-4">
            <form action="<?php echo e(route('employees.promotions')); ?>" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-secondary"><i class="bi bi-calendar-month me-1"></i>Pilih Bulan</label>
                    <select name="month" class="form-select form-select-sm">
                        <?php $__currentLoopData = $monthNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($num); ?>" <?php echo e(($selectedMonth == $num && empty($preset)) ? 'selected' : ''); ?>>
                                <?php echo e($name); ?> (Bulan <?php echo e($num); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold small text-secondary"><i class="bi bi-calendar3 me-1"></i>Pilih Tahun</label>
                    <select name="year" class="form-select form-select-sm">
                        <?php for($y = 2024; $y <= 2030; $y++): ?>
                            <option value="<?php echo e($y); ?>" <?php echo e(($selectedYear == $y && empty($preset)) ? 'selected' : ''); ?>>
                                Tahun <?php echo e($y); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-secondary"><i class="bi bi-building me-1"></i>Unit Kerja</label>
                    <select name="department_id" class="form-select form-select-sm">
                        <option value="">Semua Unit Kerja</option>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dept->id); ?>" <?php echo e($departmentId == $dept->id ? 'selected' : ''); ?>>
                                <?php echo e($dept->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold small text-secondary"><i class="bi bi-search me-1"></i>Cari Pegawai</label>
                    <input type="text" name="search" value="<?php echo e($search); ?>" class="form-control form-control-sm" placeholder="Nama / NIP...">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold">
                        <i class="bi bi-filter me-1"></i>Filter
                    </button>
                    <a href="<?php echo e(route('employees.promotions')); ?>" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>

            <!-- QUICK PRESET PILLS -->
            <div class="d-flex gap-2 flex-wrap align-items-center mt-3 pt-3 border-top">
                <span class="small text-muted fw-semibold me-1"><i class="bi bi-lightning-charge-fill text-warning"></i> Filter Cepat:</span>
                <a href="<?php echo e(route('employees.promotions', ['preset' => 'current_month'])); ?>" class="badge <?php echo e($preset === 'current_month' ? 'bg-primary text-white' : 'bg-light text-dark border'); ?> text-decoration-none px-3 py-2">
                    Bulan Ini (<?php echo e(\Carbon\Carbon::now()->translatedFormat('F Y')); ?>)
                </a>
                <a href="<?php echo e(route('employees.promotions', ['preset' => 'next_month'])); ?>" class="badge <?php echo e($preset === 'next_month' ? 'bg-primary text-white' : 'bg-light text-dark border'); ?> text-decoration-none px-3 py-2">
                    Bulan Depan (<?php echo e(\Carbon\Carbon::now()->addMonth()->translatedFormat('F Y')); ?>)
                </a>
                <a href="<?php echo e(route('employees.promotions', ['preset' => 'next_3_months'])); ?>" class="badge <?php echo e($preset === 'next_3_months' ? 'bg-primary text-white' : 'bg-light text-dark border'); ?> text-decoration-none px-3 py-2">
                    3 Bulan Kedepan
                </a>
                <a href="<?php echo e(route('employees.promotions', ['preset' => 'this_year'])); ?>" class="badge <?php echo e($preset === 'this_year' ? 'bg-primary text-white' : 'bg-light text-dark border'); ?> text-decoration-none px-3 py-2">
                    Sepanjang Tahun <?php echo e(\Carbon\Carbon::now()->year); ?>

                </a>
            </div>
        </div>
    </div>

    <!-- TIMELINE PROYEKSI 12 BULAN KEDEPAN CHIPS -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small fw-bold text-secondary text-uppercase"><i class="bi bi-graph-up-arrow me-1 text-primary"></i>Jadwal Kenaikan Pangkat 12 Bulan Kedepan</span>
                <small class="text-muted">Klik salah satu bulan untuk melihat rincian pegawai</small>
            </div>
            <div class="d-flex gap-2 overflow-auto pb-2" style="white-space: nowrap;">
                <?php $__currentLoopData = $timelineProjection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isActive = ($selectedMonth == $tl['month'] && $selectedYear == $tl['year'] && empty($preset));
                    ?>
                    <a href="<?php echo e(route('employees.promotions', ['month' => $tl['month'], 'year' => $tl['year']])); ?>" class="btn btn-sm <?php echo e($isActive ? 'btn-primary' : ($tl['count'] > 0 ? 'btn-outline-primary' : 'btn-light text-muted border')); ?> text-start py-2 px-3 rounded-3" style="min-width: 100px;">
                        <div class="small fw-semibold"><?php echo e($tl['label']); ?></div>
                        <div class="fs-6 fw-bold mt-1">
                            <?php echo e($tl['count']); ?> <span class="small fw-normal" style="font-size: 10px;">Pegawai</span>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- STATISTIK RINGKASAN PERIODE TERPILIH -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                <div class="text-muted small fw-semibold">Total Nominatif Periode Ini</div>
                <div class="fs-4 fw-bold text-dark mt-1"><?php echo e(count($selectedPeriodEmployees)); ?> Pegawai</div>
                <small class="text-muted">
                    <?php if($preset === 'this_year'): ?> Tahun <?php echo e($selectedYear); ?>

                    <?php elseif($preset === 'next_3_months'): ?> 3 Bulan Kedepan
                    <?php else: ?> <?php echo e($monthNames[$selectedMonth] ?? 'Bulan Ini'); ?> <?php echo e($selectedYear); ?>

                    <?php endif; ?>
                </small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-danger">
                <div class="text-muted small fw-semibold">Golongan IV (Pembina)</div>
                <div class="fs-4 fw-bold text-danger mt-1"><?php echo e($countGolIV); ?> Orang</div>
                <small class="text-muted">Jenjang Golongan IV/a - IV/e</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                <div class="text-muted small fw-semibold">Golongan III (Penata)</div>
                <div class="fs-4 fw-bold text-success mt-1"><?php echo e($countGolIII); ?> Orang</div>
                <small class="text-muted">Jenjang Golongan III/a - III/d</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-info">
                <div class="text-muted small fw-semibold">Golongan II & PPPK</div>
                <div class="fs-4 fw-bold text-info mt-1"><?php echo e($countGolII + $countPppk); ?> Orang</div>
                <small class="text-muted">Pengatur / PPPK Golongan</small>
            </div>
        </div>
    </div>

    <!-- TABEL NOMINATIF PEGAWAI NAIK PANGKAT -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="fw-bold text-dark mb-0">
                <i class="bi bi-list-check me-2 text-primary"></i>Daftar Pegawai Siap / Jatuh Tempo Naik Pangkat
                <span class="badge bg-primary-subtle text-primary ms-1">
                    <?php if($preset === 'this_year'): ?> Tahun <?php echo e($selectedYear); ?>

                    <?php elseif($preset === 'next_3_months'): ?> 3 Bulan Kedepan
                    <?php else: ?> <?php echo e($monthNames[$selectedMonth] ?? ''); ?> <?php echo e($selectedYear); ?>

                    <?php endif; ?>
                </span>
            </h6>
            <span class="small text-muted">Ditemukan <strong><?php echo e(count($selectedPeriodEmployees)); ?></strong> Pegawai</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 40px;">No</th>
                            <th style="width: 220px;">Nama Pegawai & NIP</th>
                            <th style="width: 170px;">Jabatan & Unit Kerja</th>
                            <th style="width: 140px;">Pangkat Saat Ini</th>
                            <th style="width: 180px;">Target Pangkat Baru</th>
                            <th style="width: 110px;">TMT Terakhir</th>
                            <th style="width: 130px;">Jatuh Tempo</th>
                            <th style="width: 120px;" class="text-center">Status</th>
                            <th style="width: 120px;" class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $selectedPeriodEmployees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $nextDate = $emp->tanggal_kenaikan_pangkat_berikutnya;
                                $isOverdue = $emp->is_eligible_kenaikan_pangkat;
                                $isCurrentMonth = $nextDate && $nextDate->format('Y-m') === \Carbon\Carbon::now()->format('Y-m');
                            ?>
                            <tr>
                                <td class="ps-3 fw-bold text-secondary"><?php echo e($idx + 1); ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo e($emp->full_name); ?></div>
                                    <small class="text-muted font-monospace"><?php echo e($emp->employee_number); ?></small>
                                    <span class="badge bg-secondary-subtle text-secondary small ms-1"><?php echo e($emp->status_pegawai); ?></span>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?php echo e($emp->position->name ?? '-'); ?></div>
                                    <small class="text-muted"><?php echo e($emp->unit_kerja ?: '-'); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        <?php echo e($emp->pangkat_golongan ?: '-'); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary">
                                        <i class="bi bi-arrow-up-circle-fill text-success me-1"></i><?php echo e($emp->pangkat_berikutnya_estimasi); ?>

                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted"><?php echo e($emp->pangkat_terakhir_tmt ? $emp->pangkat_terakhir_tmt->format('d/m/Y') : '-'); ?></small>
                                </td>
                                <td>
                                    <strong><?php echo e($nextDate ? $nextDate->format('d/m/Y') : '-'); ?></strong>
                                    <div class="small text-muted"><?php echo e($nextDate ? $nextDate->diffForHumans() : ''); ?></div>
                                </td>
                                <td class="text-center">
                                    <?php if($isOverdue): ?>
                                        <span class="badge bg-danger px-2 py-1">
                                            <i class="bi bi-exclamation-circle me-1"></i>Siap Diproses
                                        </span>
                                    <?php elseif($isCurrentMonth): ?>
                                        <span class="badge bg-warning text-dark px-2 py-1">
                                            <i class="bi bi-clock me-1"></i>Bulan Ini
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                                            <i class="bi bi-calendar-event me-1"></i>Mendatang
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center pe-3">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="<?php echo e(route('employees.show', ['employee' => $emp, 'tab' => 'rank'])); ?>" class="btn btn-sm btn-outline-primary" title="Buka Detail & Riwayat Pangkat">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary"></i>
                                    Tidak ada jadwal pegawai yang naik pangkat untuk periode bulan & tahun yang dipilih.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BakesBangPol\KesbangPol\resources\views/employees/promotions/index.blade.php ENDPATH**/ ?>