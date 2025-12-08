<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ringkasan Laporan Hasil Pengelolaan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 20px;
            line-height: 1.5;
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
        
        .period {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .stats-row {
            display: table-row;
        }
        
        .stat-box {
            display: table-cell;
            width: 20%;
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            vertical-align: middle;
        }
        
        .stat-number {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            display: block;
        }
        
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .chart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .chart-table th,
        .chart-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .chart-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .progress-bar {
            width: 100px;
            height: 15px;
            background-color: #e9ecef;
            border-radius: 7px;
            overflow: hidden;
            display: inline-block;
        }
        
        .progress-fill {
            height: 100%;
            background-color: #28a745;
            border-radius: 7px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>RINGKASAN LAPORAN HASIL PENGELOLAAN LIMBAH</h1>
        @if($user->isPerusahaan())
            <h2>{{ $user->perusahaan->nama_perusahaan }}</h2>
        @endif
        <div class="period">
            Periode: {{ $start_date->format('d/m/Y') }} - {{ $end_date->format('d/m/Y') }}
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="stats-grid">
        <div class="stats-row">
            <div class="stat-box">
                <span class="stat-number">{{ $statistics['total_laporan'] }}</span>
                <div class="stat-label">Total Laporan</div>
            </div>
            <div class="stat-box">
                <span class="stat-number">{{ $statistics['berhasil'] }}</span>
                <div class="stat-label">Berhasil</div>
            </div>
            <div class="stat-box">
                <span class="stat-number">{{ $statistics['sebagian_berhasil'] }}</span>
                <div class="stat-label">Sebagian Berhasil</div>
            </div>
            <div class="stat-box">
                <span class="stat-number">{{ $statistics['gagal'] }}</span>
                <div class="stat-label">Gagal</div>
            </div>
            <div class="stat-box">
                <span class="stat-number">{{ number_format($statistics['rata_rata_efisiensi'], 1) }}%</span>
                <div class="stat-label">Rata-rata Efisiensi</div>
            </div>
        </div>
    </div>

    <!-- Analisis Berdasarkan Jenis Limbah -->
    <div class="section">
        <div class="section-title">Analisis Berdasarkan Jenis Limbah</div>
        <table class="chart-table">
            <thead>
                <tr>
                    <th>Jenis Limbah</th>
                    <th class="text-center">Jumlah Laporan</th>
                    <th class="text-right">Total Dikelola (kg/L)</th>
                    <th class="text-right">Total Residu (kg/L)</th>
                    <th class="text-center">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @php $totalLaporan = $statistics['total_laporan']; @endphp
                @forelse($by_jenis_limbah as $jenis => $data)
                    @php $percentage = $totalLaporan > 0 ? ($data['count'] / $totalLaporan) * 100 : 0; @endphp
                    <tr>
                        <td>{{ $jenis }}</td>
                        <td class="text-center">{{ $data['count'] }}</td>
                        <td class="text-right">{{ number_format($data['total_dikelola'], 2) }}</td>
                        <td class="text-right">{{ number_format($data['total_residu'], 2) }}</td>
                        <td class="text-center">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                            </div>
                            {{ number_format($percentage, 1) }}%
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Analisis Berdasarkan Vendor -->
    @if($by_vendor->count() > 0)
    <div class="section">
        <div class="section-title">Analisis Berdasarkan Vendor</div>
        <table class="chart-table">
            <thead>
                <tr>
                    <th>Vendor</th>
                    <th class="text-center">Jumlah Laporan</th>
                    <th class="text-right">Total Dikelola (kg/L)</th>
                    <th class="text-center">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($by_vendor as $vendor => $data)
                    @php $percentage = $totalLaporan > 0 ? ($data['count'] / $totalLaporan) * 100 : 0; @endphp
                    <tr>
                        <td>{{ $vendor ?: 'Internal' }}</td>
                        <td class="text-center">{{ $data['count'] }}</td>
                        <td class="text-right">{{ number_format($data['total_dikelola'], 2) }}</td>
                        <td class="text-center">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                            </div>
                            {{ number_format($percentage, 1) }}%
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Tren Bulanan -->
    @if($monthly_trend->count() > 0)
    <div class="section">
        <div class="section-title">Tren Bulanan</div>
        <table class="chart-table">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="text-center">Jumlah Laporan</th>
                    <th class="text-right">Total Dikelola (kg/L)</th>
                    <th class="text-center">Tren</th>
                </tr>
            </thead>
            <tbody>
                @php $maxCount = $monthly_trend->max('count'); @endphp
                @foreach($monthly_trend as $month => $data)
                    @php 
                        $percentage = $maxCount > 0 ? ($data['count'] / $maxCount) * 100 : 0;
                        $monthName = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y');
                    @endphp
                    <tr>
                        <td>{{ $monthName }}</td>
                        <td class="text-center">{{ $data['count'] }}</td>
                        <td class="text-right">{{ number_format($data['total_dikelola'], 2) }}</td>
                        <td class="text-center">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Kesimpulan dan Rekomendasi -->
    <div class="section">
        <div class="section-title">Kesimpulan dan Rekomendasi</div>
        
        <h4>Kesimpulan:</h4>
        <ul>
            <li>
                Dalam periode {{ $start_date->format('d/m/Y') }} - {{ $end_date->format('d/m/Y') }}, 
                telah dilakukan {{ $statistics['total_laporan'] }} pengelolaan limbah.
            </li>
            <li>
                Tingkat keberhasilan pengelolaan mencapai 
                {{ $statistics['total_laporan'] > 0 ? number_format(($statistics['berhasil'] / $statistics['total_laporan']) * 100, 1) : 0 }}%
                ({{ $statistics['berhasil'] }} dari {{ $statistics['total_laporan'] }} laporan).
            </li>
            <li>
                Total limbah yang berhasil dikelola: {{ number_format($statistics['total_limbah_dikelola'], 2) }} kg/L
            </li>
            <li>
                Total residu yang dihasilkan: {{ number_format($statistics['total_residu'], 2) }} kg/L
            </li>
            <li>
                Rata-rata efisiensi pengelolaan: {{ number_format($statistics['rata_rata_efisiensi'], 1) }}%
            </li>
        </ul>

        <h4>Rekomendasi:</h4>
        <ul>
            @if($statistics['rata_rata_efisiensi'] < 70)
                <li><strong>Perbaikan Efisiensi:</strong> Rata-rata efisiensi masih di bawah 70%. Perlu evaluasi metode pengelolaan dan pelatihan tim.</li>
            @endif
            
            @if($statistics['gagal'] > 0)
                <li><strong>Analisis Kegagalan:</strong> Terdapat {{ $statistics['gagal'] }} laporan dengan status gagal. Perlu investigasi penyebab dan perbaikan proses.</li>
            @endif
            
            @if($statistics['total_residu'] > ($statistics['total_limbah_dikelola'] * 0.2))
                <li><strong>Pengelolaan Residu:</strong> Jumlah residu cukup tinggi (>20%). Pertimbangkan metode pengelolaan yang lebih efisien.</li>
            @endif
            
            <li><strong>Monitoring Berkelanjutan:</strong> Lakukan monitoring rutin untuk memastikan konsistensi kualitas pengelolaan limbah.</li>
            
            @if($by_vendor->count() > 1)
                <li><strong>Evaluasi Vendor:</strong> Bandingkan performa antar vendor untuk optimalisasi kerjasama.</li>
            @endif
        </ul>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            <strong>Ringkasan Laporan Hasil Pengelolaan Limbah</strong><br>
            Periode: {{ $start_date->format('d/m/Y') }} - {{ $end_date->format('d/m/Y') }}<br>
            Digenerate pada: {{ $generated_at->format('d/m/Y H:i:s') }} WIB<br>
            @if($user->isPerusahaan())
                Perusahaan: {{ $user->perusahaan->nama_perusahaan }}<br>
            @endif
            Sistem Pengelolaan Limbah
        </p>
    </div>
</body>
</html>
