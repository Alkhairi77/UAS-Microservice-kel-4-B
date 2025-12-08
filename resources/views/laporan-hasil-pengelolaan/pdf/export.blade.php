<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Hasil Pengelolaan Limbah</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .info-section {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .info-box {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .summary-stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .stat-box {
            text-align: center;
            padding: 10px;
            background: #e9ecef;
            border-radius: 5px;
            min-width: 80px;
        }

        .stat-number {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .stat-label {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 9px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 9px;
        }

        .status-berhasil {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
        }

        .status-partial {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
        }

        .status-gagal {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .page-break {
            page-break-after: always;
        }

        .efficiency-bar {
            width: 50px;
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            display: inline-block;
        }

        .efficiency-fill {
            height: 100%;
            background-color: #28a745;
        }

        .efficiency-fill.medium {
            background-color: #ffc107;
        }

        .efficiency-fill.low {
            background-color: #dc3545;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .mt-20 {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN HASIL PENGELOLAAN LIMBAH</h1>
        @if($user->isPerusahaan())
            <h2>{{ $user->perusahaan->nama_perusahaan }}</h2>
        @else
            <h2>SISTEM PENGELOLAAN LIMBAH</h2>
        @endif
        <p>Periode:
            @if($filters['tanggal_dari'] && $filters['tanggal_sampai'])
                {{ \Carbon\Carbon::parse($filters['tanggal_dari'])->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($filters['tanggal_sampai'])->format('d/m/Y') }}
            @else
                Semua Data
            @endif
        </p>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-stats">
        <div class="stat-box">
            <div class="stat-number">{{ $total_laporan }}</div>
            <div class="stat-label">Total Laporan</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $total_berhasil }}</div>
            <div class="stat-label">Berhasil</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $total_partial }}</div>
            <div class="stat-label">Sebagian Berhasil</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $total_gagal }}</div>
            <div class="stat-label">Gagal</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ number_format($total_limbah_dikelola, 2) }}</div>
            <div class="stat-label">Total Dikelola (kg/L)</div>
        </div>
    </div>

    <!-- Filter Information -->
    @if($filters['status_hasil'] || $filters['perusahaan_id'])
        <div class="info-box">
            <strong>Filter Diterapkan:</strong>
            @if($filters['status_hasil'])
                Status: {{ ucfirst(str_replace('_', ' ', $filters['status_hasil'])) }}
            @endif
            @if($filters['perusahaan_id'] && $user->isAdmin())
                | Perusahaan ID: {{ $filters['perusahaan_id'] }}
            @endif
        </div>
    @endif

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th width="8%">No</th>
                <th width="10%">Tanggal</th>
                @if($user->isAdmin())
                    <th width="15%">Perusahaan</th>
                @endif
                <th width="15%">Jenis Limbah</th>
                <th width="12%">Jumlah Dikelola</th>
                <th width="10%">Residu</th>
                <th width="10%">Efisiensi</th>
                <th width="12%">Status</th>
                <th width="15%">Vendor</th>
                @if(!$user->isAdmin())
                    <th width="13%">Keterangan</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($laporanHasil as $index => $item)
                @php
                    $totalJumlah = $item->jumlah_berhasil_dikelola + ($item->jumlah_residu ?? 0);
                    $efisiensi = $totalJumlah > 0 ? ($item->jumlah_berhasil_dikelola / $totalJumlah) * 100 : 0;
                    $efisiensiClass = $efisiensi >= 80 ? '' : ($efisiensi >= 60 ? 'medium' : 'low');
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal_selesai->format('d/m/Y') }}</td>
                    @if($user->isAdmin())
                        <td>{{ $item->perusahaan->nama_perusahaan }}</td>
                    @endif
                    <td>
                        <strong>{{ $item->pengelolaanLimbah->jenisLimbah->nama }}</strong><br>
                        <small>{{ $item->pengelolaanLimbah->jenisLimbah->kode_limbah }}</small>
                    </td>
                    <td class="text-right">
                        {{ number_format($item->jumlah_berhasil_dikelola, 2) }} {{ $item->satuan }}
                    </td>
                    <td class="text-right">
                        {{ number_format($item->jumlah_residu ?? 0, 2) }} {{ $item->satuan }}
                    </td>
                    <td class="text-center">
                        <div class="efficiency-bar">
                            <div class="efficiency-fill {{ $efisiensiClass }}" style="width: {{ min($efisiensi, 100) }}%">
                            </div>
                        </div>
                        <br>{{ number_format($efisiensi, 1) }}%
                    </td>
                    <td class="text-center">
                        @php
                            $statusClass = match ($item->status_hasil) {
                                'berhasil' => 'status-berhasil',
                                'partial' => 'status-sebagian',
                                'gagal' => 'status-gagal',
                                default => ''
                            };
                            $statusName = match ($item->status_hasil) {
                                'berhasil' => 'Berhasil',
                                'partial' => 'Berhasil sebagian',
                                'gagal' => 'Gagal',
                                default => ucfirst($item->status_hasil)
                            };
                        @endphp
                        <span class="{{ $statusClass }}">{{ $statusName }}</span>
                    </td>
                    <td>{{ $item->pengelolaanLimbah->vendor->nama_perusahaan ?? '-' }}</td>
                    @if(!$user->isAdmin())
                        <td>{{ Str::limit($item->keterangan ?? '-', 50) }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $user->isAdmin() ? '9' : '9' }}" class="text-center">
                        Tidak ada data laporan hasil pengelolaan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>
            Laporan ini dibuat secara otomatis oleh Sistem Pengelolaan Limbah<br>
            Digenerate pada: {{ $generated_at->format('d/m/Y H:i:s') }} WIB<br>
            @if($user->isPerusahaan())
                Perusahaan: {{ $user->perusahaan->nama_perusahaan }}
            @endif
        </p>
    </div>
</body>

</html>