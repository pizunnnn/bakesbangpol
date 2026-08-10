@extends('layouts.app')

@section('title', 'Edit Aset BMD')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Edit Data Aset BMD</h1>
            <p class="text-muted mb-0">{{ $asset->nama_barang ?? $asset->asset_code }}</p>
        </div>
        <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white fw-bold rounded-4 rounded-bottom-0">
            Edit Data Aset BMD
        </div>
        <div class="card-body">
            <form action="{{ route('assets.update', $asset) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label text-muted small">Foto Aset</label>
                    @if ($asset->photo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $asset->photo) }}" alt="Foto aset" class="img-thumbnail"
                                style="max-height: 150px;">
                        </div>
                    @endif
                    <input type="file" name="photo" id="photo"
                        class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                    <div id="photo_preview" class="mt-2"></div>
                    <div class="form-text">Kosongkan jika tidak ingin mengubah foto. Format: JPEG, PNG, JPG, GIF, atau WebP.
                        Maksimal 2MB.</div>
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Bidang / Lokasi Barang</label>
                    <select name="bidang" class="form-select @error('bidang') is-invalid @enderror">
                        <option value="">-- Pilih Bidang --</option>
                        @foreach ($bidangList ?? [] as $b)
                            <option value="{{ $b }}" {{ old('bidang', $asset->bidang) == $b ? 'selected' : '' }}>
                                {{ $b }}</option>
                        @endforeach
                    </select>
                    @error('bidang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Kategori & Kode Barang (Permendagri)</label>
                    <div class="input-group">
                        <select name="kode_barang" id="kode_barang_select"
                            class="form-select @error('kode_barang') is-invalid @enderror" required>
                            <option value="">-- Pilih Kode Barang --</option>
                            @foreach ($catalogs ?? [] as $catalog)
                                <option value="{{ $catalog->kode_barang }}" data-nama="{{ $catalog->nama_barang }}"
                                    {{ old('kode_barang', $asset->kode_barang) == $catalog->kode_barang ? 'selected' : '' }}>
                                    {{ $catalog->kode_barang }} - {{ $catalog->nama_barang }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary" target="_blank"
                            title="Kelola Katalog Barang">
                            <i class="bi bi-journal-bookmark"></i>
                        </a>
                    </div>
                    <div class="form-text">
                        <a href="{{ route('catalog.create') }}" target="_blank">
                            <i class="bi bi-plus-circle me-1"></i>Tambah kode barang baru
                        </a>
                    </div>
                    @error('kode_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted small">No. Register</label>
                        <input type="text" name="no_register"
                            class="form-control @error('no_register') is-invalid @enderror"
                            value="{{ old('no_register', $asset->no_register) }}">
                        @error('no_register')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted small">Kondisi</label>
                        <select name="keadaan" class="form-select @error('keadaan') is-invalid @enderror">
                            <option value="B" {{ old('keadaan', $asset->keadaan) == 'B' ? 'selected' : '' }}>Baik (B)
                            </option>
                            <option value="KB" {{ old('keadaan', $asset->keadaan) == 'KB' ? 'selected' : '' }}>Kurang
                                Baik (KB)</option>
                            <option value="RB" {{ old('keadaan', $asset->keadaan) == 'RB' ? 'selected' : '' }}>Rusak
                                Berat (RB)</option>
                        </select>
                        @error('keadaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror"
                        value="{{ old('nama_barang', $asset->nama_barang) }}" required>
                    @error('nama_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Merk / Tipe</label>
                    <input type="text" name="merk_tipe" class="form-control @error('merk_tipe') is-invalid @enderror"
                        value="{{ old('merk_tipe', $asset->merk_tipe) }}">
                    @error('merk_tipe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Spesifikasi / No. Seri / No. Polisi</label>
                    <textarea name="spesifikasi" class="form-control @error('spesifikasi') is-invalid @enderror" rows="2">{{ old('spesifikasi', $asset->spesifikasi) }}</textarea>
                    @error('spesifikasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted small">Tahun Perolehan</label>
                        <input type="number" name="tahun_perolehan"
                            class="form-control @error('tahun_perolehan') is-invalid @enderror"
                            value="{{ old('tahun_perolehan', $asset->tahun_perolehan) }}">
                        @error('tahun_perolehan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted small">Cara Perolehan</label>
                        <input type="text" name="cara_perolehan"
                            class="form-control @error('cara_perolehan') is-invalid @enderror"
                            value="{{ old('cara_perolehan', $asset->cara_perolehan) }}">
                        @error('cara_perolehan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-3">
                        <label class="form-label text-muted small">Jumlah Unit</label>
                        <input type="number" name="jumlah_unit"
                            class="form-control @error('jumlah_unit') is-invalid @enderror"
                            value="{{ old('jumlah_unit', $asset->jumlah_unit ?? 1) }}" min="1" required>
                        @error('jumlah_unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-label text-muted small">Nilai Perolehan (Rp)</label>
                        <input type="number" name="nilai_perolehan"
                            class="form-control @error('nilai_perolehan') is-invalid @enderror"
                            value="{{ old('nilai_perolehan', $asset->nilai_perolehan) }}" required>
                        @error('nilai_perolehan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-label text-muted small">Umur (Thn)</label>
                        <input type="number" name="umur_ekonomis"
                            class="form-control @error('umur_ekonomis') is-invalid @enderror"
                            value="{{ old('umur_ekonomis', $asset->umur_ekonomis) }}" required>
                        @error('umur_ekonomis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Status</label>
                    <select name="status" id="asset_status" class="form-select @error('status') is-invalid @enderror">
                        <option value="Tersedia" {{ old('status', $asset->status) == 'Tersedia' ? 'selected' : '' }}>
                            Tersedia
                        </option>
                        <option value="Dipinjam" {{ old('status', $asset->status) == 'Dipinjam' ? 'selected' : '' }}>
                            Dipinjam</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="peminjam_field"
                    style="{{ ($asset->status ?? '') === 'Dipinjam' ? '' : 'display:none;' }}">
                    <label class="form-label text-muted small">Dipinjam Oleh</label>
                    <select name="current_employee_id"
                        class="form-select @error('current_employee_id') is-invalid @enderror">
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}"
                                {{ old('current_employee_id', $asset->current_employee_id) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->full_name }}
                                @if ($employee->unit_kerja && $employee->unit_kerja !== '-')
                                    - {{ $employee->unit_kerja }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('current_employee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const statusSelect = document.getElementById('asset_status');
                const peminjamField = document.getElementById('peminjam_field');

                function togglePeminjam() {
                    if (statusSelect.value === 'Dipinjam') {
                        peminjamField.style.display = 'block';
                    } else {
                        peminjamField.style.display = 'none';
                    }
                }

                statusSelect.addEventListener('change', togglePeminjam);

                const photoInput = document.getElementById('photo');
                const photoPreview = document.getElementById('photo_preview');
                if (photoInput && photoPreview) {
                    photoInput.addEventListener('change', function() {
                        photoPreview.innerHTML = '';
                        const file = photoInput.files[0];
                        if (!file) return;
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-thumbnail';
                            img.style.maxHeight = '150px';
                            photoPreview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });
        </script>
    @endpush
@endsection
