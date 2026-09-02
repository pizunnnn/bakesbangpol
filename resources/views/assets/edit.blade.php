@extends('layouts.app')

@section('title', 'Edit Data Aset')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold text-primary">Edit Data Aset</h1>
            <p class="text-muted mb-0">Perbarui informasi dan spesifikasi aset <strong>{{ $asset->nama_barang }}</strong>.</p>
        </div>
        <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-warning text-dark fw-bold rounded-4 rounded-bottom-0 py-3">
            <i class="bi bi-pencil-square me-2"></i>Form Edit Data Aset
        </div>
        <div class="card-body p-4">
            <form action="{{ route('assets.update', $asset) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Kategori Master Aset <span class="text-danger">*</span></label>
                        <select name="asset_category_id" class="form-select @error('asset_category_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori Master --</option>
                            @foreach ($categories ?? [] as $cat)
                                <option value="{{ $cat->id }}" {{ old('asset_category_id', $asset->asset_category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('asset_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Bidang / Unit Kerja Pemakai</label>
                        <select name="bidang" class="form-select @error('bidang') is-invalid @enderror">
                            <option value="">-- Pilih Bidang --</option>
                            @foreach ($bidangList ?? [] as $b)
                                <option value="{{ $b }}" {{ old('bidang', $asset->bidang) == $b ? 'selected' : '' }}>
                                    {{ $b }}
                                </option>
                            @endforeach
                        </select>
                        @error('bidang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">Kode Barang Katalog (Permendagri) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select name="kode_barang" id="kode_barang_select" class="form-select @error('kode_barang') is-invalid @enderror" required>
                            <option value="">-- Pilih Kode Barang Katalog --</option>
                            @foreach ($catalogs ?? [] as $catalog)
                                <option value="{{ $catalog->kode_barang }}" data-nama="{{ $catalog->nama_barang }}" {{ old('kode_barang', $asset->kode_barang) == $catalog->kode_barang ? 'selected' : '' }}>
                                    {{ $catalog->kode_barang }} - {{ $catalog->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary" target="_blank" title="Kelola Katalog Barang">
                            <i class="bi bi-journal-bookmark"></i>
                        </a>
                    </div>
                    @error('kode_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Nama Barang / Aset <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" value="{{ old('nama_barang', $asset->nama_barang) }}" required>
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">No. Register / Kode Registrasi</label>
                        <input type="text" name="no_register" class="form-control @error('no_register') is-invalid @enderror" value="{{ old('no_register', $asset->no_register) }}">
                        @error('no_register')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Merk / Tipe / Model</label>
                        <input type="text" name="merk_tipe" class="form-control @error('merk_tipe') is-invalid @enderror" value="{{ old('merk_tipe', $asset->merk_tipe) }}">
                        @error('merk_tipe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Serial Number (SN)</label>
                        <input type="text" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" value="{{ old('serial_number', $asset->serial_number) }}">
                        @error('serial_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">Spesifikasi Detail (IT / Perangkat / Kendaraan)</label>
                    <textarea name="spesifikasi" class="form-control @error('spesifikasi') is-invalid @enderror" rows="2">{{ old('spesifikasi', $asset->spesifikasi) }}</textarea>
                    @error('spesifikasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Tanggal Perolehan <span class="text-danger">*</span></label>
                        <input type="date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date', $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : ($asset->tahun_perolehan ? $asset->tahun_perolehan . '-01-01' : date('Y-m-d'))) }}" required>
                        @error('purchase_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Jumlah Unit</label>
                        <input type="number" name="jumlah_unit" class="form-control @error('jumlah_unit') is-invalid @enderror" value="{{ old('jumlah_unit', $asset->jumlah_unit ?: 1) }}" min="1" required>
                        @error('jumlah_unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Nilai Perolehan (Rp)</label>
                        <input type="number" step="0.01" name="nilai_perolehan" class="form-control @error('nilai_perolehan') is-invalid @enderror" value="{{ old('nilai_perolehan', $asset->nilai_perolehan ?: $asset->purchase_price) }}" required>
                        @error('nilai_perolehan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Status Aset</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="Tersedia" {{ in_array(old('status', $asset->status), ['Tersedia', 'Aktif']) ? 'selected' : '' }}>Tersedia</option>
                            <option value="Dipinjam" {{ old('status', $asset->status) == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Dalam Perbaikan" {{ old('status', $asset->status) == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            <option value="Rusak Ringan" {{ in_array(old('status', $asset->status), ['Rusak Ringan', 'RR']) ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ in_array(old('status', $asset->status), ['Rusak Berat', 'RB', 'Rusak']) ? 'selected' : '' }}>Rusak Berat</option>
                            <option value="Dapat Dihapus" {{ old('status', $asset->status) == 'Dapat Dihapus' ? 'selected' : '' }}>Dapat Dihapus</option>
                            <option value="Sudah Dihapus" {{ old('status', $asset->status) == 'Sudah Dihapus' ? 'selected' : '' }}>Sudah Dihapus</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Lokasi Fisik Aset</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $asset->location) }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Penanggung Jawab Pemakai</label>
                        <select name="current_employee_id" class="form-select @error('current_employee_id') is-invalid @enderror">
                            <option value="">-- Pilih Penanggung Jawab --</option>
                            @foreach ($employees ?? [] as $emp)
                                <option value="{{ $emp->id }}" {{ old('current_employee_id', $asset->current_employee_id) == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->full_name }} ({{ $emp->unit_kerja ?: '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('current_employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">Foto Dokumentasi Aset</label>
                    <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                    @if($asset->photo)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $asset->photo) }}" alt="Foto Aset" class="img-thumbnail" style="max-height: 120px;">
                        </div>
                    @endif
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2 pt-3 border-top mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
