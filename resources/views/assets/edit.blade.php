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
            <form action="{{ route('assets.update', $asset) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label text-muted small">Kategori & Kode Barang (Permendagri)</label>
                    <select name="kode_barang" class="form-select @error('kode_barang') is-invalid @enderror" required>
                        <option value="">-- Pilih Kode Barang --</option>
                        <option value="1.3.2.02.01.02.003"
                            {{ old('kode_barang', $asset->kode_barang) == '1.3.2.02.01.02.003' ? 'selected' : '' }}>
                            1.3.2.02.01.02.003 - Kendaraan Mini Bus</option>
                        <option value="1.3.2.01.03.04.002"
                            {{ old('kode_barang', $asset->kode_barang) == '1.3.2.01.03.04.002' ? 'selected' : '' }}>
                            1.3.2.01.03.04.002 - Portable Generating Set</option>
                        <option value="1.3.2.05.01.01.001"
                            {{ old('kode_barang', $asset->kode_barang) == '1.3.2.05.01.01.001' ? 'selected' : '' }}>
                            1.3.2.05.01.01.001 - Personal Computer / Laptop</option>
                        <option value="1.3.2.05.02.01.004"
                            {{ old('kode_barang', $asset->kode_barang) == '1.3.2.05.02.01.004' ? 'selected' : '' }}>
                            1.3.2.05.02.01.004 - Printer / Scanner</option>
                    </select>
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
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="Menunggu Approval"
                            {{ old('status', $asset->status) == 'Menunggu Approval' ? 'selected' : '' }}>Menunggu Approval
                        </option>
                        <option value="Disetujui" {{ old('status', $asset->status) == 'Disetujui' ? 'selected' : '' }}>
                            Disetujui</option>
                    </select>
                    @error('status')
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
@endsection
