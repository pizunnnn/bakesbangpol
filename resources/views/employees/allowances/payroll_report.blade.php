<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pegawai Penerima Tunjangan - Bakesbangpol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #0f172a;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
        }
        .main-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07);
        }
        .header-title {
            text-align: center;
            border-bottom: 2px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header-title h4 {
            font-size: 15px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-title h5 {
            font-size: 13px;
            font-weight: bold;
            margin: 3px 0;
            text-transform: uppercase;
        }
        .header-title p {
            font-size: 11px;
            margin: 0;
            color: #475569;
        }
        .payroll-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        .payroll-table th, .payroll-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .payroll-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            color: #0f172a;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-start { text-align: left; }
        .fw-bold { font-weight: bold; }
        .stat-badge-card {
            border-radius: 10px;
            padding: 12px 16px;
            background: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        /* ATURAN KHUSUS CETAK (PRINT PREVIEW / PRINTER) */
        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm 8mm 10mm 8mm;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color: #000 !important;
                box-shadow: none !important;
                text-shadow: none !important;
            }
            body {
                padding: 0 !important;
                background: #fff !important;
                color: #000 !important;
                font-size: 8.5pt !important;
            }
            /* SEMBUNYIKAN NAVIGASI, FILTER, DAN KOLOM DETAIL SAAT CETAK */
            .no-print,
            .no-print-col,
            th.no-print-col,
            td.no-print-col {
                display: none !important;
                visibility: hidden !important;
                width: 0 !important;
                padding: 0 !important;
                border: none !important;
            }
            .main-card {
                box-shadow: none !important;
                padding: 0 !important;
                background: transparent !important;
                border: none !important;
            }
            .header-title {
                border-bottom: 2px solid #000 !important;
                margin-bottom: 12px !important;
                padding-bottom: 6px !important;
            }
            .header-title h4, .header-title h5, .header-title p {
                color: #000 !important;
            }
            /* TABEL HITAM PUTIH RESMI */
            .payroll-table {
                width: 100% !important;
                border: 1px solid #000 !important;
                border-collapse: collapse !important;
                color: #000 !important;
                font-size: 8pt !important;
            }
            .payroll-table th, .payroll-table td {
                border: 1px solid #000 !important;
                color: #000 !important;
                background: transparent !important;
                padding: 4px 5px !important;
            }
            .payroll-table th {
                background-color: #f3f4f6 !important;
                font-weight: bold !important;
                color: #000 !important;
            }
            /* HILANGKAN SEMUA WARNA & KOTAK BADGE */
            .badge,
            span.badge {
                background: none !important;
                background-color: transparent !important;
                color: #000 !important;
                border: none !important;
                padding: 0 !important;
                font-size: 8pt !important;
                font-weight: normal !important;
                box-shadow: none !important;
            }
            .badge i {
                display: none !important;
            }
            .text-muted, .text-primary, .text-success, .text-danger, .text-warning, .text-info, .text-dark, .text-secondary, .text-info-emphasis, .text-warning-emphasis {
                color: #000 !important;
            }
        }
    </style>
</head>
<body>

    <div class="container-fluid max-w-7xl mx-auto">
        <!-- TOP ACTIONS (NO PRINT) -->
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Data Pegawai
            </a>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-sm btn-primary">
                    <i class="bi bi-printer me-1"></i>Cetak Daftar Penerima Tunjangan
                </button>
            </div>
        </div>

        <div class="main-card">
            <!-- TITLE HEADER -->
            <div class="header-title">
                <h4>Pemerintah Provinsi Jawa Barat</h4>
                <h5>Badan Kesatuan Bangsa dan Politik</h5>
                <p>DAFTAR PEGAWAI PENERIMA HAK TUNJANGAN KEPEGAWAIAN | BULAN : {{ strtoupper($periode) }}</p>
            </div>

            <!-- STATISTIK JUMLAH PENERIMA TUNJANGAN (NO PRINT) -->
            <div class="row g-2 mb-4 no-print">
                <div class="col-md-2 col-6">
                    <div class="stat-badge-card border-start border-4 border-primary">
                        <div class="small text-muted">Total Pegawai</div>
                        <div class="fs-5 fw-bold text-dark">{{ count($employees) }} Orang</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-badge-card border-start border-4 border-success">
                        <div class="small text-muted">Tj. Pasangan</div>
                        <div class="fs-5 fw-bold text-success">{{ $countSuamiIstri }} Orang</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-badge-card border-start border-4 border-info">
                        <div class="small text-muted">Tj. Anak</div>
                        <div class="fs-5 fw-bold text-info">{{ $countAnak }} Orang</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-badge-card border-start border-4 border-warning">
                        <div class="small text-muted">Tj. Struktural</div>
                        <div class="fs-5 fw-bold text-warning-emphasis">{{ $countStruktural }} Pejabat</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-badge-card border-start border-4 border-secondary">
                        <div class="small text-muted">Tj. Fungsional</div>
                        <div class="fs-5 fw-bold text-secondary">{{ $countFungsional }} Orang</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-badge-card border-start border-4 border-success">
                        <div class="small text-muted">Tj. Beras</div>
                        <div class="fs-5 fw-bold text-success">{{ $countBeras }} Orang</div>
                    </div>
                </div>
            </div>

            <!-- FILTER TABS & PENCARIAN (NO PRINT) -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 no-print">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('employees.payroll-report') }}" class="btn {{ empty($filterType) ? 'btn-primary' : 'btn-outline-primary' }}">
                        Semua Pegawai ({{ count($employees) }})
                    </a>
                    <a href="{{ route('employees.payroll-report', ['filter' => 'suami_istri']) }}" class="btn {{ $filterType === 'suami_istri' ? 'btn-success text-white' : 'btn-outline-success' }}">
                        Penerima Tj. Pasangan
                    </a>
                    <a href="{{ route('employees.payroll-report', ['filter' => 'anak']) }}" class="btn {{ $filterType === 'anak' ? 'btn-info text-dark' : 'btn-outline-info' }}">
                        Penerima Tj. Anak
                    </a>
                    <a href="{{ route('employees.payroll-report', ['filter' => 'struktural']) }}" class="btn {{ $filterType === 'struktural' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
                        Penerima Tj. Struktural
                    </a>
                    <a href="{{ route('employees.payroll-report', ['filter' => 'fungsional']) }}" class="btn {{ $filterType === 'fungsional' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                        Penerima Tj. Fungsional
                    </a>
                </div>

                <form action="{{ route('employees.payroll-report') }}" method="GET" class="d-flex gap-1" style="max-width: 280px;">
                    @if(!empty($filterType)) <input type="hidden" name="filter" value="{{ $filterType }}"> @endif
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama / NIP..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                    @if(!empty($search) || !empty($filterType))
                        <a href="{{ route('employees.payroll-report') }}" class="btn btn-sm btn-outline-secondary" title="Reset"><i class="bi bi-x"></i></a>
                    @endif
                </form>
            </div>

            <!-- MAIN TABLE PENERIMA TUNJANGAN -->
            <div class="table-responsive">
                <table class="payroll-table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 35px;">NO</th>
                            <th style="width: 180px;">NAMA & NIP PEGAWAI</th>
                            <th style="width: 150px;">JABATAN & UNIT KERJA</th>
                            <th style="width: 90px;">PANGKAT / GOL</th>
                            <th style="width: 90px;">STATUS JIWA</th>
                            <th style="width: 95px;">TJ. PASANGAN</th>
                            <th style="width: 95px;">TJ. ANAK</th>
                            <th style="width: 110px;">TJ. JABATAN</th>
                            <th style="width: 85px;">TJ. BERAS</th>
                            <th style="width: 130px;">NO. REKENING BJB</th>
                            <th style="width: 75px;" class="text-center no-print-col">DETAIL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $idx => $emp)
                            @php $al = $emp->allowance; @endphp
                            <tr>
                                <td class="text-center fw-bold">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $emp->full_name }}</div>
                                    <small class="text-muted font-monospace">{{ $emp->employee_number }}</small>
                                    <span class="badge bg-secondary-subtle text-secondary small ms-1">{{ $emp->status_pegawai }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $emp->position->name ?? '-' }}</div>
                                    <small class="text-muted">{{ $emp->unit_kerja ?: '-' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $emp->pangkat_golongan ?: '-' }}</span>
                                    <div class="small text-muted">{{ $al->masker ?? $emp->masa_kerja_tahun ?? 0 }} Thn</div>
                                </td>
                                <td class="text-center">
                                    <div class="fw-semibold">{{ ($al && $al->status_kawin === 'K') ? 'Kawin (K)' : 'Belum (TK)' }}</div>
                                    <small class="text-muted">Kode: <strong>{{ $al->kd_jiwa ?? '1100' }}</strong> ({{ $al->jml_jiwa ?? 1 }} Jiwa)</small>
                                </td>
                                <td class="text-center">
                                    @if($al?->has_tj_suami_istri)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            <i class="bi bi-check-circle-fill me-1"></i>Menerima
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($al?->has_tj_anak)
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                                            <i class="bi bi-people-fill me-1"></i>{{ $al->jumlah_anak_tanggungan }} Anak
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($al?->has_tj_struktural)
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                                            <i class="bi bi-award-fill me-1"></i>Struktural
                                        </span>
                                    @elseif($al?->has_tj_fungsional)
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                            <i class="bi bi-gear-fill me-1"></i>Fungsional
                                        </span>
                                    @else
                                        <span class="text-muted small">Umum</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($al?->has_tj_beras)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            <i class="bi bi-bag-check-fill me-1"></i>{{ $al->jml_jiwa ?? 1 }} Jiwa
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-monospace fw-semibold text-primary">{{ $al->nomor_rekening ?? '-' }}</div>
                                    <small class="text-muted">NPWP: {{ $al->npwp ?? '-' }}</small>
                                </td>
                                <td class="text-center no-print-col">
                                    <a href="{{ route('employees.show', ['employee' => $emp, 'tab' => 'allowance']) }}" class="btn btn-xs btn-outline-primary py-1 px-2" title="Lihat Hak Tunjangan">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">Tidak ada data pegawai yang sesuai dengan kriteria filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- FOOTER SIGNATURE -->
            <div class="row mt-4 pt-2">
                <div class="col-6">
                    <small class="text-muted">Dicetak pada: {{ date('d F Y H:i') }} WIB | SIMPEG-ASSET Bakesbangpol Jawa Barat</small>
                </div>
                <div class="col-6 text-end">
                    <div style="display: inline-block; text-align: center; width: 220px;">
                        <p class="mb-1">Bandung, {{ date('d F Y') }}</p>
                        <p class="mb-5">Pengelola Kepegawaian & Tunjangan</p>
                        <p class="fw-bold mb-0">________________________</p>
                        <p class="text-muted small">NIP. .....................................</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>