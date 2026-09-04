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
                            class="nav-link {{ (request()->routeIs('employees.*') && !request()->routeIs('employees.promotions*')) ? 'active' : '' }}">
                            <i class="bi bi-people me-1"></i>Pegawai
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('employees.promotions') }}"
                            class="nav-link {{ request()->routeIs('employees.promotions*') ? 'active' : '' }}">
                            <i class="bi bi-award me-1"></i>Kenaikan Pangkat
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
                @if (auth()->user()?->hasAnyRole(['Administrator', 'HR / Kepegawaian']))
                    @php
                        $navPromotionList = \App\Models\Employee::where('employment_status', '!=', 'inactive')
                            ->with('rankHistories')
                            ->get()
                            ->filter(fn($e) => $e->is_eligible_kenaikan_pangkat || ($e->tanggal_kenaikan_pangkat_berikutnya && $e->tanggal_kenaikan_pangkat_berikutnya->format('Y-m') === \Carbon\Carbon::now()->format('Y-m')));
                        $navPromotionCount = $navPromotionList->count();
                        $navPromotionCandidates = $navPromotionList->take(5);
                    @endphp
                    <!-- NOTIFICATION BELL DROPDOWN -->
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle position-relative text-white py-1 px-2" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi Kenaikan Pangkat">
                            <i class="bi bi-bell-fill fs-5"></i>
                            @if($navPromotionCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 9px;">
                                    {{ $navPromotionCount }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2 mt-2" aria-labelledby="notificationDropdown" style="width: 320px;">
                            <li class="dropdown-header d-flex justify-content-between align-items-center py-2 px-2 border-bottom">
                                <span class="fw-bold text-dark"><i class="bi bi-award-fill text-warning me-1"></i>Notifikasi Kenaikan Pangkat</span>
                                <span class="badge bg-danger">{{ $navPromotionCount }} Pegawai</span>
                            </li>
                            @forelse($navPromotionCandidates as $pCandidate)
                                <li>
                                    <a class="dropdown-item py-2 px-2 border-bottom rounded-2" href="{{ route('employees.show', ['employee' => $pCandidate, 'tab' => 'rank']) }}">
                                        <div class="fw-bold text-dark small text-truncate">{{ $pCandidate->full_name }}</div>
                                        <div class="text-muted small" style="font-size: 11px;">
                                            {{ $pCandidate->pangkat_golongan ?: '-' }} ➔ <span class="text-primary fw-semibold">{{ $pCandidate->pangkat_berikutnya_estimasi }}</span>
                                        </div>
                                        <div class="text-secondary" style="font-size: 10px;">
                                            <i class="bi bi-clock me-1"></i>Jatuh tempo: {{ $pCandidate->tanggal_kenaikan_pangkat_berikutnya ? $pCandidate->tanggal_kenaikan_pangkat_berikutnya->format('d/m/Y') : '-' }}
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="p-3 text-center text-muted small">
                                    <i class="bi bi-check2-circle text-success fs-4 d-block mb-1"></i>
                                    Tidak ada jadwal kenaikan pangkat bulan ini.
                                </li>
                            @endforelse
                            <li class="pt-2 px-1 text-center">
                                <a href="{{ route('employees.promotions') }}" class="btn btn-sm btn-primary w-100 py-1 fw-semibold" style="font-size: 11.5px;">
                                    <i class="bi bi-calendar3 me-1"></i>Lihat Semua Monitoring Bulanan
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
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
