<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Harian Limbah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
            color: #333;
            font-size: 24px;
        }
        
        .header h2 {
            margin: 5px 0;
            color: #666;
            font-size: 16px;
            font-weight: normal;
        }
        
        .company-info {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        
        .company-info h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .info-item {
            margin-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .status {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
        }
        
        .status-draft {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-submitted {
            background-color: #d4edda;
            color: #155724;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .summary {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            text-align: center;
        }
        
        .summary-item {
            background-color: white;
            padding: 10px;
            border-radius: 3px;
        }
        
        .summary-number {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        .summary-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        @page {
            margin: 20mm;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN HARIAN LIMBAH</h1>
        <h2>{{ $user->perusahaan->nama_perusahaan ?? 'Semua Perusahaan' }}</h2>
        <p>Periode: {{ $period ?? 'Semua Data' }}</p>
    </div>

    <!-- Company Info (jika user perusahaan) -->
    @if($user->isPerusahaan() && $user->perusahaan)
    <div class="company-info">
        <h3>Informasi Perusahaan</h3>
        <div class="info-grid">
            <div>
                <div class="info-item">
                    <span class="info-label">Nama Perusahaan:</span> {{ $user->perusahaan->nama_perusahaan }}
                </div>
                <div class="info-item">
                    <span class="info-label">No. Registrasi:</span> {{ $user->perusahaan->no_registrasi }}
                </div>
                <div class="info-item">
                    <span class="info-label">Jenis Usaha:</span> {{ $user->perusahaan->jenis_usaha }}
                </div>
            </div>
            <div>
                <div class="info-item">
                    <span class="info-label">Alamat:</span> {{ $user->perusahaan->alamat }}
                </div>
                <div class="info-item">
                    <span class="info-label">Telepon:</span> {{ $user->perusahaan->no_telp }}
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span> {{ $user->perusahaan->email }}
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Summary -->
    <div class="summary">
        <h3 style="margin: 0 0 15px 0;">Ringkasan Data</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-number">{{ $data->count() }}</div>
                <div class="summary-label">Total Laporan</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $data->where('status', 'draft')->count() }}</div>
                <div class="summary-label">Draft</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $data->where('status', 'submitted')->count() }}</div>
                <div class="summary-label">Submitted</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ number_format($data->where('status', 'submitted')->sum('jumlah'), 2) }}</div>
                <div class="summary-label">Total Limbah (Submitted)</div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 10%;">Tanggal</th>
                @if($user->isAdmin())
                <th style="width: 15%;">Perusahaan</th>
                @endif
                <th style="width: 15%;">Jenis Limbah</th>
                <th style="width: 12%;">Penyimpanan</th>
                <th style="width: 10%;">Jumlah</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 22%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                @if($user->isAdmin())
                <td>{{ $item->perusahaan->nama_perusahaan }}</td>
                @endif
                <td>
                    <div class="font-bold">{{ $item->jenisLimbah->nama }}</div>
                    <div style="font-size: 10px; color: #666;">{{ $item->jenisLimbah->kode_limbah }}</div>
                </td>
                <td>
                    <div class="font-bold">{{ $item->penyimpanan->nama_penyimpanan }}</div>
                                        <div class="font-bold">{{ $item->penyimpanan->nama_penyimpanan }}</div>
                    <div style="font-size: 10px; color: #666;">{{ $item->penyimpanan->lokasi }}</div>
                </td>
                <td class="text-right">
                    <div class="font-bold">{{ number_format($item->jumlah, 2) }}</div>
                    <div style="font-size: 10px; color: #666;">{{ $item->satuan }}</div>
                </td>
                <td class="text-center">
                    <span class="status {{ $item->status === 'draft' ? 'status-draft' : 'status-submitted' }}">
                        {{ $item->status === 'draft' ? 'Draft' : 'Submitted' }}
                    </span>
                </td>
                <td style="font-size: 10px;">{{ $item->keterangan ?: '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ $user->isAdmin() ? '8' : '7' }}" class="text-center" style="padding: 20px; color: #666;">
                    Tidak ada data laporan harian
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis pada {{ $generated_at->format('d F Y H:i:s') }}</p>
        <p>© {{ date('Y') }} {{ config('app.name') }} - Sistem Manajemen Limbah</p>
    </div>
</body>
</html>
