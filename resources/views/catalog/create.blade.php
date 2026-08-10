@extends('layouts.app')

@section('title', 'Tambah Katalog Barang')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Tambah Katalog Barang</h1>
            <p class="text-muted mb-0">Tambahkan kategori & kode barang (format Permendagri)</p>
        </div>
        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white fw-bold rounded-4 rounded-bottom-0">
            Form Tambah Katalog Barang
        </div>
        <div class="card-body">
            <form action="{{ route('catalog.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label text-muted small">Kode Barang (Permendagri)</label>
                    <input type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror"
                        placeholder="Contoh: 1.3.2.05.01.01.001" value="{{ old('kode_barang') }}" required>
                    <div class="form-text">Gunakan format kode barang sesuai Permendagri.</div>
                    @error('kode_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror"
                        placeholder="Contoh: Personal Computer / Laptop" value="{{ old('nama_barang') }}" required>
                    @error('nama_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-check-lg me-1"></i>Simpan Katalog
                    </button>
                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary px-4 py-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
