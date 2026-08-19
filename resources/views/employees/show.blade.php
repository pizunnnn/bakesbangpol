@extends('layouts.app')

@section('title', 'Detail & Riwayat Pegawai - ' . $employee->full_name)

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold text-primary">Detail & Riwayat Kepegawaian</h1>
            <p class="text-muted mb-0">Informasi terintegrasi data kepegawaian <strong>{{ $employee->full_name }}</strong>.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning px-3 py-2">
                <i class="bi bi-pencil me-1"></i>Edit Data Pegawai
            </a>
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary px-3 py-2">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- AUTOMATED ELIGIBILITY & RETIREMENT NOTIFICATION BANNERS -->
    @if ($employee->is_sudah_pensiun)
        <div class="alert alert-danger shadow-sm rounded-4 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-octagon-fill fs-2 me-3"></i>
                <div>
                    <h5 class="fw-bold mb-1">Status Pensiun (Usia 58 Tahun)</h5>
                    <p class="mb-0 small">Pegawai telah mencapai/melewati usia pensiun (58 Tahun). Tanggal Pensiun Otomatis: <strong>{{ $employee->tanggal_pensiun_otomatis ? $employee->tanggal_pensiun_otomatis->format('d F Y') : '-' }}</strong>.</p>
                </div>
            </div>
            <span class="badge bg-danger fs-6 px-3 py-2">Pensiun BUP</span>
        </div>
    @endif

    @if ($employee->is_eligible_kgb)
        <div class="alert alert-warning shadow-sm rounded-4 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-cash-coin fs-2 me-3 text-warning-emphasis"></i>
                <div>
                    <h5 class="fw-bold mb-1 text-dark">Eligible Kenaikan Gaji Berkala (KGB 2 Tahun)</h5>
                    <p class="mb-0 small text-dark">Pegawai telah memenuhi syarat 2 tahun kerja sejak KGB/TMT terakhir (Jadwal: {{ $employee->tanggal_kgb_berikutnya ? $employee->tanggal_kgb_berikutnya->format('d/m/Y') : '-' }}). Tambahkan ke riwayat tanpa mengubah data utama.</p>
                </div>
            </div>
            <form action="{{ route('employees.salaries.auto', $employee) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning fw-bold px-3 py-2 text-dark shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i>Proses KGB Otomatis (Masuk History)
                </button>
            </form>
        </div>
    @endif

    @if ($employee->is_eligible_kenaikan_pangkat)
        <div class="alert alert-info shadow-sm rounded-4 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-award-fill fs-2 me-3 text-info-emphasis"></i>
                <div>
                    <h5 class="fw-bold mb-1 text-dark">Eligible Kenaikan Pangkat (4 Tahun)</h5>
                    <p class="mb-0 small text-dark">Pegawai telah memenuhi masa kerja 4 tahun sejak pangkat/TMT terakhir (Jadwal: {{ $employee->tanggal_kenaikan_pangkat_berikutnya ? $employee->tanggal_kenaikan_pangkat_berikutnya->format('d/m/Y') : '-' }}). Catat ke riwayat kenaikan pangkat.</p>
                </div>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-3 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addAutoRankModal">
                <i class="bi bi-award me-1"></i>Proses Kenaikan Pangkat
            </button>
        </div>
    @endif

    <!-- Profile Header Card -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    <div class="avatar-box bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 100px; height: 100px; font-size: 40px; font-weight: bold;">
                        {{ strtoupper(substr($employee->full_name, 0, 1)) }}
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <div>
                            <h2 class="h4 fw-bold mb-1">{{ $employee->full_name }}</h2>
                            <p class="text-muted mb-0">
                                NIP / NIK: <strong>{{ $employee->employee_number ?: '-' }}</strong> | 
                                Status: 
                                @if ($employee->status_pegawai === 'PNS')
                                    <span class="badge bg-primary">PNS</span>
                                @elseif ($employee->status_pegawai === 'PPPK')
                                    <span class="badge bg-success">PPPK</span>
                                @elseif ($employee->status_pegawai === 'PPPK Paruh Waktu')
                                    <span class="badge bg-info">PPPK Paruh Waktu</span>
                                @else
                                    <span class="badge bg-secondary">{{ $employee->status_pegawai ?: '-' }}</span>
                                @endif
                                | Usia: <strong>{{ $employee->usia }} Tahun</strong>
                            </p>
                        </div>
                        <div>
                            @if ($employee->employment_status === 'active')
                                <span class="badge bg-success-subtle text-success fs-6 border border-success-subtle px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i>Status Aktif
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger fs-6 border border-danger-subtle px-3 py-2">
                                    <i class="bi bi-x-circle me-1"></i>Pensiun / Tidak Aktif
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3 mt-1 bg-light rounded-3 p-3">
                        <div class="col-md-3">
                            <small class="text-muted d-block"><i class="bi bi-building me-1"></i>Unit Kerja / Bidang</small>
                            <span class="fw-semibold">{{ $employee->unit_kerja ?: $employee->department->name ?? '-' }}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block"><i class="bi bi-briefcase me-1"></i>Jabatan</small>
                            <span class="fw-semibold">{{ $employee->position->name ?? '-' }}</span>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted d-block"><i class="bi bi-award me-1"></i>Pangkat / Gol.</small>
                            <span class="fw-semibold">{{ $employee->pangkat_golongan ?: '-' }}</span>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted d-block"><i class="bi bi-hourglass-split me-1"></i>Masa Kerja</small>
                            <span class="fw-bold text-primary">{{ $employee->masa_kerja }}</span>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted d-block"><i class="bi bi-calendar-event me-1"></i>Tgl Pensiun (Usia 58)</small>
                            <span class="fw-bold text-danger">{{ $employee->tanggal_pensiun_otomatis ? $employee->tanggal_pensiun_otomatis->format('d/m/Y') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Integrated Tabs Navigation -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-bottom-0 rounded-top-4 pt-3 px-4">
            <ul class="nav nav-tabs card-header-tabs" id="historyTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold" id="tab-profile-btn" data-bs-toggle="tab" data-bs-target="#tab-profile" type="button" role="tab"><i class="bi bi-person me-1"></i>Profil Detail</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab-training-btn" data-bs-toggle="tab" data-bs-target="#tab-training" type="button" role="tab"><i class="bi bi-journal-check me-1"></i>Riwayat Pelatihan ({{ $employee->trainings->count() }})</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab-salary-btn" data-bs-toggle="tab" data-bs-target="#tab-salary" type="button" role="tab">
                        <i class="bi bi-cash-stack me-1"></i>Gaji Berkala ({{ $employee->salaryHistories->count() }})
                        @if($employee->is_eligible_kgb)<span class="badge bg-warning text-dark ms-1">Eligible</span>@endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab-rank-btn" data-bs-toggle="tab" data-bs-target="#tab-rank" type="button" role="tab">
                        <i class="bi bi-award me-1"></i>Kenaikan Pangkat ({{ $employee->rankHistories->count() }})
                        @if($employee->is_eligible_kenaikan_pangkat)<span class="badge bg-info text-dark ms-1">Eligible</span>@endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab-position-btn" data-bs-toggle="tab" data-bs-target="#tab-position" type="button" role="tab"><i class="bi bi-diagram-3 me-1"></i>Jabatan & Unit Kerja ({{ $employee->positionHistories->count() }})</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab-retirement-btn" data-bs-toggle="tab" data-bs-target="#tab-retirement" type="button" role="tab">
                        <i class="bi bi-box-arrow-right me-1"></i>Informasi Pensiun ({{ $employee->retirements->count() }})
                        @if($employee->is_sudah_pensiun)<span class="badge bg-danger ms-1">Pensiun</span>@endif
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-4">
            <div class="tab-content" id="historyTabsContent">
                
                <!-- TAB PROFIL DETAIL -->
                <div class="tab-pane fade show active" id="tab-profile" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Informasi Biodata</h6>
                            <table class="table table-borderless table-sm">
                                <tr><td class="text-muted" style="width: 170px;">Tempat, Tgl Lahir</td><td>: {{ $employee->birth_place ?: '-' }}, {{ $employee->birth_date ? $employee->birth_date->format('d F Y') : '-' }}</td></tr>
                                <tr><td class="text-muted">Usia Pegawai Saat Ini</td><td>: <strong>{{ $employee->usia }} Tahun</strong></td></tr>
                                <tr><td class="text-muted">Jenis Kelamin</td><td>: {{ $employee->gender == 'male' ? 'Laki-laki' : ($employee->gender == 'female' ? 'Perempuan' : '-') }}</td></tr>
                                <tr><td class="text-muted">No. Telepon</td><td>: {{ $employee->phone ?: '-' }}</td></tr>
                                <tr><td class="text-muted">Email</td><td>: {{ $employee->email ?: '-' }}</td></tr>
                                <tr><td class="text-muted">Alamat</td><td>: {{ $employee->address ?: '-' }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Informasi Kepegawaian & Otomatisasi</h6>
                            <table class="table table-borderless table-sm">
                                <tr><td class="text-muted" style="width: 170px;">TMT Bergabung</td><td>: {{ $employee->join_date ? $employee->join_date->format('d F Y') : '-' }}</td></tr>
                                <tr><td class="text-muted">Status Pegawai</td><td>: <strong>{{ $employee->status_pegawai }}</strong></td></tr>
                                <tr><td class="text-muted">Masa Kerja (TMT)</td><td>: <span class="badge bg-primary fs-6">{{ $employee->masa_kerja }}</span> ({{ $employee->masa_kerja_tahun }} Thn {{ $employee->masa_kerja_bulan }} Bln)</td></tr>
                                <tr><td class="text-muted">Jadwal KGB 2 Thn Berikutnya</td><td>: {{ $employee->tanggal_kgb_berikutnya ? $employee->tanggal_kgb_berikutnya->format('d F Y') : '-' }} @if($employee->is_eligible_kgb)<span class="badge bg-warning text-dark">Eligible</span>@endif</td></tr>
                                <tr><td class="text-muted">Jadwal Pangkat 4 Thn Berikutnya</td><td>: {{ $employee->tanggal_kenaikan_pangkat_berikutnya ? $employee->tanggal_kenaikan_pangkat_berikutnya->format('d F Y') : '-' }} @if($employee->is_eligible_kenaikan_pangkat)<span class="badge bg-info text-dark">Eligible</span>@endif</td></tr>
                                <tr><td class="text-muted">Tanggal Pensiun (58 Thn)</td><td>: <span class="badge bg-danger fs-6">{{ $employee->tanggal_pensiun_otomatis ? $employee->tanggal_pensiun_otomatis->format('d F Y') : '-' }}</span> @if($employee->is_sudah_pensiun)<span class="badge bg-danger">Sudah Usia Pensiun</span>@endif</td></tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB RIWAYAT PELATIHAN -->
                <div class="tab-pane fade" id="tab-training" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-journal-text me-1"></i>Daftar Riwayat Pelatihan / Diklat</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTrainingModal">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Pelatihan
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Pelatihan</th>
                                    <th>Jenis / Penyelenggara</th>
                                    <th>Tanggal pelaksanaan</th>
                                    <th>Lokasi</th>
                                    <th>No. Sertifikat</th>
                                    <th>Dokumen Sertifikat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->trainings as $tr)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $tr->nama_pelatihan }}</div>
                                            @if($tr->keterangan)<small class="text-muted">{{ $tr->keterangan }}</small>@endif
                                        </td>
                                        <td>
                                            <div><span class="badge bg-secondary">{{ $tr->jenis_pelatihan ?: 'Umum' }}</span></div>
                                            <small class="text-muted">{{ $tr->penyelenggara ?: '-' }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $tr->tanggal_mulai ? $tr->tanggal_mulai->format('d/m/Y') : '-' }} s/d {{ $tr->tanggal_selesai ? $tr->tanggal_selesai->format('d/m/Y') : '-' }}</small>
                                        </td>
                                        <td>{{ $tr->lokasi ?: '-' }}</td>
                                        <td><small class="fw-semibold">{{ $tr->nomor_sertifikat ?: '-' }}</small></td>
                                        <td>
                                            @if($tr->file_sertifikat)
                                                <a href="{{ asset('storage/' . $tr->file_sertifikat) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-file-earmark-pdf me-1"></i>Unduh Sertifikat
                                                </a>
                                            @else
                                                <span class="text-muted small">Tidak ada file</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('employees.trainings.destroy', $tr) }}" method="POST" onsubmit="return confirm('Hapus riwayat pelatihan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada riwayat pelatihan untuk pegawai ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB GAJI BERKALA -->
                <div class="tab-pane fade" id="tab-salary" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-cash-stack me-1"></i>Riwayat Gaji Berkala (KJB 2 Tahun)</h6>
                        <div class="d-flex gap-2">
                            @if($employee->is_eligible_kgb)
                                <form action="{{ route('employees.salaries.auto', $employee) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning text-dark fw-bold">
                                        <i class="bi bi-magic me-1"></i>Proses KGB Otomatis (2 Thn)
                                    </button>
                                </form>
                            @endif
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSalaryModal">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Manual History Gaji
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>TMT Berlaku</th>
                                    <th>Gaji Pokok</th>
                                    <th>Pangkat / Golongan</th>
                                    <th>Nomor SK</th>
                                    <th>Keterangan</th>
                                    <th>Dokumen SK</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->salaryHistories as $sal)
                                    <tr>
                                        <td><strong>{{ $sal->tanggal_mulai_berlaku ? $sal->tanggal_mulai_berlaku->format('d F Y') : '-' }}</strong></td>
                                        <td class="fw-bold text-success">Rp {{ number_format((float)$sal->gaji_pokok, 0, ',', '.') }}</td>
                                        <td>{{ $sal->pangkat_golongan ?: '-' }}</td>
                                        <td>{{ $sal->nomor_sk ?: '-' }}</td>
                                        <td><small class="text-muted">{{ $sal->keterangan ?: '-' }}</small></td>
                                        <td>
                                            @if($sal->dokumen_sk)
                                                <a href="{{ asset('storage/' . $sal->dokumen_sk) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-file-earmark-pdf me-1"></i>SK Gaji
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('employees.salaries.destroy', $sal) }}" method="POST" onsubmit="return confirm('Hapus riwayat gaji berkala ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada riwayat gaji berkala.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB KENAIKAN PANGKAT -->
                <div class="tab-pane fade" id="tab-rank" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-award me-1"></i>Riwayat Kenaikan Pangkat (4 Tahun)</h6>
                        <div class="d-flex gap-2">
                            @if($employee->is_eligible_kenaikan_pangkat)
                                <button type="button" class="btn btn-sm btn-info text-dark fw-bold" data-bs-toggle="modal" data-bs-target="#addAutoRankModal">
                                    <i class="bi bi-magic me-1"></i>Proses Kenaikan Pangkat (4 Thn)
                                </button>
                            @endif
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addRankModal">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Manual Kenaikan Pangkat
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Pangkat Lama</th>
                                    <th>Pangkat Baru</th>
                                    <th>TMT Kenaikan</th>
                                    <th>Nomor SK</th>
                                    <th>Keterangan</th>
                                    <th>Dokumen SK</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->rankHistories as $rnk)
                                    <tr>
                                        <td class="text-muted">{{ $rnk->pangkat_lama ?: '-' }}</td>
                                        <td><strong class="text-primary">{{ $rnk->pangkat_baru }}</strong></td>
                                        <td>{{ $rnk->tanggal_kenaikan ? $rnk->tanggal_kenaikan->format('d F Y') : '-' }}</td>
                                        <td>{{ $rnk->nomor_sk ?: '-' }}</td>
                                        <td><small class="text-muted">{{ $rnk->keterangan ?: '-' }}</small></td>
                                        <td>
                                            @if($rnk->dokumen_sk)
                                                <a href="{{ asset('storage/' . $rnk->dokumen_sk) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-file-earmark-pdf me-1"></i>SK Pangkat
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('employees.ranks.destroy', $rnk) }}" method="POST" onsubmit="return confirm('Hapus riwayat kenaikan pangkat ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada riwayat kenaikan pangkat.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB JABATAN & UNIT KERJA -->
                <div class="tab-pane fade" id="tab-position" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-diagram-3 me-1"></i>Riwayat Jabatan & Unit Kerja</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPositionModal">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Riwayat Jabatan
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Jabatan</th>
                                    <th>Unit Kerja</th>
                                    <th>TMT Mulai</th>
                                    <th>TMT Selesai</th>
                                    <th>Nomor SK</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->positionHistories as $pos)
                                    <tr>
                                        <td><strong>{{ $pos->nama_jabatan }}</strong></td>
                                        <td>{{ $pos->unit_kerja ?: '-' }}</td>
                                        <td>{{ $pos->tanggal_mulai ? $pos->tanggal_mulai->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $pos->tanggal_selesai ? $pos->tanggal_selesai->format('d/m/Y') : 'Sekarang' }}</td>
                                        <td>{{ $pos->nomor_sk ?: '-' }}</td>
                                        <td><small class="text-muted">{{ $pos->keterangan ?: '-' }}</small></td>
                                        <td class="text-center">
                                            <form action="{{ route('employees.positions.destroy', $pos) }}" method="POST" onsubmit="return confirm('Hapus riwayat jabatan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada riwayat jabatan & unit kerja.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB INFORMASI PENSIUN -->
                <div class="tab-pane fade" id="tab-retirement" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-box-arrow-right me-1"></i>Informasi & Otomatisasi Pensiun (58 Tahun)</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addRetirementModal">
                            <i class="bi bi-plus-lg me-1"></i>Catat Data Pensiun
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal Pensiun</th>
                                    <th>Status / Proses Pensiun</th>
                                    <th>Nomor SK</th>
                                    <th>Keterangan</th>
                                    <th>Dokumen SK</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->retirements as $ret)
                                    <tr>
                                        <td><strong class="text-danger">{{ $ret->tanggal_pensiun ? $ret->tanggal_pensiun->format('d F Y') : '-' }}</strong></td>
                                        <td><span class="badge bg-warning text-dark fs-6">{{ $ret->status_pensiun }}</span></td>
                                        <td>{{ $ret->nomor_sk ?: '-' }}</td>
                                        <td><small class="text-muted">{{ $ret->keterangan ?: '-' }}</small></td>
                                        <td>
                                            @if($ret->dokumen_sk)
                                                <a href="{{ asset('storage/' . $ret->dokumen_sk) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-file-earmark-pdf me-1"></i>SK Pensiun
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('employees.retirements.destroy', $ret) }}" method="POST" onsubmit="return confirm('Hapus data pensiun ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada catatan informasi/proses pensiun pegawai ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL PROSES OTOMATIS KENAIKAN PANGKAT (4 THN) -->
    <div class="modal fade" id="addAutoRankModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('employees.ranks.auto', $employee) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-info text-dark">
                        <h5 class="modal-title"><i class="bi bi-award-fill me-2"></i>Proses Kenaikan Pangkat Otomatis (4 Tahun)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Pangkat Lama</label>
                            <input type="text" class="form-control" value="{{ $employee->pangkat_golongan ?: '-' }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Tanggal Kenaikan (TMT 4 Thn)</label>
                            <input type="text" class="form-control" value="{{ $employee->tanggal_kenaikan_pangkat_berikutnya ? $employee->tanggal_kenaikan_pangkat_berikutnya->format('d F Y') : date('d F Y') }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Pangkat / Golongan Baru <span class="text-danger">*</span></label>
                            <input type="text" name="pangkat_baru" class="form-control" placeholder="Contoh: Penata / III/c" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info fw-bold text-dark"><i class="bi bi-check-lg me-1"></i>Simpan ke History Kenaikan Pangkat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH PELATIHAN -->
    <div class="modal fade" id="addTrainingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('employees.trainings.store', $employee) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-journal-plus me-2"></i>Tambah Riwayat Pelatihan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Nama Pelatihan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_pelatihan" class="form-control" required placeholder="Contoh: Diklat Penata Layanan Publik">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Jenis Pelatihan</label>
                                <input type="text" name="jenis_pelatihan" class="form-control" placeholder="Diklat Strukur / Teknis / Workshop">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Penyelenggara</label>
                                <input type="text" name="penyelenggara" class="form-control" placeholder="Lembaga penyelenggara">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Lokasi</label>
                                <input type="text" name="lokasi" class="form-control" placeholder="Kota / Tempat pelaksanaan">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Nomor Sertifikat</label>
                                <input type="text" name="nomor_sertifikat" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Upload File Sertifikat (PDF/Gambar)</label>
                            <input type="file" name="file_sertifikat" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Pelatihan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH GAJI BERKALA -->
    <div class="modal fade" id="addSalaryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('employees.salaries.store', $employee) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-cash-coin me-2"></i>Tambah History Gaji Berkala</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Tanggal Mulai Berlaku (TMT) <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai_berlaku" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Gaji Pokok Baru (Rp)</label>
                            <input type="number" step="0.01" name="gaji_pokok" class="form-control" placeholder="Contoh: 3500000">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Pangkat / Golongan Terkait</label>
                            <input type="text" name="pangkat_golongan" class="form-control" value="{{ $employee->pangkat_golongan }}" placeholder="Penata Muda / III/a">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Nomor SK</label>
                            <input type="text" name="nomor_sk" class="form-control" placeholder="Nomor SK Gaji Berkala">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Upload Dokumen SK (PDF/Gambar)</label>
                            <input type="file" name="dokumen_sk" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan History Gaji</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH KENAIKAN PANGKAT -->
    <div class="modal fade" id="addRankModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('employees.ranks.store', $employee) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-award-fill me-2"></i>Tambah Kenaikan Pangkat</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Pangkat / Golongan Lama</label>
                            <input type="text" name="pangkat_lama" class="form-control" value="{{ $employee->pangkat_golongan }}" placeholder="Pangkat sebelumnya">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Pangkat / Golongan Baru <span class="text-danger">*</span></label>
                            <input type="text" name="pangkat_baru" class="form-control" required placeholder="Contoh: Penata / III/c">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Tanggal Kenaikan (TMT) <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kenaikan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Nomor SK</label>
                            <input type="text" name="nomor_sk" class="form-control" placeholder="Nomor SK Kenaikan Pangkat">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Upload Dokumen SK (PDF/Gambar)</label>
                            <input type="file" name="dokumen_sk" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Kenaikan Pangkat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH RIWAYAT JABATAN -->
    <div class="modal fade" id="addPositionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('employees.positions.store', $employee) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-diagram-3-fill me-2"></i>Tambah Riwayat Jabatan & Unit Kerja</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Nama Jabatan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_jabatan" class="form-control" required placeholder="Nama jabatan">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Unit Kerja / Bidang</label>
                            <select name="unit_kerja" class="form-select">
                                <option value="">-- Pilih Unit Kerja --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">TMT Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_mulai" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">TMT Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control">
                                <span class="form-text">Kosongkan jika masih menjabat.</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Nomor SK</label>
                            <input type="text" name="nomor_sk" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Riwayat Jabatan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH DATA PENSIUN -->
    <div class="modal fade" id="addRetirementModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('employees.retirements.store', $employee) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-box-arrow-right me-2"></i>Catat Data / Proses Pensiun</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Tanggal Pensiun <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pensiun" class="form-control" value="{{ $employee->tanggal_pensiun_otomatis ? $employee->tanggal_pensiun_otomatis->format('Y-m-d') : '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Status / Proses Pensiun <span class="text-danger">*</span></label>
                            <select name="status_pensiun" class="form-select" required>
                                <option value="Pensiun BUP (Batas Usia 58 Tahun)">Pensiun BUP (Batas Usia 58 Tahun)</option>
                                <option value="Dalam Proses Usulan">Dalam Proses Usulan</option>
                                <option value="SK Terbit">SK Pensiun Terbit</option>
                                <option value="Pensiun Dini">Pensiun Dini</option>
                                <option value="Pensiun Janda/Duda">Pensiun Janda / Duda</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Nomor SK Pensiun</label>
                            <input type="text" name="nomor_sk" class="form-control" placeholder="Nomor SK Pensiun">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Upload Dokumen SK (PDF/Gambar)</label>
                            <input type="file" name="dokumen_sk" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Data Pensiun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
