@extends('layouts.app')

@section('title', 'Form Laporan Kinerja PPPK')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold">Form Laporan Kinerja PPPK</h1>
            <small class="text-muted">Badan Kesatuan Bangsa dan Politik Provinsi Jawa Barat</small>
        </div>
        @if ($selected)
            <a href="{{ route('reviews.print', ['periode' => $selected->id]) }}" target="_blank"
                class="btn btn-success fw-bold">
                <i class="bi bi-printer me-1"></i>Preview & Cetak Laporan (PDF)
            </a>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Kolom kiri: pilih periode & form data pegawai baru --}}
        <div class="col-lg-4 mb-4">
            {{-- Pilih Periode --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white fw-bold">
                    Pilih Periode Laporan
                </div>
                <div class="card-body">
                    @if ($periods->count())
                        <div class="list-group">
                            @foreach ($periods as $period)
                                <a href="{{ route('reviews.index', ['periode' => $period->id]) }}"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $selected && $selected->id === $period->id ? 'active' : '' }}">
                                    <span>
                                        <strong>{{ $period->nama }}</strong><br>
                                        <small>{{ $period->evaluation_period }}</small>
                                    </span>
                                    @if ($selected && $selected->id === $period->id)
                                        <i class="bi bi-check-circle-fill"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Belum ada periode. Buat periode baru di bawah.</p>
                    @endif
                </div>
            </div>

            {{-- Form Data periode baru --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    + Buat / Ubah Data Laporan Kinerja
                </div>
                <div class="card-body">
                    <form action="{{ route('reviews.period.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Pegawai</label>
                            <input type="text" name="nama" class="form-control form-control-sm"
                                value="{{ old('nama', $selected->nama ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">NIPKKK</label>
                            <input type="text" name="nipkkk" class="form-control form-control-sm"
                                value="{{ old('nipkkk', $selected->nipkkk ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control form-control-sm"
                                value="{{ old('jabatan', $selected->jabatan ?? '') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-7 mb-3">
                                <label class="form-label small fw-bold">Periode Bulan</label>
                                <select name="periode_bulan" class="form-select form-select-sm" required>
                                    @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $bulan)
                                        <option value="{{ $bulan }}"
                                            {{ old('periode_bulan', $selected->periode_bulan ?? '') == $bulan ? 'selected' : '' }}>
                                            {{ $bulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-5 mb-3">
                                <label class="form-label small fw-bold">Tahun</label>
                                <input type="number" name="periode_tahun" class="form-control form-control-sm"
                                    value="{{ old('periode_tahun', $selected->periode_tahun ?? now()->year) }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">PPTK (Nama)</label>
                            <input type="text" name="pptk_nama" class="form-control form-control-sm"
                                value="{{ old('pptk_nama', $selected->pptk_nama ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">NIP PPTK</label>
                            <input type="text" name="pptk_nip" class="form-control form-control-sm"
                                value="{{ old('pptk_nip', $selected->pptk_nip ?? '') }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                            <i class="bi bi-save me-1"></i>Simpan Data Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Kolom kanan: detail pegawai & form tambah kegiatan + daftar kegiatan --}}
        <div class="col-lg-8 mb-4">
            @if ($selected)
                {{-- Detail Pegawai & PPTK --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white fw-bold">
                        Profile Pegawai & Pejabat Penilai (PPTK)
                    </div>
                    <div class="card-body bg-light">
                        <div class="row small">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Nama Pegawai:</strong> {{ $selected->nama }}</p>
                                <p class="mb-1"><strong>NIPKKK:</strong> {{ $selected->nipkkk }}</p>
                                <p class="mb-1"><strong>Jabatan:</strong> {{ $selected->jabatan }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Periode Laporan:</strong> {{ $selected->evaluation_period }}</p>
                                <p class="mb-1"><strong>PPTK:</strong> {{ $selected->pptk_nama }}</p>
                                <p class="mb-1"><strong>NIP PPTK:</strong> {{ $selected->pptk_nip }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Form Tambah Kegiatan --}}
                    <div class="col-md-5 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white fw-bold">
                                + Tambah Kegiatan Harian
                            </div>
                            <div class="card-body">
                                <form action="{{ route('reviews.kegiatan.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="pppk_review_id" value="{{ $selected->id }}">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Tanggal Kegiatan</label>
                                        <input type="date" name="kegiatan_date" class="form-control form-control-sm"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label small fw-bold">Jam Mulai</label>
                                            <input type="text" name="waktu_mulai" class="form-control form-control-sm"
                                                value="08.00" required>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label class="form-label small fw-bold">Jam Selesai</label>
                                            <input type="text" name="waktu_selesai"
                                                class="form-control form-control-sm" value="16.00" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Uraian Kegiatan</label>
                                        <textarea name="uraian" class="form-control form-control-sm" rows="4"
                                            placeholder="Jelaskan detail kegiatan kinerja yang dilakukan hari ini..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                                        <i class="bi bi-plus-lg me-1"></i>Simpan Kegiatan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Daftar Kegiatan --}}
                    <div class="col-md-7 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white fw-bold">
                                Daftar Uraian Kegiatan (Periode {{ $selected->evaluation_period }})
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover table-striped m-0 align-middle small">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Hari / Tanggal</th>
                                            <th class="text-center">Waktu</th>
                                            <th>Uraian Kegiatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($selected->details as $idx => $row)
                                            <tr>
                                                <td class="text-center fw-bold">{{ $idx + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($row->kegiatan_date)->format('d/m/Y') }}</td>
                                                <td class="text-center">{{ $row->kegiatan_time }}</td>
                                                <td>{{ $row->uraian }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    Belum ada kegiatan untuk periode ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center text-muted py-5">
                        <i class="bi bi-file-earmark-text display-4 d-block mb-3"></i>
                        Silakan buat periode laporan terlebih dahulu di kolom kiri.
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
