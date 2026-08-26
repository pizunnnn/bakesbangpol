@extends('layouts.app')

@section('title', 'Data Pegawai')

@section('content')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h1 class="h4 mb-0 fw-bold text-primary">
                        <i class="bi bi-people-fill me-2"></i>Data Pegawai
                    </h1>
                    <p class="text-muted small mb-0">Kelola data pegawai PNS, PPPK, dan PPPK Paruh Waktu Bakesbangpol.</p>
                </div>
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#pdfExportModal">
                        <i class="bi bi-file-earmark-pdf me-1"></i>Cetak PDF
                    </button>
                    <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Pegawai
                    </a>
                </div>
            </div>

            <!-- Quick Filter Preset Badges -->
            <div class="mb-3 d-flex gap-1 flex-wrap align-items-center">
                <span class="small text-muted me-1"><i class="bi bi-funnel"></i> Quick Filter Cetak:</span>
                <a href="{{ route('employees.export-pdf', ['status_pegawai' => 'PNS']) }}" target="_blank" class="badge bg-primary text-decoration-none px-2 py-1">
                    <i class="bi bi-printer me-1"></i>Cetak PNS
                </a>
                <a href="{{ route('employees.export-pdf', ['status_pegawai' => 'PPPK']) }}" target="_blank" class="badge bg-success text-decoration-none px-2 py-1">
                    <i class="bi bi-printer me-1"></i>Cetak PPPK
                </a>
                <a href="{{ route('employees.export-pdf', ['status_pegawai' => 'PPPK Paruh Waktu']) }}" target="_blank" class="badge bg-info text-decoration-none px-2 py-1">
                    <i class="bi bi-printer me-1"></i>Cetak PPPK Paruh Waktu
                </a>
                <a href="{{ route('employees.export-pdf') }}" target="_blank" class="badge bg-secondary text-decoration-none px-2 py-1">
                    <i class="bi bi-printer me-1"></i>Cetak Seluruh Pegawai
                </a>
            </div>

            <!-- Form Pencarian & Filter -->
            <form action="{{ route('employees.index') }}" method="GET" class="row g-2 mb-3 align-items-center" role="search">
                <div class="col-md-3">
                    <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control form-control-sm" placeholder="Cari nama, NIP, unit, jabatan, pangkat...">
                </div>
                <div class="col-md-2">
                    <select name="status_pegawai" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Status Pegawai</option>
                        @foreach (['PNS', 'PPPK', 'PPPK Paruh Waktu'] as $s)
                            <option value="{{ $s }}" {{ ($statusPegawai ?? '') === $s ? 'selected' : '' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="department_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Unit Kerja</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}" {{ ($departmentId ?? '') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="position_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Jabatan</option>
                        @foreach ($positions as $pos)
                            <option value="{{ $pos->id }}" {{ ($positionId ?? '') == $pos->id ? 'selected' : '' }}>
                                {{ $pos->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                    @if (!empty($search) || !empty($statusPegawai) || !empty($statusKepegawaian) || !empty($departmentId) || !empty($positionId))
                        <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                            <i class="bi bi-x-circle me-1"></i>Reset
                        </a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>NIP / NIK</th>
                            <th>Status</th>
                            <th>Pangkat / Gol.</th>
                            <th>Unit Kerja</th>
                            <th>Jabatan</th>
                            <th>Masa Kerja</th>
                            <th>Status / Kelayakan</th>
                            <th class="text-center" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $employee->full_name }}</div>
                                    <small class="text-muted"><i class="bi bi-person-heart me-1"></i>Usia: {{ $employee->usia }} Thn</small>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $employee->employee_number ?: '-' }}</span></td>
                                <td>
                                    @if ($employee->status_pegawai === 'PNS')
                                        <span class="badge bg-primary px-2 py-1">PNS</span>
                                    @elseif ($employee->status_pegawai === 'PPPK')
                                        <span class="badge bg-success px-2 py-1">PPPK</span>
                                    @elseif ($employee->status_pegawai === 'PPPK Paruh Waktu')
                                        <span class="badge bg-info px-2 py-1">PPPK Paruh Waktu</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $employee->status_pegawai ?: '-' }}</span>
                                    @endif
                                </td>
                                <td><small class="fw-semibold">{{ $employee->pangkat_golongan ?: '-' }}</small></td>
                                <td>{{ $employee->unit_kerja ?: $employee->department->name ?? '-' }}</td>
                                <td><small>{{ $employee->position->name ?? '-' }}</small></td>
                                <td><small class="fw-bold text-primary">{{ $employee->masa_kerja }}</small></td>
                                <td>
                                    <div class="d-flex flex-column gap-1 align-items-start">
                                        @if ($employee->is_sudah_pensiun)
                                            <span class="badge bg-danger">Pensiun (58 Thn)</span>
                                        @elseif ($employee->employment_status == 'active')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif

                                        @if ($employee->is_eligible_kgb)
                                            <span class="badge bg-warning text-dark" title="Eligible KGB 2 Tahun"><i class="bi bi-clock me-1"></i>Eligible KGB</span>
                                        @endif

                                        @if ($employee->is_eligible_kenaikan_pangkat)
                                            <span class="badge bg-info text-dark" title="Eligible Kenaikan Pangkat 4 Tahun"><i class="bi bi-award me-1"></i>Eligible Pangkat</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-info text-white" title="Detail & Riwayat Kepegawaian">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning" title="Edit Pegawai">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Hapus Pegawai">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Belum ada data pegawai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $employees->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Modal Cetak PDF -->
    <div class="modal fade" id="pdfExportModal" tabindex="-1" aria-labelledby="pdfExportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('employees.export-pdf') }}" method="GET" target="_blank">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="pdfExportModalLabel"><i class="bi bi-file-earmark-pdf me-2"></i>Cetak Laporan PDF Pegawai</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">Pilih filter untuk mencetak data pegawai dalam format PDF instansi resmi.</p>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small">Status Pegawai</label>
                            <select name="status_pegawai" class="form-select">
                                <option value="">Cetak Seluruh Status Pegawai</option>
                                <option value="PNS">Cetak Seluruh PNS</option>
                                <option value="PPPK">Cetak Seluruh PPPK</option>
                                <option value="PPPK Paruh Waktu">Cetak Seluruh PPPK Paruh Waktu</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small">Unit Kerja</label>
                            <select name="department_id" class="form-select">
                                <option value="">Semua Unit Kerja</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small">Jabatan</label>
                            <select name="position_id" class="form-select">
                                <option value="">Semua Jabatan</option>
                                @foreach ($positions as $pos)
                                    <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger"><i class="bi bi-printer me-1"></i>Cetak PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
