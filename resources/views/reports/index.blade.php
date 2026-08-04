@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h4 mb-0">Laporan</h1>
                <div class="btn-group">
                    <button class="btn btn-outline-primary">Export PDF</button>
                    <button class="btn btn-outline-primary">Export Excel</button>
                    <button class="btn btn-outline-primary">Print</button>
                </div>
            </div>
            <form class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Departemen</label>
                    <select class="form-select">
                        <option>Semua</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <select class="form-select">
                        <option>2026</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Periode Evaluasi</label>
                    <select class="form-select">
                        <option>Semester 1</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select">
                        <option>Semua Status</option>
                    </select>
                </div>
            </form>
            <p class="text-muted mb-0">Filter laporan pegawai, aset, dan evaluasi PPPK akan dihubungkan ke export PDF,
                Excel, dan print pada iterasi berikutnya.</p>
        </div>
    </div>
@endsection
