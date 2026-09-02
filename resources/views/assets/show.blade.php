@extends('layouts.app')

@section('title', 'Detail Aset - ' . ($asset->nama_barang ?: $asset->asset_code))

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold text-primary">Detail & History Lifecycle Aset</h1>
            <p class="text-muted mb-0">Informasi spesifikasi, pemeliharaan, perbaikan kendaraan, dan rekam jejak aset <strong>{{ $asset->nama_barang }}</strong>.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('assets.edit', $asset) }}" class="btn btn-warning px-3 py-2">
                <i class="bi bi-pencil me-1"></i>Edit Data Aset
            </a>
            <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary px-3 py-2">
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

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Banner Peringatan Umur 10 Tahun -->
    @if ($asset->is_eligible_disposal)
        <div class="alert alert-warning shadow-sm rounded-4 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-2 me-3 text-warning-emphasis"></i>
                <div>
                    <h5 class="fw-bold mb-1 text-dark">Aset Berusia >= 10 Tahun (Dapat Dihapus)</h5>
                    <p class="mb-0 small text-dark">Aset ini telah mencapai umur <strong>{{ $asset->age_formatted }}</strong> sejak tanggal perolehan ({{ $asset->tanggal_perolehan ? $asset->tanggal_perolehan->format('d F Y') : '-' }}). Aset dapat diproses untuk usulan penghapusan.</p>
                </div>
            </div>
            <button type="button" class="btn btn-danger fw-bold px-3 py-2" data-bs-toggle="modal" data-bs-target="#verifyDisposalModal">
                <i class="bi bi-shield-check me-1"></i>Verifikasi Penghapusan
            </button>
        </div>
    @endif

    <!-- Profile Header Card -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    @if($asset->photo)
                        <img src="{{ asset('storage/' . $asset->photo) }}" alt="{{ $asset->nama_barang }}" class="img-fluid rounded-4 shadow-sm" style="max-height: 120px; object-fit: cover;">
                    @else
                        <div class="avatar-box bg-primary text-white rounded-4 d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 100px; height: 100px; font-size: 36px; font-weight: bold;">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-10">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <div>
                            <h2 class="h4 fw-bold mb-1">{{ $asset->nama_barang }}</h2>
                            <p class="text-muted mb-0">
                                Kode Aset: <strong class="text-dark">{{ $asset->asset_code }}</strong> | 
                                Kode Barang: <strong>{{ $asset->kode_barang ?: '-' }}</strong> | 
                                No. Reg: <strong>{{ $asset->no_register ?: '-' }}</strong>
                            </p>
                        </div>
                        <div>
                            @if ($asset->status === 'Tersedia' || $asset->status === 'Aktif' || $asset->status === 'Disetujui')
                                <span class="badge bg-success-subtle text-success fs-6 border border-success-subtle px-3 py-2">Tersedia</span>
                            @elseif ($asset->status === 'Dipinjam')
                                <span class="badge bg-info-subtle text-info-emphasis fs-6 border border-info-subtle px-3 py-2">Dipinjam</span>
                            @elseif ($asset->status === 'Dalam Perbaikan')
                                <span class="badge bg-warning-subtle text-warning-emphasis fs-6 border border-warning-subtle px-3 py-2">Dalam Perbaikan</span>
                            @elseif ($asset->status === 'Rusak Ringan' || $asset->status === 'RR')
                                <span class="badge bg-warning text-dark fs-6 border border-warning-subtle px-3 py-2">Rusak Ringan</span>
                            @elseif ($asset->status === 'Rusak Berat' || $asset->status === 'RB' || $asset->status === 'Rusak')
                                <span class="badge bg-danger text-white fs-6 px-3 py-2">Rusak Berat</span>
                            @elseif ($asset->status === 'Dapat Dihapus')
                                <span class="badge bg-danger-subtle text-danger fs-6 border border-danger-subtle px-3 py-2">Dapat Dihapus</span>
                            @elseif ($asset->status === 'Sudah Dihapus')
                                <span class="badge bg-secondary fs-6 px-3 py-2">Sudah Dihapus</span>
                            @else
                                <span class="badge bg-info fs-6 px-3 py-2">{{ $asset->status }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3 mt-1 bg-light rounded-3 p-3">
                        <div class="col-md-3">
                            <small class="text-muted d-block"><i class="bi bi-tag me-1"></i>Kategori</small>
                            <span class="fw-semibold">{{ $asset->categoryRelation->name ?? $asset->category ?? '-' }}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block"><i class="bi bi-building me-1"></i>Unit Kerja / Bidang</small>
                            <span class="fw-semibold">{{ $asset->bidang ?: '-' }}</span>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted d-block"><i class="bi bi-clock-history me-1"></i>Umur Aset</small>
                            <span class="fw-bold text-primary">{{ $asset->age_formatted }}</span>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted d-block"><i class="bi bi-cash me-1"></i>Nilai Perolehan</small>
                            <span class="fw-bold text-success">Rp {{ number_format((float)($asset->nilai_perolehan ?: $asset->purchase_price), 0, ',', '.') }}</span>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted d-block"><i class="bi bi-person me-1"></i>Penanggung Jawab</small>
                            <span class="fw-semibold">{{ $asset->currentEmployee->full_name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Integrated Tabs Navigation -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-bottom-0 rounded-top-4 pt-3 px-4">
            <ul class="nav nav-tabs card-header-tabs" id="assetDetailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold" id="tab-specs-btn" data-bs-toggle="tab" data-bs-target="#tab-specs" type="button" role="tab"><i class="bi bi-info-circle me-1"></i>Spesifikasi & Detail</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab-maintenance-btn" data-bs-toggle="tab" data-bs-target="#tab-maintenance" type="button" role="tab"><i class="bi bi-wrench-adjustable me-1"></i>Pemeliharaan Aset ({{ $asset->maintenances->count() }})</button>
                </li>
                @if($asset->is_vehicle)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" id="tab-vehicle-btn" data-bs-toggle="tab" data-bs-target="#tab-vehicle" type="button" role="tab"><i class="bi bi-car-front me-1"></i>Perbaikan Kendaraan ({{ $asset->vehicleRepairs->count() }})</button>
                    </li>
                @endif
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab-history-btn" data-bs-toggle="tab" data-bs-target="#tab-history" type="button" role="tab"><i class="bi bi-clock-history me-1"></i>Rekam Jejak History ({{ $asset->histories->count() }})</button>
                </li>
            </ul>
        </div>
        <div class="card-body p-4">
            <div class="tab-content" id="assetDetailTabsContent">
                
                <!-- TAB SPESIFIKASI & DETAIL -->
                <div class="tab-pane fade show active" id="tab-specs" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Atribut & Spesifikasi Barang</h6>
                            <table class="table table-borderless table-sm">
                                <tr><td class="text-muted" style="width: 160px;">Nama Barang</td><td>: <strong>{{ $asset->nama_barang }}</strong></td></tr>
                                <tr><td class="text-muted">Kode Aset</td><td>: <span class="badge bg-secondary font-monospace">{{ $asset->asset_code }}</span></td></tr>
                                <tr><td class="text-muted">Merk / Tipe / Model</td><td>: {{ $asset->merk_tipe ?: ($asset->brand . ' ' . $asset->model) ?: '-' }}</td></tr>
                                <tr><td class="text-muted">Serial Number (SN)</td><td>: {{ $asset->serial_number ?: '-' }}</td></tr>
                                <tr><td class="text-muted">Spesifikasi Detail</td><td>: {{ $asset->spesifikasi ?: '-' }}</td></tr>
                                <tr><td class="text-muted">Jumlah Unit</td><td>: {{ $asset->jumlah_unit ?: 1 }} Unit</td></tr>
                                <tr><td class="text-muted">Kondisi / Keadaan</td><td>: <span class="badge bg-info">{{ $asset->condition ?: $asset->keadaan ?: '-' }}</span></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Perolehan & Lokasi</h6>
                            <table class="table table-borderless table-sm">
                                <tr><td class="text-muted" style="width: 160px;">Tanggal Perolehan</td><td>: {{ $asset->tanggal_perolehan ? $asset->tanggal_perolehan->format('d F Y') : '-' }}</td></tr>
                                <tr><td class="text-muted">Tahun Perolehan</td><td>: {{ $asset->tahun_perolehan ?: '-' }}</td></tr>
                                <tr><td class="text-muted">Cara Perolehan</td><td>: {{ $asset->cara_perolehan ?: '-' }}</td></tr>
                                <tr><td class="text-muted">Nilai Perolehan</td><td>: <span class="fw-bold text-success">Rp {{ number_format((float)($asset->nilai_perolehan ?: $asset->purchase_price), 0, ',', '.') }}</span></td></tr>
                                <tr><td class="text-muted">Lokasi Fisik Aset</td><td>: {{ $asset->location ?: '-' }}</td></tr>
                                <tr><td class="text-muted">Unit Kerja / Bidang</td><td>: {{ $asset->bidang ?: '-' }}</td></tr>
                                <tr><td class="text-muted">Penanggung Jawab</td><td>: {{ $asset->currentEmployee->full_name ?? 'Belum Ditentukan' }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB PEMELIHARAAN ASET -->
                <div class="tab-pane fade" id="tab-maintenance" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-wrench-adjustable me-1"></i>Riwayat Pemeliharaan Aset</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Pemeliharaan
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis Pemeliharaan</th>
                                    <th>Deskripsi</th>
                                    <th>Vendor / Bengkel</th>
                                    <th>Biaya</th>
                                    <th>Nota / Berkas</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($asset->maintenances as $m)
                                    <tr>
                                        <td><strong>{{ $m->maintenance_date ? $m->maintenance_date->format('d/m/Y') : '-' }}</strong></td>
                                        <td><span class="badge bg-secondary">{{ $m->maintenance_type }}</span></td>
                                        <td>
                                            <div>{{ $m->description }}</div>
                                            @if($m->notes)<small class="text-muted">{{ $m->notes }}</small>@endif
                                        </td>
                                        <td>{{ $m->vendor_name ?: '-' }}</td>
                                        <td class="fw-bold text-success">Rp {{ number_format((float)$m->cost, 0, ',', '.') }}</td>
                                        <td>
                                            @if($m->receipt_file)
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ asset('storage/' . $m->receipt_file) }}" target="_blank" class="btn btn-outline-primary" title="Lihat Nota">
                                                        <i class="bi bi-eye me-1"></i>Lihat
                                                    </a>
                                                    <a href="{{ route('assets.maintenances.receipt', $m) }}" class="btn btn-outline-danger" title="Unduh Nota">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                                @if($m->receipt_number)<div class="small text-muted mt-1">No: {{ $m->receipt_number }}</div>@endif
                                            @else
                                                <span class="text-muted small">Tidak ada nota</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-success-subtle text-success border border-success-subtle">{{ $m->status }}</span></td>
                                        <td class="text-center">
                                            <form action="{{ route('assets.maintenances.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus data pemeliharaan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada riwayat pemeliharaan untuk aset ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB PERBAIKAN KENDARAAN (JIKA KENDARAAN) -->
                @if($asset->is_vehicle)
                    <div class="tab-pane fade" id="tab-vehicle" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-primary mb-0"><i class="bi bi-car-front me-1"></i>Riwayat Perbaikan Kendaraan</h6>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleRepairModal">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Perbaikan Kendaraan
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No. Polisi</th>
                                        <th>Kerusakan & Deskripsi</th>
                                        <th>Bengkel</th>
                                        <th>Biaya Perbaikan</th>
                                        <th>Nota Perbaikan</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($asset->vehicleRepairs as $vr)
                                        <tr>
                                            <td><strong>{{ $vr->repair_date ? $vr->repair_date->format('d/m/Y') : '-' }}</strong></td>
                                            <td><span class="badge bg-dark font-monospace">{{ $vr->license_plate ?: '-' }}</span></td>
                                            <td>
                                                <div class="fw-semibold text-danger">{{ $vr->damage_type ?: 'Perbaikan Umum' }}</div>
                                                <small class="text-muted">{{ $vr->repair_description }}</small>
                                            </td>
                                            <td>{{ $vr->workshop_name ?: '-' }}</td>
                                            <td class="fw-bold text-success">Rp {{ number_format((float)$vr->cost, 0, ',', '.') }}</td>
                                            <td>
                                                @if($vr->receipt_file)
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ asset('storage/' . $vr->receipt_file) }}" target="_blank" class="btn btn-outline-primary" title="Lihat Nota">
                                                            <i class="bi bi-eye me-1"></i>Lihat
                                                        </a>
                                                        <a href="{{ route('assets.repairs.receipt', $vr) }}" class="btn btn-outline-danger" title="Unduh Nota">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    </div>
                                                    @if($vr->receipt_number)<div class="small text-muted mt-1">No: {{ $vr->receipt_number }}</div>@endif
                                                @else
                                                    <span class="text-muted small">Tidak ada nota</span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-warning text-dark">{{ $vr->status }}</span></td>
                                            <td class="text-center">
                                                <form action="{{ route('assets.repairs.destroy', $vr) }}" method="POST" onsubmit="return confirm('Hapus data perbaikan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada riwayat perbaikan kendaraan untuk aset ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- TAB REKAM JEJAK HISTORY LIFECYCLE -->
                <div class="tab-pane fade" id="tab-history" role="tabpanel">
                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-clock-history me-1"></i>Rekam Jejak Audit Trail Lifecycle Aset</h6>
                    
                    <div class="timeline ps-3 border-start border-2 border-primary">
                        @forelse($asset->histories as $h)
                            <div class="mb-4 position-relative ps-4">
                                <div class="position-absolute top-0 start-0 translate-middle-x bg-primary rounded-circle" style="width: 12px; height: 12px; margin-left: -1px; margin-top: 5px;"></div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold text-dark mb-0">{{ $h->event_type }}</h6>
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $h->created_at ? $h->created_at->format('d F Y H:i') : '-' }}</small>
                                </div>
                                <p class="text-muted mb-1 small">{{ $h->description }}</p>
                                <small class="text-secondary"><i class="bi bi-person me-1"></i>Oleh: {{ $h->user->name ?? 'Sistem' }}</small>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">Belum ada catatan rekam jejak history untuk aset ini.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL VERIFIKASI PENGHAPUSAN (AGE >= 10 YRS) -->
    <div class="modal fade" id="verifyDisposalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('assets.verify-disposal', $asset) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="bi bi-shield-check me-2"></i>Verifikasi Penghapusan Aset</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">Aset ini telah memenuhi syarat umur >= 10 tahun. Silakan pilih status verifikasi dan sertakan catatan administrasi.</p>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Status Penghapusan</label>
                            <select name="status" class="form-select" required>
                                <option value="Dapat Dihapus" {{ $asset->status == 'Dapat Dihapus' ? 'selected' : '' }}>Dapat Dihapus (Siap Diproses)</option>
                                <option value="Sudah Dihapus" {{ $asset->status == 'Sudah Dihapus' ? 'selected' : '' }}>Sudah Dihapus (Selesai Penghapusan)</option>
                                <option value="Tersedia" {{ in_array($asset->status, ['Tersedia', 'Aktif']) ? 'selected' : '' }}>Tetap Digunakan / Tersedia</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Catatan Verifikasi</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Catatan atau pertimbangan verifikasi penghapusan aset..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger"><i class="bi bi-check-lg me-1"></i>Simpan Verifikasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH PEMELIHARAAN -->
    <div class="modal fade" id="addMaintenanceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('assets.maintenances.store', $asset) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-wrench me-2"></i>Tambah Pemeliharaan Aset</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Tanggal Pemeliharaan <span class="text-danger">*</span></label>
                                <input type="date" name="maintenance_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Jenis Pemeliharaan <span class="text-danger">*</span></label>
                                <select name="maintenance_type" class="form-select" required>
                                    <option value="Perawatan Rutin">Perawatan Rutin</option>
                                    <option value="Perbaikan">Perbaikan</option>
                                    <option value="Penggantian Komponen">Penggantian Komponen</option>
                                    <option value="Pemeriksaan">Pemeriksaan</option>
                                    <option value="Pemeliharaan Lainnya">Pemeliharaan Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Deskripsi Pemeliharaan <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Rincian pemeliharaan aset..." required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Vendor / Bengkel / Pihak Pelaksana</label>
                                <input type="text" name="vendor_name" class="form-control" placeholder="Nama vendor / pelaksana">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Biaya (Rp) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="cost" class="form-control" value="0" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Kondisi Sebelum</label>
                                <input type="text" name="condition_before" class="form-control" value="{{ $asset->condition ?: $asset->keadaan }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Kondisi Setelah</label>
                                <input type="text" name="condition_after" class="form-control" value="Baik">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Status</label>
                                <select name="status" class="form-select">
                                    <option value="Selesai">Selesai</option>
                                    <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                                    <option value="Diajukan">Diajukan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Nomor Nota / Kwitansi</label>
                                <input type="text" name="receipt_number" class="form-control" placeholder="Nomor kwitansi/nota">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Upload Nota Pemeliharaan (PDF/JPG/PNG)</label>
                            <input type="file" name="receipt_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Pemeliharaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH PERBAIKAN KENDARAAN -->
    @if($asset->is_vehicle)
        <div class="modal fade" id="addVehicleRepairModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('assets.repairs.store', $asset) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="bi bi-car-front-fill me-2"></i>Tambah Perbaikan Kendaraan</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold small text-muted">Nomor Polisi</label>
                                    <input type="text" name="license_plate" class="form-control" value="{{ $asset->no_register ?: '' }}" placeholder="Contoh: D 1234 ABC">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold small text-muted">Tanggal Perbaikan <span class="text-danger">*</span></label>
                                    <input type="date" name="repair_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold small text-muted">Jenis Kerusakan</label>
                                    <input type="text" name="damage_type" class="form-control" placeholder="Servis Rutin / Ganti Oli / Mesin">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold small text-muted">Bengkel Pelaksana</label>
                                    <input type="text" name="workshop_name" class="form-control" placeholder="Nama bengkel">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-muted">Deskripsi Perbaikan <span class="text-danger">*</span></label>
                                <textarea name="repair_description" class="form-control" rows="2" placeholder="Rincian perbaikan kendaraan..." required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold small text-muted">Biaya Perbaikan (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="cost" class="form-control" value="0" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold small text-muted">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="Selesai">Selesai</option>
                                        <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                                        <option value="Diajukan">Diajukan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold small text-muted">Nomor Nota / Kwitansi</label>
                                    <input type="text" name="receipt_number" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold small text-muted">Upload Nota Perbaikan (PDF/JPG/PNG)</label>
                                    <input type="file" name="receipt_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Perbaikan Kendaraan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
