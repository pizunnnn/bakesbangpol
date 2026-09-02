<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Hak Tunjangan - {{ $employee->full_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: Arial, sans-serif;
            color: #1e293b;
        }
        .slip-container {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .header-title {
            text-align: center;
            border-bottom: 2px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header-title h4 {
            margin: 0;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-title h5 {
            margin: 3px 0;
            font-size: 13px;
            font-weight: bold;
        }
        .header-title p {
            margin: 0;
            font-size: 11px;
            color: #475569;
        }
        .doc-name {
            text-align: center;
            margin-bottom: 25px;
        }
        .doc-name h5 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .doc-name p {
            margin: 3px 0 0 0;
            font-size: 11px;
            color: #64748b;
        }
        .signature-box {
            width: 240px;
            float: right;
            text-align: center;
            font-size: 11.5px;
            margin-top: 30px;
        }
        
        /* ATURAN CETAK (PRINT) TANPA WARNA (MONOCHROME) */
        @media print {
            @page {
                size: A4 portrait;
                margin: 15mm 12mm 15mm 12mm;
            }
            body {
                background: #fff !important;
                color: #000 !important;
                padding: 0 !important;
                font-size: 10pt !important;
            }
            .slip-container {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
                border: none !important;
            }
            .no-print {
                display: none !important;
            }
            .header-title {
                border-bottom: 2px solid #000 !important;
            }
            .header-title h4, .header-title h5, .header-title p {
                color: #000 !important;
            }
            .card {
                background: transparent !important;
                border: 1px solid #000 !important;
            }
            .table {
                color: #000 !important;
                border-color: #000 !important;
            }
            .table th, .table td {
                border-color: #000 !important;
                color: #000 !important;
                background: transparent !important;
            }
            .table thead th {
                background-color: #e5e7eb !important;
                color: #000 !important;
            }
            .badge {
                background: transparent !important;
                color: #000 !important;
                border: none !important;
                padding: 0 !important;
                font-size: 9.5pt !important;
                font-weight: normal !important;
            }
            .text-muted, .text-primary, .text-success, .text-danger, .text-dark {
                color: #000 !important;
            }
        }
    </style>
</head>
<body>

    <div class="container my-3 no-print">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('employees.show', ['employee' => $employee, 'tab' => 'allowance']) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Detail Pegawai
            </a>
            <button onclick="window.print()" class="btn btn-sm btn-primary">
                <i class="bi bi-printer me-1"></i>Cetak Surat Keterangan Tunjangan
            </button>
        </div>
    </div>

    <div class="slip-container">
        <!-- HEADER KOP -->
        <div class="header-title">
            <h4>Pemerintah Provinsi Jawa Barat</h4>
            <h5>Badan Kesatuan Bangsa dan Politik</h5>
            <p>Jl. Supratman No. 44, Cihapit, Kec. Bandung Wetan, Kota Bandung, Jawa Barat 40114</p>
        </div>

        <div class="doc-name">
            <h5>SURAT KETERANGAN HAK KEPEMILIKAN TUNJANGAN PEGAWAI</h5>
            <p>Nomor: 800 / SKT / BAKESBANGPOL / {{ date('Y') }}</p>
        </div>

        <p style="font-size: 12px; line-height: 1.6;">
            Yang bertanda tangan di bawah ini menerangkan bahwa pegawai Badan Kesatuan Bangsa dan Politik Provinsi Jawa Barat berikut ini:
        </p>

        <!-- BIODATA PRIBADI PEMILIK TUNJANGAN -->
        <div class="card bg-light border rounded-3 p-3 mb-4">
            <table class="table table-borderless table-sm mb-0" style="font-size: 12px;">
                <tr>
                    <td style="width: 170px;" class="text-muted">Nama Lengkap</td>
                    <td style="width: 260px;">: <strong>{{ $employee->full_name }}</strong></td>
                    <td style="width: 140px;" class="text-muted">NPWP</td>
                    <td>: <strong>{{ $allowance->npwp ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">NIP / Status Pegawai</td>
                    <td>: {{ $employee->employee_number }} (<strong>{{ $employee->status_pegawai }}</strong>)</td>
                    <td class="text-muted">Rekening Bank</td>
                    <td>: <strong>{{ $allowance->nomor_rekening ?? '-' }}</strong> ({{ $allowance->nama_bank ?? 'Bank bjb' }})</td>
                </tr>
                <tr>
                    <td class="text-muted">Pangkat / Golongan</td>
                    <td>: {{ $employee->pangkat_golongan ?: '-' }}</td>
                    <td class="text-muted">Status / Tanggungan</td>
                    <td>: Status <strong>{{ ($allowance && $allowance->status_kawin === 'K') ? 'Kawin' : 'Tidak Kawin' }}</strong> | Kode: <strong>{{ $allowance->kd_jiwa ?? '1100' }}</strong> (<strong>{{ $allowance->jml_jiwa ?? 1 }} Jiwa</strong>)</td>
                </tr>
                <tr>
                    <td class="text-muted">Jabatan / Unit Kerja</td>
                    <td>: {{ $employee->position->name ?? $employee->unit_kerja ?? '-' }}</td>
                    <td class="text-muted">Masa Kerja (Masker)</td>
                    <td>: <strong>{{ $allowance->masker ?? $employee->masa_kerja_tahun ?? '-' }} Tahun</strong> | TMT: {{ $allowance && $allowance->tmt_sk ? $allowance->tmt_sk->format('d/m/Y') : '-' }}</td>
                </tr>
            </table>
        </div>

        <p style="font-size: 12px; font-weight: bold; margin-bottom: 8px;">
            Terdaftar sebagai penerima hak tunjangan kepegawaian aktif dengan rincian status hak sebagai berikut:
        </p>

        <!-- DAFTAR HAK TUNJANGAN YANG DITERIMA -->
        <table class="table table-bordered table-sm mb-4" style="font-size: 12px;">
            <thead class="table-light">
                <tr>
                    <th style="width: 35px;" class="text-center">No</th>
                    <th>Jenis Hak Tunjangan</th>
                    <th style="width: 150px;" class="text-center">Status Kepemilikan</th>
                    <th>Keterangan / Dasar Hak</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td><strong>Tunjangan Suami / Istri</strong></td>
                    <td class="text-center">
                        @if($allowance?->has_tj_suami_istri)
                            <span class="badge bg-success">Berhak Menerima</span>
                        @else
                            <span class="badge bg-secondary">Tidak Menerima</span>
                        @endif
                    </td>
                    <td>{{ $allowance?->has_tj_suami_istri ? 'Pegawai berstatus Kawin (1 Pasangan Sah)' : 'Tidak berstatus kawin / tidak ada tanggungan pasangan' }}</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td><strong>Tunjangan Anak</strong></td>
                    <td class="text-center">
                        @if($allowance?->has_tj_anak)
                            <span class="badge bg-info text-dark">Berhak ({{ $allowance->jumlah_anak_tanggungan }} Anak)</span>
                        @else
                            <span class="badge bg-secondary">Tidak Menerima</span>
                        @endif
                    </td>
                    <td>{{ $allowance?->has_tj_anak ? 'Tanggungan ' . $allowance->jumlah_anak_tanggungan . ' anak kandung/sah memenuhi syarat usia & sekolah' : 'Tidak ada anak tanggungan' }}</td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td><strong>Tunjangan Jabatan Struktural</strong></td>
                    <td class="text-center">
                        @if($allowance?->has_tj_struktural)
                            <span class="badge bg-warning text-dark">Pejabat Struktural</span>
                        @else
                            <span class="badge bg-secondary">Bukan Struktural</span>
                        @endif
                    </td>
                    <td>{{ $allowance?->has_tj_struktural ? 'Menduduki jabatan struktural (' . ($employee->position->name ?? 'Eselon') . ')' : 'Tidak menduduki jabatan struktural' }}</td>
                </tr>
                <tr>
                    <td class="text-center">4</td>
                    <td><strong>Tunjangan Jabatan Fungsional</strong></td>
                    <td class="text-center">
                        @if($allowance?->has_tj_fungsional)
                            <span class="badge bg-primary text-white">Pejabat Fungsional</span>
                        @else
                            <span class="badge bg-secondary">-</span>
                        @endif
                    </td>
                    <td>{{ $allowance?->has_tj_fungsional ? 'Menduduki jabatan fungsional tertentu/keahlian' : 'Bukan jabatan fungsional tertentu' }}</td>
                </tr>
                <tr>
                    <td class="text-center">5</td>
                    <td><strong>Tunjangan Beras</strong></td>
                    <td class="text-center">
                        <span class="badge bg-success">Berhak ({{ $allowance->jml_jiwa ?? 1 }} Jiwa)</span>
                    </td>
                    <td>Tunjangan pangan beras untuk {{ $allowance->jml_jiwa ?? 1 }} jiwa keluarga yang terdaftar dalam Kartu Keluarga / SIMPEG</td>
                </tr>
                <tr>
                    <td class="text-center">6</td>
                    <td><strong>Tunjangan Umum & Tambahan</strong></td>
                    <td class="text-center">
                        <span class="badge bg-success">Terdaftar</span>
                    </td>
                    <td>Tunjangan umum pegawai sesuai ketentuan regulasi kepegawaian daerah</td>
                </tr>
                <tr>
                    <td class="text-center">7</td>
                    <td><strong>Iuran Wajib Pegawai & Jaminan (IWP)</strong></td>
                    <td class="text-center">
                        <span class="badge bg-warning text-dark">Terdaftar IWP 8% & 1%</span>
                    </td>
                    <td>Kepesertaan jaminan pensiun, hari tua, dan BPJS Kesehatan resmi terdaftar</td>
                </tr>
            </tbody>
        </table>

        <p style="font-size: 12px; line-height: 1.6;">
            Demikian Surat Keterangan Hak Kepemilikan Tunjangan ini diterbitkan untuk dipergunakan sebagaimana mestinya.
        </p>

        <!-- TANDA TANGAN -->
        <div class="signature-box">
            <p class="mb-1">Bandung, {{ date('d F Y') }}</p>
            <p class="mb-5">Pengelola Kepegawaian & Tunjangan</p>
            <p class="fw-bold mb-0">________________________</p>
            <p class="text-muted small">NIP. .....................................</p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>