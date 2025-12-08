<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Laporan Hasil Pengelolaan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        
        .header h2 {
            margin: 10px 0;
            font-size: 16px;
            color: #666;
        }
        
        .section {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            font-weight: bold;
            padding: 5px 10px 5px 0;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px 0;
            vertical-align: top;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-berhasil {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-sebagian {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-gagal {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .efficiency-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        
        .efficiency-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
            color: white;
        }
        
        .efficiency-high {
            background-color: #28a745;
        }
        
        .efficiency-medium {
            background-color: #ffc107;
            color: #333;
        }
        
        .efficiency-low {
            background-color: #dc3545;
        }
        
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #ddd;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -23px;
            top: 5px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #007bff;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>DETAIL LAPORAN HASIL PENGELOLAAN</h1>
        <h2>{{ $laporan->perusahaan->nama_perusahaan }}</h2>
        <p>ID Laporan: #{{ $laporan->id }}</p>
    </div>

    <!-- Informasi Pengelolaan -->
    <div class="section">
        <div class="section-title">Informasi Pengelolaan Limbah</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Jenis Limbah:</div>
                <div class="info-value">
                    {{ $laporan->pengelolaanLimbah->jenisLimbah->nama }}<br>
                    <small>Kode: {{ $laporan->pengelolaanLimbah->jenisLimbah->kode_limbah }}</small>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Penyimpanan Asal:</div>
                <div class="info-value">
                    {{ $laporan->pengelolaanLimbah->penyimpanan->nama_penyimpanan }}<br>
                    <small>Lokasi: {{ $laporan->pengelolaanLimbah->penyimpanan->lokasi }}</small>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Mulai:</div>
                <div class="info-value">{{ $laporan->pengelolaanLimbah->tanggal_mulai->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Selesai:</div>
                <div class="info-value">{{ $laporan->tanggal_selesai->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Vendor:</div>
                <div class="info-value">{{ $laporan->pengelolaanLimbah->vendor->nama_perusahaan ?? 'Internal' }}</div>
            </div>
        </div>
    </div>

    <!-- Hasil Pengelolaan -->
    <div class="section">
        <div class="section-title">Hasil Pengelolaan</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Jumlah Awal:</div>
                <div class="info-value">{{ number_format($laporan->pengelolaanLimbah->jumlah_dikelola, 2) }} {{ $laporan->satuan }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Jumlah Berhasil Dikelola:</div>
                <div class="info-value">{{ number_format($laporan->jumlah_berhasil_dikelola, 2) }} {{ $laporan->satuan }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Jumlah Residu:</div>
                <div class="info-value">{{ number_format($laporan->jumlah_residu ?? 0, 2) }} {{ $laporan->satuan }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status Hasil:</div>
                <div class="info-value">
                    @php
                        $statusClass = match($laporan->status_hasil) {
                            'berhasil' => 'status-berhasil',
                            'sebagian_berhasil' => 'status-sebagian',
                            'gagal' => 'status-gagal',
                            default => ''
                        };
                        $statusName = match($laporan->status_hasil) {
                            'berhasil' => 'Berhasil',
                            'sebagian_berhasil' => 'Sebagian Berhasil',
                            'gagal' => 'Gagal',
                            default => ucfirst($laporan->status_hasil)
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $statusName }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Efisiensi Pengelolaan -->
    <div class="section">
        <div class="section-title">Efisiensi Pengelolaan</div>
        <div class="efficiency-section">
            @php
                $efisiensiClass = $efisiensi >= 80 ? 'efficiency-high' : ($efisiensi >= 60 ? 'efficiency-medium' : 'efficiency-low');
            @endphp
            <div class="efficiency-circle {{ $efisiensiClass }}">
                {{ number_format($efisiensi, 1) }}%
            </div>
            <p>
                <strong>Tingkat Efisiensi: 
                @if($efisiensi >= 80)
                    Sangat Baik
                @elseif($efisiensi >= 60)
                    Baik
                @else
                    Perlu Perbaikan
                @endif
                </strong>
            </p>
            <p>
                Dari {{ number_format($laporan->pengelolaanLimbah->jumlah_dikelola, 2) }} {{ $laporan->satuan }} limbah yang diproses,
                {{ number_format($laporan->jumlah_berhasil_dikelola, 2) }} {{ $laporan->satuan }} berhasil dikelola dengan baik.
            </p>
        </div>
    </div>

    <!-- Informasi Tambahan -->
    @if($laporan->biaya_aktual || $laporan->nomor_sertifikat || $laporan->metode_disposal_akhir)
    <div class="section">
        <div class="section-title">Informasi Tambahan</div>
        <div class="info-grid">
            @if($laporan->biaya_aktual)
            <div class="info-row">
                <div class="info-label">Biaya Aktual:</div>
                <div class="info-value">Rp {{ number_format($laporan->biaya_aktual, 0, ',', '.') }}</div>
            </div>
            @endif
            
            @if($laporan->nomor_sertifikat)
            <div class="info-row">
                <div class="info-label">Nomor Sertifikat:</div>
                <div class="info-value">{{ $laporan->nomor_sertifikat }}</div>
            </div>
            @endif
            
            @if($laporan->metode_disposal_akhir)
            <div class="info-row">
                <div class="info-label">Metode Disposal:</div>
                <div class="info-value">{{ $laporan->metode_disposal_akhir }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Timeline Proses -->
    <div class="section">
        <div class="section-title">Timeline Proses</div>
        <div class="timeline">
            <div class="timeline-item">
                <strong>Mulai Pengelolaan</strong><br>
                {{ $laporan->pengelolaanLimbah->tanggal_mulai->format('d/m/Y H:i') }}<br>
                <small>Pengelolaan limbah dimulai</small>
            </div>
            
            <div class="timeline-item">
                <strong>Selesai Pengelolaan</strong><br>
                {{ $laporan->tanggal_selesai->format('d/m/Y H:i') }}<br>
                <small>Proses pengelolaan selesai</small>
            </div>
            
            <div class="timeline-item">
                <strong>Laporan Dibuat</strong><br>
                {{ $laporan->created_at->format('d/m/Y H:i') }}<br>
                <small>Laporan hasil pengelolaan dibuat</small>
            </div>
            
            @if($laporan->updated_at != $laporan->created_at)
            <div class="timeline-item">
                <strong>Terakhir Diperbarui</strong><br>
                {{ $laporan->updated_at->format('d/m/Y H:i') }}<br>
                <small>Laporan terakhir diperbarui</small>
            </div>
            @endif
        </div>
    </div>

    <!-- Keterangan -->
    @if($laporan->keterangan)
    <div class="section">
        <div class="section-title">Keterangan</div>
        <p>{{ $laporan->keterangan }}</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>
            <strong>Laporan Detail Hasil Pengelolaan Limbah</strong><br>
            Digenerate pada: {{ $generated_at->format('d/m/Y H:i:s') }} WIB<br>
            Perusahaan: {{ $laporan->perusahaan->nama_perusahaan }}<br>
            Sistem Pengelolaan Limbah
        </p>
    </div>
</body>
</html>
