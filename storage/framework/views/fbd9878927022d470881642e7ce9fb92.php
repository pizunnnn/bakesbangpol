

<?php $__env->startSection('title', 'Form Laporan Kinerja PPPK'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold">Form Laporan Kinerja PPPK</h1>
            <small class="text-muted">Badan Kesatuan Bangsa dan Politik Provinsi Jawa Barat</small>
        </div>
        <?php if($selected): ?>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?php echo e(route('reviews.print', ['periode' => $selected->id])); ?>" target="_blank"
                    class="btn btn-success fw-bold">
                    <i class="bi bi-printer me-1"></i>Preview & Cetak Laporan (PDF)
                </a>
                <form action="<?php echo e(route('reviews.destroy', $selected)); ?>" method="POST" class="d-inline"
                    onsubmit="return confirm('Yakin ingin menghapus periode <?php echo e($selected->evaluation_period); ?> beserta seluruh kegiatannya?')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger fw-bold">
                        <i class="bi bi-trash me-1"></i>Hapus Periode
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        
        <div class="col-lg-4 mb-4">
            
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white fw-bold">
                    Pilih Periode Laporan
                </div>
                <div class="card-body">
                    <?php if($periods->count()): ?>
                        <div class="list-group">
                            <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo e($selected && $selected->id === $period->id ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('reviews.index', ['periode' => $period->id])); ?>"
                                        class="text-decoration-none flex-grow-1 me-2 <?php echo e($selected && $selected->id === $period->id ? 'text-white' : 'text-dark'); ?>">
                                        <strong><?php echo e($period->nama); ?></strong>
                                        <?php if($selected && $selected->id === $period->id): ?>
                                            <i class="bi bi-check-circle-fill ms-1"></i>
                                        <?php endif; ?>
                                        <br>
                                        <small><?php echo e($period->evaluation_period); ?></small>
                                    </a>
                                    <form action="<?php echo e(route('reviews.destroy', $period)); ?>" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus periode <?php echo e($period->evaluation_period); ?> beserta seluruh kegiatannya?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            title="Hapus periode <?php echo e($period->evaluation_period); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Belum ada periode. Buat periode baru di bawah.</p>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    + Buat / Ubah Data Laporan Kinerja
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('reviews.period.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Pegawai</label>
                            <input type="text" name="nama" class="form-control form-control-sm"
                                value="<?php echo e(old('nama', $selected->nama ?? '')); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">NIPKKK</label>
                            <input type="text" name="nipkkk" class="form-control form-control-sm"
                                value="<?php echo e(old('nipkkk', $selected->nipkkk ?? '')); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control form-control-sm"
                                value="<?php echo e(old('jabatan', $selected->jabatan ?? '')); ?>" required>
                        </div>
                        <div class="row">
                            <div class="col-7 mb-3">
                                <label class="form-label small fw-bold">Periode Bulan</label>
                                <select name="periode_bulan" class="form-select form-select-sm" required>
                                    <?php $__currentLoopData = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bulan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($bulan); ?>"
                                            <?php echo e(old('periode_bulan', $selected->periode_bulan ?? '') == $bulan ? 'selected' : ''); ?>>
                                            <?php echo e($bulan); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-5 mb-3">
                                <label class="form-label small fw-bold">Tahun</label>
                                <input type="number" name="periode_tahun" class="form-control form-control-sm"
                                    value="<?php echo e(old('periode_tahun', $selected->periode_tahun ?? now()->year)); ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">PPTK (Nama)</label>
                            <input type="text" name="pptk_nama" class="form-control form-control-sm"
                                value="<?php echo e(old('pptk_nama', $selected->pptk_nama ?? '')); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">NIP PPTK</label>
                            <input type="text" name="pptk_nip" class="form-control form-control-sm"
                                value="<?php echo e(old('pptk_nip', $selected->pptk_nip ?? '')); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                            <i class="bi bi-save me-1"></i>Simpan Data Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="col-lg-8 mb-4">
            <?php if($selected): ?>
                
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white fw-bold">
                        Profile Pegawai & Pejabat Penilai (PPTK)
                    </div>
                    <div class="card-body bg-light">
                        <div class="row small">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Nama Pegawai:</strong> <?php echo e($selected->nama); ?></p>
                                <p class="mb-1"><strong>NIPKKK:</strong> <?php echo e($selected->nipkkk); ?></p>
                                <p class="mb-1"><strong>Jabatan:</strong> <?php echo e($selected->jabatan); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Periode Laporan:</strong> <?php echo e($selected->evaluation_period); ?></p>
                                <p class="mb-1"><strong>PPTK:</strong> <?php echo e($selected->pptk_nama); ?></p>
                                <p class="mb-1"><strong>NIP PPTK:</strong> <?php echo e($selected->pptk_nip); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    
                    <div class="col-md-5 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white fw-bold">
                                + Tambah Kegiatan Harian
                            </div>
                            <div class="card-body">
                                <form action="<?php echo e(route('reviews.kegiatan.store')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="pppk_review_id" value="<?php echo e($selected->id); ?>">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Tanggal Kegiatan</label>
                                        <input type="date" name="kegiatan_date" class="form-control form-control-sm"
                                            value="<?php echo e(date('Y-m-d')); ?>" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label small fw-bold">Jam Mulai</label>
                                            <input type="text" name="waktu_mulai" class="form-control form-control-sm"
                                                value="08.00" required>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label class="form-label small fw-bold">Jam Selesai</label>
                                            <input type="text" name="waktu_selesai"
                                                class="form-control form-control-sm" value="16.00" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Uraian Kegiatan</label>
                                        <textarea name="uraian" class="form-control form-control-sm" rows="4"
                                            placeholder="Jelaskan detail kegiatan kinerja yang dilakukan hari ini..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                                        <i class="bi bi-plus-lg me-1"></i>Simpan Kegiatan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-7 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white fw-bold">
                                Daftar Uraian Kegiatan (Periode <?php echo e($selected->evaluation_period); ?>)
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover table-striped m-0 align-middle small">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Hari / Tanggal</th>
                                            <th class="text-center">Waktu</th>
                                            <th>Uraian Kegiatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $selected->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td class="text-center fw-bold"><?php echo e($idx + 1); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($row->kegiatan_date)->format('d/m/Y')); ?></td>
                                                <td class="text-center"><?php echo e($row->kegiatan_time); ?></td>
                                                <td><?php echo e($row->uraian); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    Belum ada kegiatan untuk periode ini.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center text-muted py-5">
                        <i class="bi bi-file-earmark-text display-4 d-block mb-3"></i>
                        Silakan buat periode laporan terlebih dahulu di kolom kiri.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\bakesbangpol\resources\views/reviews/index.blade.php ENDPATH**/ ?>