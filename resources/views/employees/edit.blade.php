@extends('layouts.app')

@section('title', 'Edit Pegawai')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Edit Data Pegawai</h1>
            <p class="text-muted mb-0">{{ $employee->full_name }}</p>
        </div>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-warning text-dark fw-bold rounded-4 rounded-bottom-0">
            Edit Data Pegawai
        </div>
        <div class="card-body">
            <form action="{{ route('employees.update', $employee) }}" method="POST">
                @csrf
                @method('PUT')

                <h6 class="text-primary fw-bold mb-3">Data Pribadi</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Nama Lengkap</label>
                        <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                            value="{{ old('full_name', $employee->full_name) }}" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3" id="box_nip">
                        <label class="form-label text-muted small">NIP / NIPKKK / ID <span
                                class="text-danger">*</span></label>
                        <input type="text" name="employee_number" id="employee_number"
                            class="form-control @error('employee_number') is-invalid @enderror"
                            value="{{ old('employee_number', $employee->employee_number) }}">
                        <div class="form-text">Wajib diisi untuk Pegawai Tetap / P3K. Tidak wajib untuk Outsourcing.</div>
                        @error('employee_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted small">Jenis Kelamin</label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted small">Tempat Lahir</label>
                        <input type="text" name="birth_place"
                            class="form-control @error('birth_place') is-invalid @enderror"
                            value="{{ old('birth_place', $employee->birth_place) }}">
                        @error('birth_place')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted small">Tanggal Lahir</label>
                        <input type="date" name="birth_date"
                            class="form-control @error('birth_date') is-invalid @enderror"
                            value="{{ old('birth_date', $employee->birth_date ? $employee->birth_date->format('Y-m-d') : '') }}">
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">No. Telepon</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $employee->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $employee->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Alamat</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $employee->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>
                <h6 class="text-primary fw-bold mb-3">Data Kepegawaian</h6>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Status Pegawai</label>
                        <select name="status_pegawai" id="status_pegawai"
                            class="form-select @error('status_pegawai') is-invalid @enderror"
                            onchange="checkStatusPegawai()">
                            <option value="">-- Pilih Status --</option>
                            <option value="Pegawai Tetap"
                                {{ old('status_pegawai', $employee->status_pegawai) == 'Pegawai Tetap' ? 'selected' : '' }}>
                                Pegawai Tetap (PNS)</option>
                            <option value="P3K Paruh Waktu"
                                {{ old('status_pegawai', $employee->status_pegawai) == 'P3K Paruh Waktu' ? 'selected' : '' }}>
                                P3K Paruh Waktu</option>
                            <option value="Outsourcing"
                                {{ old('status_pegawai', $employee->status_pegawai) == 'Outsourcing' ? 'selected' : '' }}>
                                Outsourcing</option>
                        </select>
                        @error('status_pegawai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Status Keaktifan</label>
                        <select name="employment_status"
                            class="form-select @error('employment_status') is-invalid @enderror">
                            <option value="active"
                                {{ old('employment_status', $employee->employment_status) == 'active' ? 'selected' : '' }}>
                                Aktif</option>
                            <option value="inactive"
                                {{ old('employment_status', $employee->employment_status) == 'inactive' ? 'selected' : '' }}>
                                Tidak Aktif</option>
                        </select>
                        @error('employment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3" id="box_unit_kerja">
                        <label class="form-label text-muted small">Unit Kerja / Bidang</label>
                        <select name="unit_kerja" id="unit_kerja"
                            class="form-select @error('unit_kerja') is-invalid @enderror">
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->code }}"
                                    {{ old('unit_kerja', $employee->unit_kerja) == $department->code ? 'selected' : '' }}>
                                    {{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('unit_kerja')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Jabatan</label>
                        <select name="position_id" class="form-select @error('position_id') is-invalid @enderror">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}"
                                    {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
                                    {{ $position->name }}</option>
                            @endforeach
                        </select>
                        @error('position_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Tanggal Bergabung</label>
                    <input type="date" name="join_date" class="form-control @error('join_date') is-invalid @enderror"
                        value="{{ old('join_date', $employee->join_date ? $employee->join_date->format('Y-m-d') : '') }}">
                    @error('join_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function checkStatusPegawai() {
            var status = document.getElementById('status_pegawai').value;
            var boxUnitKerja = document.getElementById('box_unit_kerja');
            var unitKerja = document.getElementById('unit_kerja');
            var boxNip = document.getElementById('box_nip');
            var nip = document.getElementById('employee_number');

            if (status === 'Outsourcing') {
                boxUnitKerja.style.display = 'none';
                unitKerja.value = '';
                boxNip.style.display = 'none';
                nip.value = '';
            } else {
                boxUnitKerja.style.display = 'block';
                boxNip.style.display = 'block';
            }
        }
        checkStatusPegawai();
    </script>
@endpush
