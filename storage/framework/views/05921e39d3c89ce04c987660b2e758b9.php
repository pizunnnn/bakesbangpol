

<?php $__env->startSection('title', 'Sistem Pengadaan & Inventaris BMD'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Sistem Pengadaan & Inventaris BMD</h1>
            <p class="text-muted mb-0">BAKESBANGPOL - SIMKAP ASSET SYSTEM</p>
        </div>
        <a href="<?php echo e(route('assets.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Aset
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-4">
        <div
            class="card-header bg-dark text-white d-flex justify-content-between align-items-center rounded-4 rounded-bottom-0 flex-wrap gap-2">
            <span class="fw-bold">Daftar BMD & Pengadaan Barang (Bakesbangpol)</span>
            <form action="<?php echo e(route('assets.index')); ?>" method="GET" class="d-flex flex-wrap align-items-center gap-1"
                role="search">
                <input type="text" name="search" value="<?php echo e($search ?? ''); ?>" class="form-control form-control-sm me-1"
                    placeholder="Cari nama barang, kode, merk..." aria-label="Cari aset">
                <select name="status" class="form-select form-select-sm me-1 w-auto" aria-label="Filter status aset"
                    onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Tersedia" <?php echo e(($status ?? '') === 'Tersedia' ? 'selected' : ''); ?>>Tersedia</option>
                    <option value="Dipinjam" <?php echo e(($status ?? '') === 'Dipinjam' ? 'selected' : ''); ?>>Dipinjam</option>
                </select>
                <button type="submit" class="btn btn-sm btn-light">
                    <i class="bi bi-search"></i>
                </button>
                <?php if(!empty($search) || !empty($status)): ?>
                    <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-sm btn-outline-light ms-1"
                        title="Hapus filter & pencarian">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover align-middle m-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang / Reg</th>
                        <th>Nama & Spesifikasi Barang</th>
                        <th>Perolehan</th>
                        <th>Nilai (Rp)</th>
                        <th>Unit</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center fw-bold"><?php echo e($assets->firstItem() + $index); ?></td>
                            <td>
                                <span
                                    class="badge bg-secondary mb-1"><?php echo e($asset->kode_barang ?? $asset->asset_code); ?></span><br>
                                <small class="text-muted">Reg:
                                    <?php echo e(str_pad((string) ($asset->no_register ?? '1'), 4, '0', STR_PAD_LEFT)); ?></small>
                            </td>
                            <td>
                                <strong><?php echo e($asset->nama_barang ?? ($asset->category ?? '-')); ?></strong><br>
                                <small class="text-primary"><?php echo e($asset->merk_tipe ?? '-'); ?></small><br>
                                <small class="text-muted"
                                    style="font-size: 0.78rem;"><?php echo e($asset->spesifikasi ?? '-'); ?></small>
                            </td>
                            <td>
                                <small><?php echo e($asset->cara_perolehan ?? '-'); ?></small><br>
                                <small class="text-muted">Tahun: <?php echo e($asset->tahun_perolehan ?? '-'); ?></small>
                            </td>
                            <td class="text-end fw-bold">
                                <?php echo e($asset->nilai_perolehan ? number_format((float) $asset->nilai_perolehan, 0, ',', '.') : '-'); ?>

                            </td>
                            <td class="text-center fw-bold"><?php echo e($asset->jumlah_unit ?? 1); ?></td>
                            <td class="text-center">
                                <?php
                                    $keadaan = $asset->keadaan ?? 'B';
                                    $badge_kondisi =
                                        $keadaan === 'B'
                                            ? 'bg-success'
                                            : ($keadaan === 'KB'
                                                ? 'bg-warning text-dark'
                                                : 'bg-danger');
                                ?>
                                <span class="badge <?php echo e($badge_kondisi); ?>"><?php echo e($keadaan); ?></span>
                            </td>
                            <td class="text-center">
                                <?php if(($asset->status ?? 'Tersedia') === 'Dipinjam'): ?>
                                    <span class="badge bg-danger">Dipinjam</span>
                                    <?php if($asset->currentEmployee): ?>
                                        <div class="small text-danger mt-1">
                                            <i class="bi bi-person"></i> <?php echo e($asset->currentEmployee->full_name); ?>

                                            <?php if($asset->currentEmployee->unit_kerja && $asset->currentEmployee->unit_kerja !== '-'): ?>
                                                <br><small
                                                    class="text-muted"><?php echo e($asset->currentEmployee->unit_kerja); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-success">Tersedia</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="<?php echo e(route('assets.edit', $asset)); ?>" class="btn btn-sm btn-outline-primary"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('assets.destroy', $asset)); ?>" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus data aset ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada data aset/pengadaan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($assets->hasPages()): ?>
            <div class="card-footer bg-white">
                <?php echo e($assets->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\bakesbangpol\resources\views/assets/index.blade.php ENDPATH**/ ?>