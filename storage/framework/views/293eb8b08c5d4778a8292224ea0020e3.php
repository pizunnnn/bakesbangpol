

<?php $__env->startSection('title', 'Dashboard | SIMPEG-ASSET'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="hero-banner rounded-4 p-4 p-md-5 mb-4 position-relative overflow-hidden">
        <div class="position-relative z-1">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="badge text-bg-light text-primary mb-2 px-3 py-2 rounded-pill fw-bold fs-7 shadow-sm">
                        <i class="bi bi-stars me-1"></i>Selamat Datang
                    </span>
                    <h1 class="text-white fw-bold mb-2" style="font-size: 2rem; letter-spacing: -0.5px;">
                        <?php echo e(auth()->user()->name ?? 'Pengguna'); ?> 👋
                    </h1>
                    <p class="text-white-90 mb-0 fs-6" style="color: rgba(255, 255, 255, 0.92); font-weight: 400;">
                        Ringkasan data <strong class="text-white">Manajemen Aset</strong> & <strong class="text-white">Kepegawaian Bakesbangpol</strong> pada <?php echo e(now()->translatedFormat('l, d F Y')); ?>

                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('assets.create')); ?>" class="btn btn-light btn-md fw-bold text-primary shadow-sm rounded-3 px-3">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Aset
                    </a>
                    <a href="<?php echo e(route('employees.create')); ?>" class="btn btn-outline-light btn-md fw-bold shadow-sm rounded-3 px-3">
                        <i class="bi bi-person-plus me-1"></i>Tambah Pegawai
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-4">
        
        <div class="col-12 col-lg-6">
            
            <div class="card border-0 shadow-sm rounded-4 mb-3 p-3" style="background: linear-gradient(to right, #f0f9ff, #ffffff); border-left: 5px solid #0284c7 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; background-color: #0284c7; color: #ffffff;">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>
                        <div>
                            <h2 class="h5 mb-0 fw-bold" style="color: #0c4a6e; font-size: 1.25rem;">Manajemen Aset</h2>
                            <span class="text-muted fs-7" style="color: #475569 !important;">Statistik, kategori, & aktivitas aset terbaru</span>
                        </div>
                    </div>
                    <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-sm rounded-pill fw-bold px-3 py-1 shadow-sm" style="background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">
                        Kelola Aset <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            
            <div class="row g-3 mb-3">
                <div class="col-12 col-sm-4">
                    <a href="<?php echo e(route('assets.index')); ?>" class="text-decoration-none">
                        <div class="stat-card rounded-4 p-3 h-100 d-flex align-items-center gap-3 cursor-pointer" style="background: linear-gradient(135deg, #0284c7, #38bdf8);">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center shadow-sm">
                                <i class="bi bi-box-seam fs-3 text-white"></i>
                            </div>
                            <div>
                                <div class="stat-value text-white fw-bold" style="font-size: 1.85rem; line-height: 1.1;"><?php echo e(number_format((int)$statistics['total_assets'], 0, ',', '.')); ?></div>
                                <div class="stat-label fw-semibold text-white" style="font-size: 0.9rem; opacity: 0.95;">Total Aset</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-4">
                    <a href="<?php echo e(route('assets.index')); ?>" class="text-decoration-none">
                        <div class="stat-card rounded-4 p-3 h-100 d-flex align-items-center gap-3 cursor-pointer" style="background: linear-gradient(135deg, #0369a1, #0ea5e9);">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center shadow-sm">
                                <i class="bi bi-check-circle fs-3 text-white"></i>
                            </div>
                            <div>
                                <div class="stat-value text-white fw-bold" style="font-size: 1.85rem; line-height: 1.1;"><?php echo e(number_format((int)$statistics['active_assets'], 0, ',', '.')); ?></div>
                                <div class="stat-label fw-semibold text-white" style="font-size: 0.9rem; opacity: 0.95;">Aktif / Tersedia</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-4">
                    <a href="<?php echo e(route('assets.index')); ?>" class="text-decoration-none">
                        <div class="stat-card rounded-4 p-3 h-100 d-flex align-items-center gap-3 cursor-pointer" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center shadow-sm">
                                <i class="bi bi-tools fs-3 text-white"></i>
                            </div>
                            <div>
                                <div class="stat-value text-white fw-bold" style="font-size: 1.85rem; line-height: 1.1;"><?php echo e(number_format((int)$statistics['in_repair_assets'], 0, ',', '.')); ?></div>
                                <div class="stat-label fw-semibold text-white" style="font-size: 0.9rem; opacity: 0.95;">Perbaikan</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            
            <div class="row g-3 mb-3">
                <div class="col-12 col-sm-6">
                    <a href="<?php echo e(route('assets.index')); ?>" class="text-decoration-none">
                        <div class="stat-card rounded-4 p-3 h-100 d-flex align-items-center gap-3 cursor-pointer" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center shadow-sm">
                                <i class="bi bi-exclamation-triangle fs-3 text-white"></i>
                            </div>
                            <div>
                                <div class="stat-value text-white fw-bold" style="font-size: 1.75rem; line-height: 1.1;"><?php echo e(number_format((int)$statistics['damaged_assets'], 0, ',', '.')); ?></div>
                                <div class="stat-label fw-semibold text-white" style="font-size: 0.9rem; opacity: 0.95;">Aset Rusak</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6">
                    <a href="<?php echo e(route('assets.deletable')); ?>" class="text-decoration-none">
                        <div class="card shadow-sm border-0 rounded-4 p-3 text-white h-100" style="background: linear-gradient(135deg, #0284c7, #2563eb);">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-bold" style="font-size: 0.9rem;"><i class="bi bi-trash3 me-1"></i>Umur >= 10 Thn</span>
                                <span class="badge bg-white font-monospace px-2 py-1" style="color: #0284c7 !important; font-weight: 700;">Disposal</span>
                            </div>
                            <div class="fw-bold text-white" style="font-size: 1.75rem;"><?php echo e(number_format((int)$statistics['aged_assets'], 0, ',', '.')); ?> <small class="fs-6 fw-semibold">Unit</small></div>
                            <div class="text-white-90" style="font-size: 0.82rem; color: rgba(255, 255, 255, 0.9);">Dapat Diproses Penghapusan</div>
                        </div>
                    </a>
                </div>
            </div>

            
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6">
                    <a href="<?php echo e(route('assets.index')); ?>" class="text-decoration-none">
                        <div class="card shadow-sm border-0 rounded-4 p-3 text-white h-100" style="background: linear-gradient(135deg, #0369a1, #0284c7);">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-bold" style="font-size: 0.9rem;"><i class="bi bi-wrench me-1"></i>Total Pemeliharaan</span>
                                <span class="badge bg-white text-primary font-monospace px-2 py-1" style="font-weight: 700;">Maintenance</span>
                            </div>
                            <div class="fw-bold text-white" style="font-size: 1.75rem;"><?php echo e(number_format((int)$statistics['total_maintenances'], 0, ',', '.')); ?> <small class="fs-6 fw-semibold">Kegiatan</small></div>
                            <div class="text-white-90" style="font-size: 0.82rem; color: rgba(255, 255, 255, 0.9);">Total Biaya: <strong>Rp <?php echo e(number_format((float)$statistics['total_maintenance_cost'], 0, ',', '.')); ?></strong></div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6">
                    <a href="<?php echo e(route('assets.index')); ?>" class="text-decoration-none">
                        <div class="card shadow-sm border-0 rounded-4 p-3 text-white h-100" style="background: linear-gradient(135deg, #0f172a, #334155);">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-bold" style="font-size: 0.9rem;"><i class="bi bi-car-front me-1"></i>Kendaraan Dinas</span>
                                <span class="badge bg-secondary font-monospace px-2 py-1" style="font-weight: 700;">Fleet</span>
                            </div>
                            <div class="fw-bold text-white" style="font-size: 1.75rem;"><?php echo e(number_format((int)$statistics['total_vehicles'], 0, ',', '.')); ?> <small class="fs-6 fw-semibold">Unit</small></div>
                            <div class="text-white-90" style="font-size: 0.82rem; color: rgba(255, 255, 255, 0.9);">Dalam Perbaikan: <strong><?php echo e(number_format((int)$statistics['vehicles_in_repair'], 0, ',', '.')); ?> Unit</strong></div>
                        </div>
                    </a>
                </div>
            </div>

            
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h2 class="h6 mb-1 fw-bold" style="color: #0284c7; font-size: 1.1rem;"><i class="bi bi-pie-chart-fill me-2"></i>Aset per Kategori</h2>
                    <p class="text-muted mb-0 fs-7">Komposisi aset berdasarkan kategori master</p>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center p-3" style="min-height: 250px;">
                    <canvas id="assetsByCategoryChart" height="210"></canvas>
                </div>
            </div>

            
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3 pb-2">
                    <h2 class="h6 mb-0 fw-bold" style="color: #0284c7; font-size: 1.1rem;"><i class="bi bi-box-seam-fill me-2"></i>Aset Terbaru</h2>
                    <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-sm btn-light fw-bold rounded-pill px-3" style="color: #0284c7; background-color: #f0f9ff;">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background-color: #f8fafc; color: #475569;">
                                <tr>
                                    <th class="ps-4 py-3 fw-bold" style="font-size: 0.9rem;">Nama Barang</th>
                                    <th class="py-3 fw-bold" style="font-size: 0.9rem;">Kode</th>
                                    <th class="py-3 fw-bold" style="font-size: 0.9rem;">Jumlah</th>
                                    <th class="text-end pe-4 py-3 fw-bold" style="font-size: 0.9rem;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentAssets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-bold text-dark" style="font-size: 0.95rem;"><?php echo e($asset->nama_barang ?? ($asset->categoryRelation?->name ?? '-')); ?></div>
                                        </td>
                                        <td class="py-3 text-secondary font-monospace" style="font-size: 0.9rem;"><?php echo e($asset->asset_code ?? '-'); ?></td>
                                        <td class="py-3 fw-semibold text-dark" style="font-size: 0.95rem;"><?php echo e($asset->jumlah_unit ?? 1); ?> Unit</td>
                                        <td class="text-end pe-4 py-3">
                                            <span class="badge rounded-pill px-3 py-2 font-semibold" style="background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 0.82rem;">
                                                <?php echo e($asset->status ?? 'Aktif'); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox d-block fs-2 mb-2 text-secondary"></i>Belum ada data aset.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-12 col-lg-6">
            
            <div class="card border-0 shadow-sm rounded-4 mb-3 p-3" style="background: linear-gradient(to right, #ecfdf5, #ffffff); border-left: 5px solid #059669 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; background-color: #059669; color: #ffffff;">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                        <div>
                            <h2 class="h5 mb-0 fw-bold" style="color: #064e3b; font-size: 1.25rem;">Kepegawaian & SDM</h2>
                            <span class="text-muted fs-7" style="color: #475569 !important;">Statistik pegawai, unit kerja, & daftar terbaru</span>
                        </div>
                    </div>
                    <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-sm rounded-pill fw-bold px-3 py-1 shadow-sm" style="background-color: #d1fae5; color: #047857; border: 1px solid #a7f3d0;">
                        Kelola Pegawai <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6">
                    <a href="<?php echo e(route('employees.index')); ?>" class="text-decoration-none">
                        <div class="stat-card rounded-4 p-3 h-100 d-flex align-items-center gap-3 cursor-pointer" style="background: linear-gradient(135deg, #10b981, #34d399);">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center shadow-sm">
                                <i class="bi bi-people fs-2 text-white"></i>
                            </div>
                            <div>
                                <div class="stat-value text-white fw-bold" style="font-size: 2rem; line-height: 1.1;"><?php echo e(number_format((int)$statistics['employees'], 0, ',', '.')); ?></div>
                                <div class="stat-label fw-semibold text-white" style="font-size: 0.95rem; opacity: 0.95;">Total Pegawai</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6">
                    <a href="<?php echo e(route('departments.index')); ?>" class="text-decoration-none">
                        <div class="stat-card rounded-4 p-3 h-100 d-flex align-items-center gap-3 cursor-pointer" style="background: linear-gradient(135deg, #059669, #10b981);">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center shadow-sm">
                                <i class="bi bi-building fs-2 text-white"></i>
                            </div>
                            <div>
                                <div class="stat-value text-white fw-bold" style="font-size: 2rem; line-height: 1.1;"><?php echo e(number_format((int)($statistics['departments'] ?? $employeesByDepartment->count()), 0, ',', '.')); ?></div>
                                <div class="stat-label fw-semibold text-white" style="font-size: 0.95rem; opacity: 0.95;">Unit Kerja / Bidang</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h2 class="h6 mb-1 fw-bold" style="color: #059669; font-size: 1.1rem;"><i class="bi bi-pie-chart-fill me-2"></i>Pegawai per Unit Kerja</h2>
                    <p class="text-muted mb-0 fs-7">Distribusi & proporsi pegawai berdasarkan unit kerja / bidang</p>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center p-3" style="min-height: 250px;">
                    <canvas id="employeesByDepartmentChart" height="210"></canvas>
                </div>
            </div>

            
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3 pb-2">
                    <h2 class="h6 mb-0 fw-bold" style="color: #059669; font-size: 1.1rem;"><i class="bi bi-person-lines-fill me-2"></i>Pegawai Terbaru</h2>
                    <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-sm btn-light fw-bold rounded-pill px-3" style="color: #059669; background-color: #ecfdf5;">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background-color: #f8fafc; color: #475569;">
                                <tr>
                                    <th class="ps-4 py-3 fw-bold" style="font-size: 0.9rem;">Nama Pegawai</th>
                                    <th class="py-3 fw-bold" style="font-size: 0.9rem;">NIP</th>
                                    <th class="py-3 fw-bold" style="font-size: 0.9rem;">Unit Kerja</th>
                                    <th class="text-end pe-4 py-3 fw-bold" style="font-size: 0.9rem;">Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentEmployees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <?php if(!empty($employee->photo) && file_exists(public_path('storage/' . $employee->photo))): ?>
                                                    <img src="<?php echo e(asset('storage/' . $employee->photo)); ?>" alt="<?php echo e($employee->full_name); ?>" class="employee-avatar shadow-sm border border-2 border-white">
                                                <?php else: ?>
                                                    <div class="employee-avatar text-white fw-bold text-uppercase shadow-sm" style="background: linear-gradient(135deg, #059669, #10b981);">
                                                        <?php echo e(substr($employee->full_name ?? '?', 0, 1)); ?>

                                                    </div>
                                                <?php endif; ?>
                                                <div style="min-width: 0;">
                                                    <span class="fw-bold text-dark d-block text-truncate" style="font-size: 0.95rem; max-width: 180px;" title="<?php echo e($employee->full_name); ?>">
                                                        <?php echo e($employee->full_name); ?>

                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-secondary font-monospace" style="font-size: 0.9rem;"><?php echo e($employee->employee_number ?? '-'); ?></td>
                                        <td class="py-3 text-dark fw-semibold" style="font-size: 0.9rem;"><?php echo e($employee->department?->name ?? '-'); ?></td>
                                        <td class="text-end pe-4 py-3">
                                            <span class="badge rounded-pill px-3 py-2 font-semibold" style="background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; font-size: 0.82rem;">
                                                <?php echo e($employee->position?->name ?? '-'); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox d-block fs-2 mb-2 text-secondary"></i>Belum ada data pegawai.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        body {
            font-size: 1rem;
            color: #1e293b;
        }

        .hero-banner {
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #2563eb 100%);
            box-shadow: 0 10px 25px rgba(30, 58, 138, .2);
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -10%;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -70%;
            right: 15%;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
        }

        .stat-card {
            box-shadow: 0 4px 15px rgba(0, 0, 0, .06);
            transition: transform .25s ease, box-shadow .25s ease;
            border: 1px solid rgba(255, 255, 255, .15);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, .22);
            backdrop-filter: blur(6px);
            flex-shrink: 0;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .fs-7 {
            font-size: 0.85rem;
        }

        .employee-avatar {
            width: 38px;
            height: 38px;
            min-width: 38px;
            min-height: 38px;
            max-width: 38px;
            max-height: 38px;
            flex-shrink: 0;
            aspect-ratio: 1 / 1;
            border-radius: 50% !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Calm & Soothing Emerald/Meadow Green tonal palette for employee chart (distinct from blue assets)
        const empCanvas = document.getElementById('employeesByDepartmentChart');
        const empCounts = <?php echo json_encode($employeesByDepartment->pluck('employees_count')); ?>;
        const empLabels = <?php echo json_encode($employeesByDepartment->pluck('name')); ?>;
        const employeePalette = [
            '#059669', // Emerald primer (Sekretariat / terbesar)
            '#10b981', // Meadow Green
            '#34d399', // Soft Mint
            '#047857', // Deep Pine Green
            '#6ee7b7', // Light Sage
            '#a7f3d0', // Pale Mint
            '#065f46'  // Dark Forest
        ];

        new Chart(empCanvas, {
            type: 'doughnut',
            data: {
                labels: empLabels,
                datasets: [{
                    data: empCounts,
                    backgroundColor: employeePalette,
                    borderWidth: 2.5,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                animation: {
                    duration: 900,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 14,
                            font: { size: 12, weight: '600' },
                            color: '#334155'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#064e3b',
                        titleColor: '#ffffff',
                        bodyColor: '#d1fae5',
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 13, weight: '600' },
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(ctx) {
                                const val = ctx.parsed || 0;
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ` ${val} Orang Pegawai (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Soft Blue tonal palette for asset chart (soothing & distinct)
        new Chart(document.getElementById('assetsByCategoryChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($assetsByCategory->pluck('name')); ?>,
                datasets: [{
                    data: <?php echo json_encode($assetsByCategory->pluck('assets_count')); ?>,
                    backgroundColor: ['#0284c7', '#0ea5e9', '#38bdf8', '#60a5fa', '#93c5fd', '#3b82f6'],
                    borderWidth: 2.5,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                animation: {
                    duration: 900,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 14,
                            font: { size: 12, weight: '600' },
                            color: '#334155'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0c4a6e',
                        titleColor: '#ffffff',
                        bodyColor: '#e0f2fe',
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 13, weight: '600' },
                        padding: 12,
                        cornerRadius: 8
                    }
                }
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BakesBangPol\KesbangPol\resources\views/dashboard/index.blade.php ENDPATH**/ ?>