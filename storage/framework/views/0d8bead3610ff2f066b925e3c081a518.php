<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-3 shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?php echo e(route('dashboard')); ?>">
            <i class="bi bi-shield-check me-1"></i>SIMPEG-ASSET
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="<?php echo e(route('dashboard')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                </li>
                <?php if(auth()->user()
                        ?->hasAnyRole(['Administrator', 'HR / Kepegawaian'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('employees.index')); ?>"
                            class="nav-link <?php echo e(request()->routeIs('employees.*') ? 'active' : ''); ?>">
                            <i class="bi bi-people me-1"></i>Pegawai
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('assets.index')); ?>"
                            class="nav-link <?php echo e(request()->routeIs('assets.*') ? 'active' : ''); ?>">
                            <i class="bi bi-box-seam me-1"></i>Aset
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('catalog.index')); ?>"
                            class="nav-link <?php echo e(request()->routeIs('catalog.*') ? 'active' : ''); ?>">
                            <i class="bi bi-journal-bookmark me-1"></i>Katalog Barang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('reviews.index')); ?>"
                            class="nav-link <?php echo e(request()->routeIs('reviews.*') ? 'active' : ''); ?>">
                            <i class="bi bi-file-text me-1"></i>Form PPPK
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <span class="navbar-text text-white me-2">
                        <i class="bi bi-person-circle me-1"></i><?php echo e(auth()->user()->name ?? 'Guest'); ?>

                    </span>
                </li>
                <li class="nav-item">
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-outline-light" title="Logout">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH D:\BakesBangPol\KesbangPol\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>