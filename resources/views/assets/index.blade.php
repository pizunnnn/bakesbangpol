@extends('layouts.app')

@section('title', 'Sistem Pengadaan & Inventaris BMD')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Sistem Pengadaan & Inventaris BMD</h1>
            <p class="text-muted mb-0">BAKESBANGPOL - SIMKAP ASSET SYSTEM</p>
        </div>
        <a href="{{ route('assets.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Ajukan Pengadaan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div
            class="card-header bg-dark text-white d-flex justify-content-between align-items-center rounded-4 rounded-bottom-0">
            <span class="fw-bold">Daftar BMD & Pengadaan Barang (Bakesbangpol)</span>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover align-middle m-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang / Reg</th>
                        <th>Nama & Spesifikasi Barang</th>
                        <th>Perolehan</th>
                        <th>Nilai (Rp)</th>
                        <th>Unit</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assets as $index => $asset)
                        <tr>
                            <td class="text-center fw-bold">{{ $assets->firstItem() + $index }}</td>
                            <td>
                                <span
                                    class="badge bg-secondary mb-1">{{ $asset->kode_barang ?? $asset->asset_code }}</span><br>
                                <small class="text-muted">Reg:
                                    {{ str_pad((string) ($asset->no_register ?? '1'), 4, '0', STR_PAD_LEFT) }}</small>
                            </td>
                            <td>
                                <strong>{{ $asset->nama_barang ?? ($asset->category ?? '-') }}</strong><br>
                                <small class="text-primary">{{ $asset->merk_tipe ?? '-' }}</small><br>
                                <small class="text-muted"
                                    style="font-size: 0.78rem;">{{ $asset->spesifikasi ?? '-' }}</small>
                            </td>
                            <td>
                                <small>{{ $asset->cara_perolehan ?? '-' }}</small><br>
                                <small class="text-muted">Tahun: {{ $asset->tahun_perolehan ?? '-' }}</small>
                            </td>
                            <td class="text-end fw-bold">
                                {{ $asset->nilai_perolehan ? number_format((float) $asset->nilai_perolehan, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-center fw-bold">{{ $asset->jumlah_unit ?? 1 }}</td>
                            <td class="text-center">
                                @php
                                    $keadaan = $asset->keadaan ?? 'B';
                                    $badge_kondisi =
                                        $keadaan === 'B'
                                            ? 'bg-success'
                                            : ($keadaan === 'KB'
                                                ? 'bg-warning text-dark'
                                                : 'bg-danger');
                                @endphp
                                <span class="badge {{ $badge_kondisi }}">{{ $keadaan }}</span>
                            </td>
                            <td class="text-center">
                                @if (($asset->status ?? '') === 'Disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-warning text-dark">Menunggu Approval</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('assets.edit', $asset) }}" class="btn btn-sm btn-outline-primary"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus data aset ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada data aset/pengadaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($assets->hasPages())
            <div class="card-footer bg-white">
                {{ $assets->links() }}
            </div>
        @endif
    </div>
@endsection
