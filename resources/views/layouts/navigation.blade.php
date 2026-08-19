<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-3 shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            <i class="bi bi-shield-check me-1"></i>SIMPEG-ASSET
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                </li>
                @if (auth()->user()
                        ?->hasAnyRole(['Administrator', 'HR / Kepegawaian']))
                    <li class="nav-item">
                        <a href="{{ route('employees.index') }}"
                            class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                            <i class="bi bi-people me-1"></i>Pegawai
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('departments.index') }}"
                            class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                            <i class="bi bi-building me-1"></i>Unit Kerja
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('assets.index') }}"
                            class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam me-1"></i>Aset
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('catalog.index') }}"
                            class="nav-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}">
                            <i class="bi bi-journal-bookmark me-1"></i>Katalog Barang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reviews.index') }}"
                            class="nav-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}">
                            <i class="bi bi-file-text me-1"></i>Form PPPK
                        </a>
                    </li>
                @endif
            </ul>
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <span class="navbar-text text-white me-2">
                        <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name ?? 'Guest' }}
                    </span>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light" title="Logout">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
