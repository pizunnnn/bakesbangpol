<?php $__env->startSection('title', 'Daftar Aset yang Dapat Dihapus (Umur >= 10 Tahun)'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h1 class="h4 mb-0 fw-bold text-danger">
                        <i class="bi bi-trash3-fill me-2"></i>Daftar Aset yang Dapat Dihapus
                    </h1>
                    <p class="text-muted small mb-0">Daftar aset yang telah berumur >= 10 tahun atau ditandai untuk proses usulan penghapusan.</p>
                </div>
                <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Semua Aset
                </a>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert">
                    <i class="bi bi-check-circle me-1"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Form Filter -->
            <form action="<?php echo e(route('assets.deletable')); ?>" method="GET" class="row g-2 mb-4 align-items-center" role="search">
                <div class="col-md-3">
                    <input type="text" name="search" value="<?php echo e($search ?? ''); ?>" class="form-control form-control-sm" placeholder="Cari nama, kode aset, barang...">
                </div>
                <div class="col-md-2">
                    <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e(($categoryId ?? '') == $cat->id ? 'selected' : ''); ?>>
                                <?php echo e($cat->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="bidang" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Unit Kerja</option>
                        <?php $__currentLoopData = $bidangList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b); ?>" <?php echo e(($bidang ?? '') === $b ? 'selected' : ''); ?>>
                                <?php echo e($b); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="Dapat Dihapus" <?php echo e(($status ?? '') === 'Dapat Dihapus' ? 'selected' : ''); ?>>Dapat Dihapus</option>
                        <option value="Sudah Dihapus" <?php echo e(($status ?? '') === 'Sudah Dihapus' ? 'selected' : ''); ?>>Sudah Dihapus</option>
                        <option value="Aktif" <?php echo e(($status ?? '') === 'Aktif' ? 'selected' : ''); ?>>Aktif</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-filter me-1"></i>Filter
                    </button>
                    <?php if(!empty($search) || !empty($categoryId) || !empty($bidang) || !empty($status)): ?>
                        <a href="<?php echo e(route('assets.deletable')); ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Reset
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Aset / Reg</th>
                            <th>Nama Aset</th>
                            <th>Kategori</th>
                            <th>Tgl/Thn Perolehan</th>
                            <th>Unit Kerja & Lokasi</th>
                            <th>Kondisi</th>
                            <th>Nilai Perolehan</th>
                            <th>Umur Aset</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><span class="badge bg-secondary font-monospace"><?php echo e($asset->asset_code); ?></span></div>
                                    <small class="text-muted"><?php echo e($asset->kode_barang); ?></small>
                                </td>
                                <td>
                                    <strong class="text-dark"><?php echo e($asset->nama_barang); ?></strong>
                                    <?php if($asset->merk_tipe): ?><div class="small text-muted"><?php echo e($asset->merk_tipe); ?></div><?php endif; ?>
                                </td>
                                <td><small class="fw-semibold"><?php echo e($asset->categoryRelation->name ?? $asset->category ?? '-'); ?></small></td>
                                <td>
                                    <div><?php echo e($asset->tanggal_perolehan ? $asset->tanggal_perolehan->format('d/m/Y') : '-'); ?></div>
                                    <small class="text-muted">Tahun <?php echo e($asset->tahun_perolehan ?: '-'); ?></small>
                                </td>
                                <td>
                                    <div><small class="fw-semibold"><?php echo e($asset->bidang ?: '-'); ?></small></div>
                                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i><?php echo e($asset->location ?: '-'); ?></small>
                                </td>
                                <td><span class="badge bg-info text-dark"><?php echo e($asset->condition ?: $asset->keadaan ?: '-'); ?></span></td>
                                <td class="fw-bold text-success">Rp <?php echo e(number_format((float)($asset->nilai_perolehan ?: $asset->purchase_price), 0, ',', '.')); ?></td>
                                <td>
                                    <span class="badge bg-danger fs-6 px-2 py-1"><i class="bi bi-exclamation-triangle me-1"></i><?php echo e($asset->age_formatted); ?></span>
                                </td>
                                <td>
                                    <?php if($asset->status === 'Dapat Dihapus'): ?>
                                        <span class="badge bg-warning text-dark border">Dapat Dihapus</span>
                                    <?php elseif($asset->status === 'Sudah Dihapus'): ?>
                                        <span class="badge bg-secondary">Sudah Dihapus</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><?php echo e($asset->status); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('assets.show', $asset)); ?>" class="btn btn-info text-white" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#verifyDisposalModal<?php echo e($asset->id); ?>" title="Verifikasi Penghapusan">
                                            <i class="bi bi-shield-check"></i>
                                        </button>
                                    </div>

                                    <!-- Modal Verifikasi Disposal -->
                                    <div class="modal fade" id="verifyDisposalModal<?php echo e($asset->id); ?>" tabindex="-1">
                                        <div class="modal-dialog text-start">
                                            <div class="modal-content">
                                                <form action="<?php echo e(route('assets.verify-disposal', $asset)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title"><i class="bi bi-shield-check me-2"></i>Verifikasi Status Penghapusan</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-muted small mb-2">Aset: <strong><?php echo e($asset->nama_barang); ?></strong> (Umur: <?php echo e($asset->age_formatted); ?>)</p>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small text-muted">Pilih Status Verifikasi</label>
                                                            <select name="status" class="form-select" required>
                                                                <option value="Dapat Dihapus" <?php echo e($asset->status == 'Dapat Dihapus' ? 'selected' : ''); ?>>Dapat Dihapus (Siap Diproses)</option>
                                                                <option value="Sudah Dihapus" <?php echo e($asset->status == 'Sudah Dihapus' ? 'selected' : ''); ?>>Sudah Dihapus (Selesai Penghapusan)</option>
                                                                <option value="Aktif" <?php echo e($asset->status == 'Aktif' ? 'selected' : ''); ?>>Tetap Aktif / Diperpanjang</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small text-muted">Catatan Administrasi</label>
                                                            <textarea name="notes" class="form-control" rows="3" placeholder="Catatan hasil pemeriksaan fisik/administrasi aset..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger"><i class="bi bi-check-lg me-1"></i>Simpan Verifikasi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">Belum ada aset yang memenuhi kriteria umur >= 10 tahun.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($assets->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\bakesbangpol\resources\views/assets/deletable.blade.php ENDPATH**/ ?>