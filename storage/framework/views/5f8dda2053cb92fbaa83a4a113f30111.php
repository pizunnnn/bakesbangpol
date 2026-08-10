

<?php $__env->startSection('title', 'Edit Aset BMD'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Edit Data Aset BMD</h1>
            <p class="text-muted mb-0"><?php echo e($asset->nama_barang ?? $asset->asset_code); ?></p>
        </div>
        <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white fw-bold rounded-4 rounded-bottom-0">
            Edit Data Aset BMD
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('assets.update', $asset)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="mb-3">
                    <label class="form-label text-muted small">Foto Aset</label>
                    <?php if($asset->photo): ?>
                        <div class="mb-2">
                            <img src="<?php echo e(asset('storage/' . $asset->photo)); ?>" alt="Foto aset" class="img-thumbnail"
                                style="max-height: 150px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="photo" id="photo"
                        class="form-control <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*">
                    <div id="photo_preview" class="mt-2"></div>
                    <div class="form-text">Kosongkan jika tidak ingin mengubah foto. Format: JPEG, PNG, JPG, GIF, atau WebP.
                        Maksimal 2MB.</div>
                    <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Bidang / Lokasi Barang</label>
                    <select name="bidang" class="form-select <?php $__errorArgs = ['bidang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">-- Pilih Bidang --</option>
                        <?php $__currentLoopData = $bidangList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b); ?>" <?php echo e(old('bidang', $asset->bidang) == $b ? 'selected' : ''); ?>>
                                <?php echo e($b); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['bidang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Kategori & Kode Barang (Permendagri)</label>
                    <div class="input-group">
                        <select name="kode_barang" id="kode_barang_select"
                            class="form-select <?php $__errorArgs = ['kode_barang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">-- Pilih Kode Barang --</option>
                            <?php $__currentLoopData = $catalogs ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catalog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($catalog->kode_barang); ?>" data-nama="<?php echo e($catalog->nama_barang); ?>"
                                    <?php echo e(old('kode_barang', $asset->kode_barang) == $catalog->kode_barang ? 'selected' : ''); ?>>
                                    <?php echo e($catalog->kode_barang); ?> - <?php echo e($catalog->nama_barang); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-outline-secondary" target="_blank"
                            title="Kelola Katalog Barang">
                            <i class="bi bi-journal-bookmark"></i>
                        </a>
                    </div>
                    <div class="form-text">
                        <a href="<?php echo e(route('catalog.create')); ?>" target="_blank">
                            <i class="bi bi-plus-circle me-1"></i>Tambah kode barang baru
                        </a>
                    </div>
                    <?php $__errorArgs = ['kode_barang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted small">No. Register</label>
                        <input type="text" name="no_register"
                            class="form-control <?php $__errorArgs = ['no_register'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('no_register', $asset->no_register)); ?>">
                        <?php $__errorArgs = ['no_register'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted small">Kondisi</label>
                        <select name="keadaan" class="form-select <?php $__errorArgs = ['keadaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="B" <?php echo e(old('keadaan', $asset->keadaan) == 'B' ? 'selected' : ''); ?>>Baik (B)
                            </option>
                            <option value="KB" <?php echo e(old('keadaan', $asset->keadaan) == 'KB' ? 'selected' : ''); ?>>Kurang
                                Baik (KB)</option>
                            <option value="RB" <?php echo e(old('keadaan', $asset->keadaan) == 'RB' ? 'selected' : ''); ?>>Rusak
                                Berat (RB)</option>
                        </select>
                        <?php $__errorArgs = ['keadaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control <?php $__errorArgs = ['nama_barang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        value="<?php echo e(old('nama_barang', $asset->nama_barang)); ?>" required>
                    <?php $__errorArgs = ['nama_barang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Merk / Tipe</label>
                    <input type="text" name="merk_tipe" class="form-control <?php $__errorArgs = ['merk_tipe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        value="<?php echo e(old('merk_tipe', $asset->merk_tipe)); ?>">
                    <?php $__errorArgs = ['merk_tipe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Spesifikasi / No. Seri / No. Polisi</label>
                    <textarea name="spesifikasi" class="form-control <?php $__errorArgs = ['spesifikasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="2"><?php echo e(old('spesifikasi', $asset->spesifikasi)); ?></textarea>
                    <?php $__errorArgs = ['spesifikasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted small">Tahun Perolehan</label>
                        <input type="number" name="tahun_perolehan"
                            class="form-control <?php $__errorArgs = ['tahun_perolehan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('tahun_perolehan', $asset->tahun_perolehan)); ?>">
                        <?php $__errorArgs = ['tahun_perolehan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted small">Cara Perolehan</label>
                        <input type="text" name="cara_perolehan"
                            class="form-control <?php $__errorArgs = ['cara_perolehan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('cara_perolehan', $asset->cara_perolehan)); ?>">
                        <?php $__errorArgs = ['cara_perolehan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-3">
                        <label class="form-label text-muted small">Jumlah Unit</label>
                        <input type="number" name="jumlah_unit"
                            class="form-control <?php $__errorArgs = ['jumlah_unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('jumlah_unit', $asset->jumlah_unit ?? 1)); ?>" min="1" required>
                        <?php $__errorArgs = ['jumlah_unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-label text-muted small">Nilai Perolehan (Rp)</label>
                        <input type="number" name="nilai_perolehan"
                            class="form-control <?php $__errorArgs = ['nilai_perolehan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('nilai_perolehan', $asset->nilai_perolehan)); ?>" required>
                        <?php $__errorArgs = ['nilai_perolehan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-label text-muted small">Umur (Thn)</label>
                        <input type="number" name="umur_ekonomis"
                            class="form-control <?php $__errorArgs = ['umur_ekonomis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('umur_ekonomis', $asset->umur_ekonomis)); ?>" required>
                        <?php $__errorArgs = ['umur_ekonomis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Status</label>
                    <select name="status" id="asset_status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="Tersedia" <?php echo e(old('status', $asset->status) == 'Tersedia' ? 'selected' : ''); ?>>
                            Tersedia
                        </option>
                        <option value="Dipinjam" <?php echo e(old('status', $asset->status) == 'Dipinjam' ? 'selected' : ''); ?>>
                            Dipinjam</option>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3" id="peminjam_field"
                    style="<?php echo e(($asset->status ?? '') === 'Dipinjam' ? '' : 'display:none;'); ?>">
                    <label class="form-label text-muted small">Dipinjam Oleh</label>
                    <select name="current_employee_id"
                        class="form-select <?php $__errorArgs = ['current_employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">-- Pilih Pegawai --</option>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($employee->id); ?>"
                                <?php echo e(old('current_employee_id', $asset->current_employee_id) == $employee->id ? 'selected' : ''); ?>>
                                <?php echo e($employee->full_name); ?>

                                <?php if($employee->unit_kerja && $employee->unit_kerja !== '-'): ?>
                                    - <?php echo e($employee->unit_kerja); ?>

                                <?php endif; ?>
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['current_employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                    <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const statusSelect = document.getElementById('asset_status');
                const peminjamField = document.getElementById('peminjam_field');

                function togglePeminjam() {
                    if (statusSelect.value === 'Dipinjam') {
                        peminjamField.style.display = 'block';
                    } else {
                        peminjamField.style.display = 'none';
                    }
                }

                statusSelect.addEventListener('change', togglePeminjam);

                const photoInput = document.getElementById('photo');
                const photoPreview = document.getElementById('photo_preview');
                if (photoInput && photoPreview) {
                    photoInput.addEventListener('change', function() {
                        photoPreview.innerHTML = '';
                        const file = photoInput.files[0];
                        if (!file) return;
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-thumbnail';
                            img.style.maxHeight = '150px';
                            photoPreview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\bakesbangpol\resources\views\assets\edit.blade.php ENDPATH**/ ?>