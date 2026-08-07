

<?php $__env->startSection('title', 'Data Pegawai'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h1 class="h4 mb-0">Data Pegawai</h1>
                <div class="d-flex gap-2 flex-wrap">
                    <form action="<?php echo e(route('employees.index')); ?>" method="GET"
                        class="d-flex flex-wrap align-items-center gap-2" role="search">
                        <input type="text" name="search" value="<?php echo e($search ?? ''); ?>" class="form-control form-control-sm"
                            style="min-width: 210px;" placeholder="Cari nama, NIP, unit, jabatan..."
                            aria-label="Cari pegawai">

                        <select name="status_pegawai" class="form-select form-select-sm w-auto"
                            aria-label="Filter status pegawai" onchange="this.form.submit()">
                            <option value="">Semua Status Pegawai</option>
                            <?php $__currentLoopData = ['Pegawai Tetap', 'P3K Paruh Waktu', 'Outsourcing']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($s); ?>" <?php echo e(($statusPegawai ?? '') === $s ? 'selected' : ''); ?>>
                                    <?php echo e($s); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <select name="status_kepegawaian" class="form-select form-select-sm w-auto"
                            aria-label="Filter status kepegawaian" onchange="this.form.submit()">
                            <option value="">Semua Status Keaktifan</option>
                            <option value="active" <?php echo e(($statusKepegawaian ?? '') === 'active' ? 'selected' : ''); ?>>
                                Aktif
                            </option>
                            <option value="inactive" <?php echo e(($statusKepegawaian ?? '') === 'inactive' ? 'selected' : ''); ?>>
                                Tidak Aktif
                            </option>
                        </select>

                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-search"></i>
                        </button>
                        <?php if(!empty($search) || !empty($statusPegawai) || !empty($statusKepegawaian)): ?>
                            <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-sm btn-outline-secondary"
                                title="Hapus filter & pencarian">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        <?php endif; ?>
                    </form>
                    <a href="<?php echo e(route('employees.create')); ?>"
                        class="btn btn-sm btn-primary d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Pegawai
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Unit Kerja</th>
                            <th>Jabatan</th>
                            <th>Status Pegawai</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($employee->full_name); ?></td>
                                <td><?php echo e($employee->employee_number ?: '-'); ?></td>
                                <td><?php echo e($employee->unit_kerja ?: $employee->department->name ?? '-'); ?></td>
                                <td><?php echo e($employee->position->name ?? '-'); ?></td>
                                <td>
                                    <?php if($employee->status_pegawai): ?>
                                        <span class="badge bg-primary"><?php echo e($employee->status_pegawai); ?></span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($employee->employment_status == 'active'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo e(route('employees.edit', $employee)); ?>" class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('employees.destroy', $employee)); ?>" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada data pegawai.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($employees->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\bakesbangpol\resources\views/employees/index.blade.php ENDPATH**/ ?>