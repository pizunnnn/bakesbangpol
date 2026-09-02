@extends('layouts.app')

@section('title', 'Tambah Aset Baru')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold text-primary">Tambah Aset Baru</h1>
            <p class="text-muted mb-0">Sistem Pengadaan & Inventaris Barang Milik Daerah (BMD) Bakesbangpol</p>
        </div>
        <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white fw-bold rounded-4 rounded-bottom-0 py-3">
            <i class="bi bi-box-seam-fill me-2"></i>Form Tambah Data Aset
        </div>
        <div class="card-body p-4">
            <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Kategori Master Aset <span class="text-danger">*</span></label>
                        <select name="asset_category_id" class="form-select @error('asset_category_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori Master --</option>
                            @foreach ($categories ?? [] as $cat)
                                <option value="{{ $cat->id }}" {{ old('asset_category_id') == $cat->id ? 'selected' : '' }}>
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
                                <option value="{{ $b }}" {{ old('bidang') == $b ? 'selected' : '' }}>
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
                                <option value="{{ $catalog->kode_barang }}" data-nama="{{ $catalog->nama_barang }}" {{ old('kode_barang') == $catalog->kode_barang ? 'selected' : '' }}>
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
                        <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" placeholder="Contoh: Laptop Core i7 / Honda HiAce / Server IBM" value="{{ old('nama_barang') }}" required>
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">No. Register / Kode Registrasi</label>
                        <input type="text" name="no_register" class="form-control @error('no_register') is-invalid @enderror" value="{{ old('no_register', '1') }}">
                        @error('no_register')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Merk / Tipe / Model</label>
                        <input type="text" name="merk_tipe" class="form-control @error('merk_tipe') is-invalid @enderror" placeholder="Contoh: Asus ExpertBook B1 / Honda EU30i" value="{{ old('merk_tipe') }}">
                        @error('merk_tipe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Serial Number (SN)</label>
                        <input type="text" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" placeholder="Nomor seri perangkat" value="{{ old('serial_number') }}">
                        @error('serial_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">Spesifikasi Detail (IT / Perangkat / Kendaraan)</label>
                    <textarea name="spesifikasi" class="form-control @error('spesifikasi') is-invalid @enderror" rows="2" placeholder="Detail spesifikasi teknis (Prosesor, RAM, SSD, Nomor Polisi, Kapasitas Engine, dll.)">{{ old('spesifikasi') }}</textarea>
                    @error('spesifikasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Tanggal Perolehan <span class="text-danger">*</span></label>
                        <input type="date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                        @error('purchase_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Triwulan Perolehan</label>
                        <select name="triwulan" class="form-select">
                            <option value="TW I" {{ old('triwulan') == 'TW I' ? 'selected' : '' }}>TW I</option>
                            <option value="TW II" {{ old('triwulan', 'TW II') == 'TW II' ? 'selected' : '' }}>TW II</option>
                            <option value="TW III" {{ old('triwulan') == 'TW III' ? 'selected' : '' }}>TW III</option>
                            <option value="TW IV" {{ old('triwulan') == 'TW IV' ? 'selected' : '' }}>TW IV</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Tahun Anggaran</label>
                        <input type="number" name="tahun_anggaran" class="form-control @error('tahun_anggaran') is-invalid @enderror" value="{{ old('tahun_anggaran', now()->year) }}" required>
                        @error('tahun_anggaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Jumlah Unit</label>
                        <input type="number" name="jumlah_unit" class="form-control @error('jumlah_unit') is-invalid @enderror" value="{{ old('jumlah_unit', 1) }}" min="1" required>
                        @error('jumlah_unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Nilai Perolehan (Rp)</label>
                        <input type="number" step="0.01" name="nilai_perolehan" class="form-control @error('nilai_perolehan') is-invalid @enderror" placeholder="15000000" value="{{ old('nilai_perolehan') }}" required>
                        @error('nilai_perolehan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Status Operasional</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="Tersedia" {{ old('status', 'Tersedia') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="Dipinjam" {{ old('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Dalam Perbaikan" {{ old('status') == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            <option value="Rusak Ringan" {{ old('status') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ old('status') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                            <option value="Dapat Dihapus" {{ old('status') == 'Dapat Dihapus' ? 'selected' : '' }}>Dapat Dihapus (Age >= 10 Thn)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Lokasi Fisik Aset</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" placeholder="Ruang Rapat, Ruang Server, Ruang Sekretariat" value="{{ old('location') }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Penanggung Jawab / Pegawai Pemakai</label>
                        <select name="current_employee_id" class="form-select @error('current_employee_id') is-invalid @enderror">
                            <option value="">-- Pilih Penanggung Jawab --</option>
                            @foreach ($employees ?? [] as $emp)
                                <option value="{{ $emp->id }}" {{ old('current_employee_id') == $emp->id ? 'selected' : '' }}>
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
                    <div id="photo_preview" class="mt-2"></div>
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2 pt-3 border-top mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-check-lg me-1"></i>Simpan Aset Baru
                    </button>
                    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('photo');
            const preview = document.getElementById('photo_preview');
            if (input && preview) {
                input.addEventListener('change', function() {
                    preview.innerHTML = '';
                    const file = input.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail';
                        img.style.maxHeight = '150px';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
@endpush
