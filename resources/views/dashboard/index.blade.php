@extends('layouts.app')

@section('title', 'Dashboard | SIMPEG-ASSET')

@section('content')
    {{-- Hero Banner --}}
    <div class="hero-banner rounded-4 p-4 p-md-5 mb-4 position-relative overflow-hidden">
        <div class="position-relative z-1">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="badge text-bg-light text-primary mb-2 fw-semibold">
                        <i class="bi bi-stars me-1"></i>Selamat Datang
                    </span>
                    <h1 class="text-white fw-bold mb-1" style="font-size: 1.75rem;">
                        {{ auth()->user()->name ?? 'Pengguna' }} 👋
                    </h1>
                    <p class="text-white-50 mb-0">
                        Ringkasan data Kepegawaian & Manajemen Aset Bakesbangpol pada {{ now()->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('employees.create') }}" class="btn btn-light btn-sm fw-semibold">
                        <i class="bi bi-person-plus me-1"></i>Tambah Pegawai
                    </a>
                    <a href="{{ route('assets.create') }}" class="btn btn-outline-light btn-sm fw-semibold">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Aset
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Primary Statistic Cards Row 1 --}}
    <div class="row g-3 mb-3">
        @php
            $cards = [
                [
                    'label' => 'Total Pegawai',
                    'value' => $statistics['employees'],
                    'icon' => 'people',
                    'route' => 'employees.index',
                    'gradient' => 'linear-gradient(135deg, #4e7cff, #6ea8ff)',
                ],
                [
                    'label' => 'Total Aset',
                    'value' => $statistics['total_assets'],
                    'icon' => 'box-seam',
                    'route' => 'assets.index',
                    'gradient' => 'linear-gradient(135deg, #22c55e, #4ade80)',
                ],
                [
                    'label' => 'Aset Aktif / Tersedia',
                    'value' => $statistics['active_assets'],
                    'icon' => 'check-circle',
                    'route' => 'assets.index',
                    'gradient' => 'linear-gradient(135deg, #06b6d4, #22d3ee)',
                ],
                [
                    'label' => 'Aset Dalam Perbaikan',
                    'value' => $statistics['in_repair_assets'],
                    'icon' => 'tools',
                    'route' => 'assets.index',
                    'gradient' => 'linear-gradient(135deg, #f59e0b, #fbbf24)',
                ],
                [
                    'label' => 'Aset Rusak',
                    'value' => $statistics['damaged_assets'],
                    'icon' => 'exclamation-triangle',
                    'route' => 'assets.index',
                    'gradient' => 'linear-gradient(135deg, #ef4444, #f87171)',
                ],
            ];
        @endphp
        @foreach ($cards as $card)
            <div class="col-12 col-sm-6 col-xl">
                <a href="{{ route($card['route']) }}" class="text-decoration-none">
                    <div class="stat-card rounded-4 p-3 h-100 d-flex align-items-center gap-3 cursor-pointer" style="background: {{ $card['gradient'] }};">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-{{ $card['icon'] }} fs-3 text-white"></i>
                        </div>
                        <div>
                            <div class="stat-value fs-4 fw-bold text-white">{{ $card['value'] }}</div>
                            <div class="stat-label small text-white-50">{{ $card['label'] }}</div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Asset Metrics Row 2 (10 Years Disposal, Maintenance, Vehicles) --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <a href="{{ route('assets.deletable') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 rounded-4 p-3 bg-danger text-white h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-semibold"><i class="bi bi-trash3 me-1"></i>Aset Umur >= 10 Thn</span>
                        <span class="badge bg-white text-danger font-monospace">Disposal</span>
                    </div>
                    <div class="fs-3 fw-bold">{{ $statistics['aged_assets'] }} <small class="fs-6 fw-normal">Unit</small></div>
                    <small class="text-white-50">Dapat Diproses Penghapusan</small>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('assets.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 rounded-4 p-3 bg-primary text-white h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-semibold"><i class="bi bi-wrench me-1"></i>Total Pemeliharaan</span>
                        <span class="badge bg-white text-primary font-monospace">Maintenance</span>
                    </div>
                    <div class="fs-3 fw-bold">{{ $statistics['total_maintenances'] }} <small class="fs-6 fw-normal">Kegiatan</small></div>
                    <small class="text-white-50">Total Biaya: Rp {{ number_format((float)$statistics['total_maintenance_cost'], 0, ',', '.') }}</small>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('assets.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 rounded-4 p-3 bg-dark text-white h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-semibold"><i class="bi bi-car-front me-1"></i>Total Kendaraan Dinas</span>
                        <span class="badge bg-secondary font-monospace">Fleet</span>
                    </div>
                    <div class="fs-3 fw-bold">{{ $statistics['total_vehicles'] }} <small class="fs-6 fw-normal">Unit</small></div>
                    <small class="text-white-50">Dalam Perbaikan: {{ $statistics['vehicles_in_repair'] }} Unit</small>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('reviews.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 rounded-4 p-3 bg-secondary text-white h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-semibold"><i class="bi bi-file-earmark-text me-1"></i>Form Ulasan PPPK</span>
                        <span class="badge bg-white text-secondary font-monospace">Form</span>
                    </div>
                    <div class="fs-3 fw-bold">{{ $statistics['total_reviews'] }} <small class="fs-6 fw-normal">Laporan</small></div>
                    <small class="text-white-50">Laporan evaluasi pegawai PPPK</small>
                </div>
            </a>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-7">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3 pb-0">
                    <div>
                        <h2 class="h6 mb-0 fw-bold"><i class="bi bi-people me-2 text-primary"></i>Pegawai per Unit Kerja</h2>
                        <small class="text-muted">Distribusi pegawai berdasarkan unit kerja / bidang</small>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="employeesByDepartmentChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-5">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h2 class="h6 mb-0 fw-bold"><i class="bi bi-boxes me-2 text-success"></i>Aset per Kategori</h2>
                    <small class="text-muted">Komposisi aset berdasarkan kategori master</small>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="assetsByCategoryChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Tables --}}
    <div class="row g-3">
        <div class="col-12 col-xl-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                    <h2 class="h6 mb-0 fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i>Pegawai Terbaru</h2>
                    <a href="{{ route('employees.index') }}" class="small text-decoration-none fw-semibold">
                        Lihat semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Nama</th>
                                    <th>NIP</th>
                                    <th>Unit Kerja</th>
                                    <th class="text-end pe-4">Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentEmployees as $employee)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar rounded-circle d-flex align-items-center justify-content-center text-white fw-bold text-uppercase" style="background: linear-gradient(135deg, #4e7cff, #6ea8ff);">
                                                    {{ substr($employee->full_name ?? '?', 0, 1) }}
                                                </div>
                                                <span class="fw-medium">{{ $employee->full_name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-muted">{{ $employee->employee_number ?? '-' }}</td>
                                        <td>{{ $employee->department?->name ?? '-' }}</td>
                                        <td class="text-end pe-4">
                                            <span class="badge rounded-pill text-bg-light border">{{ $employee->position?->name ?? '-' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox d-block fs-3 mb-2"></i>Belum ada data pegawai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                    <h2 class="h6 mb-0 fw-bold"><i class="bi bi-box-seam me-2 text-success"></i>Aset Terbaru</h2>
                    <a href="{{ route('assets.index') }}" class="small text-decoration-none fw-semibold">
                        Lihat semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Nama Barang</th>
                                    <th>Kode</th>
                                    <th>Unit</th>
                                    <th class="text-end pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentAssets as $asset)
                                    <tr>
                                        <td class="ps-4 fw-medium">{{ $asset->nama_barang ?? ($asset->category ?? '-') }}</td>
                                        <td class="text-muted">{{ $asset->asset_code ?? '-' }}</td>
                                        <td>{{ $asset->jumlah_unit ?? 1 }}</td>
                                        <td class="text-end pe-4">
                                            <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle">
                                                {{ $asset->status ?? 'Aktif' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox d-block fs-3 mb-2"></i>Belum ada data aset.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .hero-banner {
            background: linear-gradient(135deg, #2563eb 0%, #4e7cff 50%, #7c9cff 100%);
            box-shadow: 0 10px 30px rgba(37, 99, 235, .25);
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -10%;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -70%;
            right: 15%;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
        }

        .stat-card {
            background: var(--card-gradient, linear-gradient(135deg, #4e7cff, #6ea8ff));
            box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, .15);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, .2);
            backdrop-filter: blur(4px);
            flex-shrink: 0;
        }

        .avatar {
            width: 34px;
            height: 34px;
            font-size: .8rem;
            flex-shrink: 0;
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const empCanvas = document.getElementById('employeesByDepartmentChart');
        const empCtx = empCanvas.getContext('2d');
        const empCounts = {!! json_encode($employeesByDepartment->pluck('employees_count')) !!};
        const empLabels = {!! json_encode($employeesByDepartment->pluck('name')) !!};

        const barColors = ['#4e7cff', '#06b6d4', '#22c55e', '#f59e0b', '#a855f7', '#ef4444', '#64748b'];
        const barGradients = empCounts.map((_, i) => {
            const g = empCtx.createLinearGradient(0, 0, 0, 300);
            g.addColorStop(0, barColors[i % barColors.length]);
            g.addColorStop(1, barColors[i % barColors.length] + '66');
            return g;
        });

        new Chart(empCanvas, {
            type: 'bar',
            data: {
                labels: empLabels,
                datasets: [{
                    label: 'Pegawai',
                    data: empCounts,
                    backgroundColor: barGradients,
                    borderRadius: 10,
                    borderSkipped: false,
                    maxBarThickness: 46,
                    hoverBorderColor: '#1e293b',
                    hoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1200,
                    easing: 'easeOutBounce'
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#fff',
                        bodyColor: '#cbd5e1',
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: (ctx) => ` ${ctx.parsed.y} pegawai`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#64748b'
                        },
                        grid: {
                            color: 'rgba(0,0,0,.06)',
                            drawTicks: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#475569',
                            font: {
                                size: 11,
                                weight: '600'
                            }
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('assetsByCategoryChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($assetsByCategory->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($assetsByCategory->pluck('assets_count')) !!},
                    backgroundColor: ['#4e7cff', '#22c55e', '#06b6d4', '#f59e0b', '#ef4444', '#64748b'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 12
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 8
                    }
                }
            }
        });
    </script>
@endpush
