@extends('layouts.app')

@section('title', 'Data Aset')

@section('content')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h1 class="h4 mb-0 fw-bold text-primary">
                        <i class="bi bi-box-seam-fill me-2"></i>Data Aset Bakesbangpol
                    </h1>
                    <p class="text-muted small mb-0">Kelola data sarana prasarana komputer, kendaraan, dan peralatan BMD.</p>
                </div>
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-success dropdown-toggle d-inline-flex align-items-center fw-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-printer me-1"></i>Export / Cetak Laporan
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 280px;">
                            <li>
                                <a class="dropdown-item fw-semibold text-primary py-2" href="{{ route('assets.print-preview', array_merge(request()->query(), ['mode' => 'rekap'])) }}" target="_blank">
                                    <i class="bi bi-eye me-2 fs-6"></i>Preview Cetak Laporan
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item fw-semibold text-danger py-2" href="{{ route('assets.export-pdf', array_merge(request()->query(), ['mode' => 'rekap'])) }}" target="_blank">
                                    <i class="bi bi-file-earmark-pdf me-2 fs-6"></i>Preview PDF Rekapitulasi
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item fw-semibold text-info py-2" href="{{ route('assets.export-pdf', array_merge(request()->query(), ['mode' => 'detail'])) }}" target="_blank">
                                    <i class="bi bi-file-earmark-text me-2 fs-6"></i>Preview PDF Rincian Aset
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item fw-semibold text-success py-2" href="{{ route('assets.export-excel', request()->query()) }}">
                                    <i class="bi bi-file-earmark-excel me-2 fs-6"></i>Export Excel (.csv / .xlsx)
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('assets.deletable') }}" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center">
                        <i class="bi bi-trash3 me-1"></i>Aset Dapat Dihapus (>=10 Thn)
                    </a>
                    <a href="{{ route('assets.create') }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Aset Baru
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert">
                    <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Form Filter & Pencarian -->
            <form action="{{ route('assets.index') }}" method="GET" class="row g-2 mb-3 align-items-center" role="search">
                <div class="col-md-3">
                    <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control form-control-sm" placeholder="Cari kode, nama barang, merk, unit...">
                </div>
                <div class="col-md-2">
                    <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ ($categoryId ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="bidang" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Unit Kerja</option>
                        @foreach ($bidangList as $b)
                            <option value="{{ $b }}" {{ ($bidang ?? '') === $b ? 'selected' : '' }}>
                                {{ $b }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="Aktif" {{ ($status ?? '') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tersedia" {{ ($status ?? '') === 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Dipinjam" {{ ($status ?? '') === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dalam Perbaikan" {{ ($status ?? '') === 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                        <option value="Rusak" {{ ($status ?? '') === 'Rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="eligible_10_years" {{ ($status ?? '') === 'eligible_10_years' ? 'selected' : '' }}>Umur >= 10 Tahun (Potensi Penghapusan)</option>
                        <option value="Dapat Dihapus" {{ ($status ?? '') === 'Dapat Dihapus' ? 'selected' : '' }}>Diverifikasi: Dapat Dihapus</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                    @if (!empty($search) || !empty($status) || !empty($bidang) || !empty($categoryId))
                        <a href="{{ route('assets.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                            <i class="bi bi-x-circle me-1"></i>Reset
                        </a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Aset</th>
                            <th>Nama Barang / Aset</th>
                            <th>Kategori</th>
                            <th>Merk / Spesifikasi</th>
                            <th>Unit Kerja & Lokasi</th>
                            <th>Nilai Perolehan</th>
                            <th>Umur Aset</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary font-monospace">{{ $asset->asset_code }}</span>
                                    @if($asset->kode_barang)<div class="small text-muted">{{ $asset->kode_barang }}</div>@endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $asset->nama_barang }}</div>
                                    @if($asset->currentEmployee)
                                        <small class="text-muted"><i class="bi bi-person me-1"></i>{{ $asset->currentEmployee->full_name }}</small>
                                    @endif
                                </td>
                                <td><small class="fw-semibold">{{ $asset->categoryRelation->name ?? $asset->category ?? '-' }}</small></td>
                                <td>
                                    <div class="small fw-semibold">{{ $asset->merk_tipe ?: ($asset->brand . ' ' . $asset->model) ?: '-' }}</div>
                                    @if($asset->serial_number)<small class="text-muted">SN: {{ $asset->serial_number }}</small>@endif
                                </td>
                                <td>
                                    <div><small class="fw-semibold">{{ $asset->bidang ?: '-' }}</small></div>
                                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $asset->location ?: '-' }}</small>
                                </td>
                                <td class="fw-bold text-success">Rp {{ number_format((float)($asset->nilai_perolehan ?: $asset->purchase_price), 0, ',', '.') }}</td>
                                <td>
                                    <small class="fw-semibold">{{ $asset->age_formatted }}</small>
                                    @if($asset->is_eligible_disposal)
                                        <div><span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-exclamation-triangle me-1"></i>>= 10 Thn</span></div>
                                    @endif
                                </td>
                                <td>
                                    @if ($asset->status === 'Aktif' || $asset->status === 'Tersedia' || $asset->status === 'Disetujui')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                                    @elseif ($asset->status === 'Dalam Perbaikan')
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Dalam Perbaikan</span>
                                    @elseif ($asset->status === 'Dapat Dihapus')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Dapat Dihapus</span>
                                    @elseif ($asset->status === 'Sudah Dihapus')
                                        <span class="badge bg-secondary">Sudah Dihapus</span>
                                    @else
                                        <span class="badge bg-info">{{ $asset->status }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('assets.show', $asset) }}" class="btn btn-info text-white" title="Detail & History Lifecycle Aset">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-warning" title="Edit Aset">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus aset ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Hapus Aset">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Belum ada data aset.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $assets->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
