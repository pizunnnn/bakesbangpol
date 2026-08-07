

<?php $__env->startSection('title', 'Pengaturan'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h1 class="h4 mb-3">Company Profile</h1>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Institution Name</label>
                            <input type="text" class="form-control" value="SIMPEG-ASSET Government">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Logo</label>
                            <input type="file" class="form-control">
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <button class="btn btn-primary">Save Settings</button>
                        <button class="btn btn-outline-secondary">Dark Mode</button>
                        <button class="btn btn-outline-secondary">Light Mode</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <h2 class="h5 mb-3">Security Notes</h2>
                    <ul class="text-muted mb-0">
                        <li>CSRF protection</li>
                        <li>Role-based authorization</li>
                        <li>Activity logging</li>
                        <li>Validated profile updates</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\bakesbangpol\resources\views\settings\index.blade.php ENDPATH**/ ?>