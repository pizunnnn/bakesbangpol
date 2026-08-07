@extends('layouts.app')

@section('title', 'Sistem Pengadaan & Inventaris BMD')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Sistem Pengadaan & Inventaris BMD</h1>
            <p class="text-muted mb-0">BAKESBANGPOL - SIMKAP ASSET SYSTEM</p>
        </div>
        <a href="{{ route('assets.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Aset
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
            class="card-header bg-dark text-white d-flex justify-content-between align-items-center rounded-4 rounded-bottom-0 flex-wrap gap-2">
            <span class="fw-bold">Daftar BMD & Pengadaan Barang (Bakesbangpol)</span>
            <form action="{{ route('assets.index') }}" method="GET" class="d-flex flex-wrap align-items-center gap-1"
                role="search">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control form-control-sm me-1"
                    placeholder="Cari nama barang, kode, merk..." aria-label="Cari aset">
                <select name="status" class="form-select form-select-sm me-1 w-auto" aria-label="Filter status aset"
                    onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Tersedia" {{ ($status ?? '') === 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Dipinjam" {{ ($status ?? '') === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                </select>
                <select name="bidang" class="form-select form-select-sm me-1 w-auto" aria-label="Filter bidang aset"
                    onchange="this.form.submit()">
                    <option value="">Semua Bidang</option>
                    @foreach ($bidangList ?? [] as $b)
                        <option value="{{ $b }}" {{ ($bidang ?? '') === $b ? 'selected' : '' }}>
                            {{ $b }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-light">
                    <i class="bi bi-search"></i>
                </button>
                @if (!empty($search) || !empty($status) || !empty($bidang))
                    <a href="{{ route('assets.index') }}" class="btn btn-sm btn-outline-light ms-1"
                        title="Hapus filter & pencarian">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </form>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover align-middle m-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Kode Barang / Reg</th>
                        <th>Nama & Spesifikasi Barang</th>
                        <th>Perolehan</th>
                        <th>Nilai (Rp)</th>
                        <th>Unit</th>
                        <th>Kondisi</th>
                        <th>Bidang</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assets as $index => $asset)
                        <tr>
                            <td class="text-center fw-bold">{{ $assets->firstItem() + $index }}</td>
                            <td class="text-center">
                                @if ($asset->photo)
                                    <img src="{{ asset('storage/' . $asset->photo) }}"
                                        alt="Foto {{ $asset->nama_barang }}" class="rounded asset-thumb"
                                        data-photo="{{ asset('storage/' . $asset->photo) }}"
                                        data-name="{{ $asset->nama_barang ?? $asset->asset_code }}"
                                        style="width:60px;height:60px;object-fit:cover;cursor:zoom-in;">
                                @else
                                    <span class="text-muted"><i class="bi bi-image fs-4"></i></span>
                                @endif
                            </td>
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
                                @if ($asset->bidang)
                                    <span class="badge bg-info text-dark">{{ $asset->bidang }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if (($asset->status ?? 'Tersedia') === 'Dipinjam')
                                    <span class="badge bg-danger">Dipinjam</span>
                                    @if ($asset->currentEmployee)
                                        <div class="small text-danger mt-1">
                                            <i class="bi bi-person"></i> {{ $asset->currentEmployee->full_name }}
                                            @if ($asset->currentEmployee->unit_kerja && $asset->currentEmployee->unit_kerja !== '-')
                                                <br><small
                                                    class="text-muted">{{ $asset->currentEmployee->unit_kerja }}</small>
                                            @endif
                                        </div>
                                    @endif
                                @else
                                    <span class="badge bg-success">Tersedia</span>
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
                            <td colspan="11" class="text-center text-muted py-4">Belum ada data aset/pengadaan.</td>
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

    <!-- Modal Zoom Foto -->
    <div class="modal fade" id="photoZoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h6 class="modal-title" id="zoomModalTitle">Zoom Foto</h6>
                    <div class="d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="zoomInBtn" title="Perbesar">
                            <i class="bi bi-zoom-in"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="zoomOutBtn"
                            title="Perkecil">
                            <i class="bi bi-zoom-out"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-dark" id="zoomResetBtn" title="Reset">
                            <i class="bi bi-arrows-fullscreen"></i>
                        </button>
                        <button type="button" class="btn-close ms-2" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                </div>
                <div class="modal-body p-0" style="background:#000;overflow:hidden;height:70vh;">
                    <div id="zoomContainer" style="width:100%;height:100%;overflow:hidden;cursor:grab;">
                        <img id="zoomImage" src="" alt="Foto aset"
                            style="width:100%;height:100%;object-fit:contain;transform-origin:center;transition:transform 0.1s ease;">
                    </div>
                </div>
                <div class="modal-footer small text-muted">
                    <span><i class="bi bi-mouse me-1"></i>Gulir untuk zoom, klik & geser untuk menggeser foto</span>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalEl = document.getElementById('photoZoomModal');
                const img = document.getElementById('zoomImage');
                const container = document.getElementById('zoomContainer');
                const title = document.getElementById('zoomModalTitle');
                let scale = 1;
                let posX = 0;
                let posY = 0;
                let isDragging = false;
                let startX = 0;
                let startY = 0;

                function applyTransform() {
                    img.style.transform = `translate(${posX}px, ${posY}px) scale(${scale})`;
                }

                function resetZoom() {
                    scale = 1;
                    posX = 0;
                    posY = 0;
                    applyTransform();
                }

                function zoomBy(factor) {
                    scale = Math.min(5, Math.max(1, scale + factor));
                    applyTransform();
                }

                // Klik thumbnail
                document.querySelectorAll('.asset-thumb').forEach(function(thumb) {
                    thumb.addEventListener('click', function() {
                        img.src = thumb.dataset.photo;
                        title.textContent = thumb.dataset.name || 'Zoom Foto';
                        resetZoom();
                        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        modal.show();
                    });
                });

                // Tombol zoom
                document.getElementById('zoomInBtn').addEventListener('click', function() {
                    zoomBy(0.5);
                });
                document.getElementById('zoomOutBtn').addEventListener('click', function() {
                    zoomBy(-0.5);
                });
                document.getElementById('zoomResetBtn').addEventListener('click', resetZoom);

                // Scroll untuk zoom
                container.addEventListener('wheel', function(e) {
                    e.preventDefault();
                    zoomBy(e.deltaY < 0 ? 0.3 : -0.3);
                }, {
                    passive: false
                });

                // Drag untuk geser
                container.addEventListener('mousedown', function(e) {
                    isDragging = true;
                    startX = e.clientX - posX;
                    startY = e.clientY - posY;
                    container.style.cursor = 'grabbing';
                });
                document.addEventListener('mousemove', function(e) {
                    if (!isDragging) return;
                    posX = e.clientX - startX;
                    posY = e.clientY - startY;
                    applyTransform();
                });
                document.addEventListener('mouseup', function() {
                    isDragging = false;
                    container.style.cursor = 'grab';
                });

                // Reset saat modal ditutup
                modalEl.addEventListener('hidden.bs.modal', resetZoom);
            });
        </script>
    @endpush
@endsection
