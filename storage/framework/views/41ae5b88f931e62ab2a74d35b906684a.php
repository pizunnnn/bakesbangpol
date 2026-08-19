<?php $__env->startSection('title', 'Manajemen Unit Kerja'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h1 class="h4 mb-0 fw-bold text-primary">
                        <i class="bi bi-building-gear me-2"></i>Manajemen Unit Kerja / Bidang
                    </h1>
                    <p class="text-muted small mb-0">Kelola master unit kerja Bakesbangpol secara dinamis.</p>
                </div>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Unit Kerja
                </button>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert">
                    <i class="bi bi-check-circle me-1"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i><?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Unit</th>
                            <th>Nama Unit Kerja / Bidang</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Pegawai</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><span class="badge bg-secondary font-monospace"><?php echo e($dept->code); ?></span></td>
                                <td><strong class="text-dark"><?php echo e($dept->name); ?></strong></td>
                                <td><small class="text-muted"><?php echo e($dept->description ?: '-'); ?></small></td>
                                <td>
                                    <span class="badge bg-primary rounded-pill"><?php echo e($dept->employees_count); ?> Pegawai</span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editDepartmentModal<?php echo e($dept->id); ?>" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="<?php echo e(route('departments.destroy', $dept)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus unit kerja ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                    <!-- Modal Edit Department -->
                                    <div class="modal fade" id="editDepartmentModal<?php echo e($dept->id); ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content text-start">
                                                <form action="<?php echo e(route('departments.update', $dept)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <div class="modal-header bg-warning text-dark">
                                                        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Unit Kerja</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small text-muted">Kode Unit Kerja</label>
                                                            <input type="text" name="code" class="form-control" value="<?php echo e(old('code', $dept->code)); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small text-muted">Nama Unit Kerja / Bidang</label>
                                                            <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $dept->name)); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small text-muted">Deskripsi</label>
                                                            <textarea name="description" class="form-control" rows="2"><?php echo e(old('description', $dept->description)); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada unit kerja.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Department -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('departments.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-building-add me-2"></i>Tambah Unit Kerja Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Kode Unit Kerja <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" placeholder="Contoh: WASDA, SEKRETARIAT" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Nama Unit Kerja / Bidang <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Nama bidang / unit kerja" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi singkat unit kerja"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Unit Kerja</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\bakesbangpol\resources\views/departments/index.blade.php ENDPATH**/ ?>