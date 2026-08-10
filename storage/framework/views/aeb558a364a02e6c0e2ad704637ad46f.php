

<?php $__env->startSection('title', 'Katalog Barang'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Katalog Barang</h1>
            <p class="text-muted mb-0">Kelola kategori & kode barang (Permendagri) untuk pengadaan aset</p>
        </div>
        <a href="<?php echo e(route('catalog.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Katalog
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
            <span class="fw-bold">Daftar Kode Barang & Kategori</span>
            <form action="<?php echo e(route('catalog.index')); ?>" method="GET" class="d-flex align-items-center gap-1">
                <input type="text" name="search" value="<?php echo e($search ?? ''); ?>"
                    class="form-control form-control-sm me-1 w-auto" placeholder="Cari kode / nama barang..."
                    aria-label="Cari katalog">
                <button type="submit" class="btn btn-sm btn-light">
                    <i class="bi bi-search"></i>
                </button>
                <?php if(!empty($search)): ?>
                    <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-sm btn-outline-light ms-1"
                        title="Hapus pencarian">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover align-middle m-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th style="width:140px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $catalogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $catalog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center fw-bold"><?php echo e($catalogs->firstItem() + $index); ?></td>
                            <td>
                                <span class="badge bg-secondary"><?php echo e($catalog->kode_barang); ?></span>
                            </td>
                            <td><?php echo e($catalog->nama_barang); ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="<?php echo e(route('catalog.edit', $catalog)); ?>" class="btn btn-sm btn-outline-primary"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('catalog.destroy', $catalog)); ?>" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus katalog ini? Aset yang sudah ada tidak akan terpengaruh.')">
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
                            <td colspan="4" class="text-center text-muted py-4">Belum ada katalog barang. Klik
                                "Tambah Katalog" untuk menambahkan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($catalogs->hasPages()): ?>
            <div class="card-footer bg-white">
                <?php echo e($catalogs->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\bakesbangpol\resources\views\catalog\index.blade.php ENDPATH**/ ?>