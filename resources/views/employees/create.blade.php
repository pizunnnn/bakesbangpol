@extends('layouts.app')

@section('title', 'Tambah Pegawai')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold text-primary">Tambah Pegawai</h1>
            <p class="text-muted mb-0">Lengkapi data pegawai PNS, PPPK, atau PPPK Paruh Waktu Bakesbangpol di bawah ini.</p>
        </div>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary px-3 py-2">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white fw-bold rounded-4 rounded-bottom-0 py-3">
            <i class="bi bi-person-plus-fill me-2"></i>Form Data Pegawai
        </div>
        <div class="card-body p-4">
            <form action="{{ route('employees.store') }}" method="POST">
                @csrf

                <h6 class="text-primary fw-bold mb-3"><i class="bi bi-card-heading me-2"></i>Data Pribadi</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required placeholder="Nama lengkap pegawai">
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">NIP / NIK <span class="text-danger">*</span></label>
                        <input type="text" name="employee_number" id="employee_number" class="form-control @error('employee_number') is-invalid @enderror" value="{{ old('employee_number') }}" placeholder="Nomor NIP PNS / NIK PPPK">
                        <div class="form-text">Wajib diisi sesuai status kepegawaian.</div>
                        @error('employee_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Jenis Kelamin</label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Tempat Lahir</label>
                        <input type="text" name="birth_place" class="form-control @error('birth_place') is-invalid @enderror" value="{{ old('birth_place') }}" placeholder="Kota / Tempat Lahir">
                        @error('birth_place')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold text-muted small">Tanggal Lahir</label>
                        <div class="input-group">
                            <input type="text" name="birth_date" id="birth_date" class="form-control flatpickr @error('birth_date') is-invalid @enderror" value="{{ old('birth_date') }}" placeholder="YYYY-MM-DD">
                            <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                        </div>
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">No. Telepon / WhatsApp</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@contoh.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">Alamat Lengkap</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2" placeholder="Alamat domisili pegawai">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">
                <h6 class="text-primary fw-bold mb-3"><i class="bi bi-briefcase-fill me-2"></i>Data Kepegawaian</h6>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Status Pegawai <span class="text-danger">*</span></label>
                        <select name="status_pegawai" id="status_pegawai" class="form-select @error('status_pegawai') is-invalid @enderror" required>
                            <option value="">-- Pilih Status Pegawai --</option>
                            <option value="PNS" {{ old('status_pegawai') == 'PNS' ? 'selected' : '' }}>PNS (Pegawai Negeri Sipil)</option>
                            <option value="PPPK" {{ old('status_pegawai') == 'PPPK' ? 'selected' : '' }}>PPPK (Pegawai Pemerintah dengan Perjanjian Kerja)</option>
                            <option value="PPPK Paruh Waktu" {{ old('status_pegawai') == 'PPPK Paruh Waktu' ? 'selected' : '' }}>PPPK Paruh Waktu</option>
                        </select>
                        @error('status_pegawai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Pangkat / Golongan</label>
                        <input type="text" name="pangkat_golongan" class="form-control @error('pangkat_golongan') is-invalid @enderror" value="{{ old('pangkat_golongan') }}" placeholder="Contoh: Penata Muda / III/a">
                        @error('pangkat_golongan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Unit Kerja / Bidang</label>
                        <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror">
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Jabatan</label>
                        <select name="position_id" class="form-select @error('position_id') is-invalid @enderror">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                    {{ $position->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('position_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">TMT / Tanggal Bergabung</label>
                        <div class="input-group">
                            <input type="text" name="join_date" id="join_date" class="form-control flatpickr @error('join_date') is-invalid @enderror" value="{{ old('join_date') }}" placeholder="YYYY-MM-DD">
                            <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                        </div>
                        @error('join_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted small">Status Keaktifan</label>
                        <select name="employment_status" class="form-select @error('employment_status') is-invalid @enderror">
                            <option value="active" {{ old('employment_status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('employment_status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('employment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 pt-3 border-top mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-check-lg me-1"></i>Simpan Data Pegawai
                    </button>
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary px-4 py-2">
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
            if (typeof flatpickr !== 'undefined') {
                flatpickr('#birth_date, #join_date', {
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altInputClass: 'form-control',
                    altFormat: 'd/m/Y',
                    allowInput: true,
                    locale: 'id',
                    static: true
                });
            }
        });
    </script>
@endpush
