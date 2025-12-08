<?php

namespace App\Services;

use App\Models\PengelolaanLimbah;
use App\Models\Penyimpanan;
use App\Models\JenisLimbah;
use App\Models\Vendor;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengelolaanLimbahService
{
    /**
     * Create new pengelolaan limbah
     */
    public function create(array $data, int $perusahaanId): PengelolaanLimbah
    {
        return DB::transaction(function () use ($data, $perusahaanId) {
            Log::info('Creating pengelolaan limbah', ['data' => $data, 'perusahaan_id' => $perusahaanId]);

            // Validasi dan get data
            $jenisLimbah = JenisLimbah::findOrFail($data['jenis_limbah_id']);
            $penyimpanan = $this->validatePenyimpanan($data['penyimpanan_id'], $perusahaanId, $data['jenis_limbah_id']);

            // Validasi stok
            $this->validateStok($penyimpanan, $data['jumlah_dikelola']);

            // Validasi vendor jika diperlukan
            $vendorId = null;
            if ($data['jenis_pengelolaan'] === 'vendor_eksternal') {
                if (empty($data['vendor_id'])) {
                    throw new \Exception('Vendor wajib dipilih untuk pengelolaan eksternal.');
                }

                $vendor = Vendor::where('id', $data['vendor_id'])
                    ->where('status', 'aktif')
                    ->first();

                if (!$vendor) {
                    throw new \Exception('Vendor tidak valid atau tidak aktif.');
                }

                $vendorId = $vendor->id;
            }

            // Generate nomor manifest
            $nomorManifest = $this->generateNomorManifest();

            // Prepare data
            $pengelolaanData = [
                'perusahaan_id' => $perusahaanId,
                'tanggal_mulai' => $data['tanggal_mulai'],
                'jenis_limbah_id' => $data['jenis_limbah_id'],
                'penyimpanan_id' => $data['penyimpanan_id'],
                'jumlah_dikelola' => $data['jumlah_dikelola'],
                'satuan' => $jenisLimbah->satuan_default,
                'jenis_pengelolaan' => $data['jenis_pengelolaan'],
                'metode_pengelolaan' => $data['metode_pengelolaan'],
                'vendor_id' => $vendorId,
                'biaya' => $data['biaya'] ?? null,
                'status' => $data['status'] ?? 'diproses',
                'catatan' => $data['catatan'] ?? null,
                'nomor_manifest' => $nomorManifest,
            ];

            Log::info('Pengelolaan data to create', $pengelolaanData);

            // Create pengelolaan
            $pengelolaan = PengelolaanLimbah::create($pengelolaanData);

            // Update stok penyimpanan
            $this->updateStokPenyimpanan($penyimpanan, -$data['jumlah_dikelola']);

            // Send notification
            try {
                NotificationHelper::pengelolaanCreated($pengelolaan);
            } catch (\Exception $e) {
                Log::warning('Failed to send notification', ['error' => $e->getMessage()]);
            }

            Log::info('Pengelolaan limbah created successfully', ['id' => $pengelolaan->id]);

            return $pengelolaan;
        });
    }

    /**
     * Update pengelolaan limbah
     */
    public function update(PengelolaanLimbah $pengelolaan, array $data): PengelolaanLimbah
    {
        return DB::transaction(function () use ($pengelolaan, $data) {
            Log::info('Updating pengelolaan limbah', ['id' => $pengelolaan->id, 'data' => $data]);

            // Validasi penyimpanan
            $penyimpanan = $this->validatePenyimpanan(
                $data['penyimpanan_id'],
                $pengelolaan->perusahaan_id,
                $pengelolaan->jenis_limbah_id
            );

            // Hitung perubahan stok
            $jumlahLama = $pengelolaan->jumlah_dikelola;
            $jumlahBaru = $data['jumlah_dikelola'];
            $perubahanStok = $jumlahBaru - $jumlahLama;

            // Validasi stok jika ada penambahan
            if ($perubahanStok > 0) {
                $this->validateStok($penyimpanan, $perubahanStok);
            }

            // Validasi vendor jika diperlukan
            $vendorId = null;
            if ($data['jenis_pengelolaan'] === 'vendor_eksternal') {
                if (empty($data['vendor_id'])) {
                    throw new \Exception('Vendor wajib dipilih untuk pengelolaan eksternal.');
                }

                $vendor = Vendor::where('id', $data['vendor_id'])
                    ->where('status', 'aktif')
                    ->first();

                if (!$vendor) {
                    throw new \Exception('Vendor tidak valid atau tidak aktif.');
                }

                $vendorId = $vendor->id;
            }

            // Update stok penyimpanan
            $this->updateStokPenyimpanan($penyimpanan, $perubahanStok);

            // Update pengelolaan
            $pengelolaan->update([
                'tanggal_mulai' => $data['tanggal_mulai'],
                'penyimpanan_id' => $data['penyimpanan_id'],
                'jumlah_dikelola' => $data['jumlah_dikelola'],
                'jenis_pengelolaan' => $data['jenis_pengelolaan'],
                'metode_pengelolaan' => $data['metode_pengelolaan'],
                'status' => $data['status'],
                'biaya' => $data['biaya'] ?? null,
                'vendor_id' => $vendorId,
                'catatan' => $data['catatan'] ?? null,
            ]);

            // Send notification if completed
            if ($data['status'] === 'selesai') {
                try {
                    NotificationHelper::notifyUser(
                        $pengelolaan->perusahaan->user,
                        'Pengelolaan Limbah Selesai',
                        "Pengelolaan {$pengelolaan->jenisLimbah->nama} telah selesai. Silakan buat laporan hasil.",
                        'info',
                        route('laporan-hasil-pengelolaan.create')
                    );
                } catch (\Exception $e) {
                    Log::warning('Failed to send notification', ['error' => $e->getMessage()]);
                }
            }

            Log::info('Pengelolaan limbah updated successfully', ['id' => $pengelolaan->id]);

            return $pengelolaan;
        });
    }

    /**
     * Update status pengelolaan
     */
    public function updateStatus(PengelolaanLimbah $pengelolaan, string $status): PengelolaanLimbah
    {
        $pengelolaan->update(['status' => $status]);

        if ($status === 'selesai') {
            try {
                NotificationHelper::notifyUser(
                    $pengelolaan->perusahaan->user,
                    'Pengelolaan Limbah Selesai',
                    "Pengelolaan {$pengelolaan->jenisLimbah->nama} telah selesai.",
                    'success',
                    route('pengelolaan-limbah.show', $pengelolaan)
                );
            } catch (\Exception $e) {
                Log::warning('Failed to send notification', ['error' => $e->getMessage()]);
            }
        }

        return $pengelolaan;
    }

    /**
     * Validate penyimpanan
     */
    private function validatePenyimpanan(int $penyimpananId, int $perusahaanId, int $jenisLimbahId): Penyimpanan
    {
        $penyimpanan = Penyimpanan::where('id', $penyimpananId)
            ->where('perusahaan_id', $perusahaanId)
            ->where('jenis_limbah_id', $jenisLimbahId)
            ->where('status', 'aktif')
            ->first();

        if (!$penyimpanan) {
            throw new \Exception('Penyimpanan tidak valid atau tidak sesuai dengan jenis limbah.');
        }

        return $penyimpanan;
    }

    /**
     * Validate stok availability
     */
    private function validateStok(Penyimpanan $penyimpanan, float $jumlah): void
    {
        if ($jumlah > $penyimpanan->kapasitas_terpakai) {
            throw new \Exception(
                'Jumlah melebihi stok tersedia: ' .
                    number_format($penyimpanan->kapasitas_terpakai, 2) . ' ' . $penyimpanan->satuan
            );
        }
    }

    /**
     * Update stok penyimpanan
     */
    private function updateStokPenyimpanan(Penyimpanan $penyimpanan, float $perubahan): void
    {
        $penyimpanan->kapasitas_terpakai += $perubahan;
        $penyimpanan->save();

        Log::info('Updated penyimpanan stock', [
            'penyimpanan_id' => $penyimpanan->id,
            'perubahan' => $perubahan,
            'kapasitas_terpakai_baru' => $penyimpanan->kapasitas_terpakai
        ]);
    }

    /**
     * Generate nomor manifest
     */
    private function generateNomorManifest(): string
    {
        $prefix = 'MNF';
        $date = date('Ymd');
        $lastNumber = PengelolaanLimbah::whereDate('created_at', today())->count() + 1;

        return $prefix . $date . str_pad($lastNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get statistics
     */
    public function getStatistics(int $perusahaanId): array
    {
        $totalPengelolaan = PengelolaanLimbah::where('perusahaan_id', $perusahaanId)->count();

        $pengelolaanBulanIni = PengelolaanLimbah::where('perusahaan_id', $perusahaanId)
            ->whereMonth('tanggal_mulai', now()->month)
            ->whereYear('tanggal_mulai', now()->year)
            ->count();

        $totalLimbahDikelola = PengelolaanLimbah::where('perusahaan_id', $perusahaanId)
            ->where('status', 'selesai')
            ->sum('jumlah_dikelola');

        $statusStats = PengelolaanLimbah::where('perusahaan_id', $perusahaanId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $jenisStats = PengelolaanLimbah::where('perusahaan_id', $perusahaanId)
            ->selectRaw('jenis_pengelolaan, COUNT(*) as total')
            ->groupBy('jenis_pengelolaan')
            ->pluck('total', 'jenis_pengelolaan');

        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = PengelolaanLimbah::where('perusahaan_id', $perusahaanId)
                ->whereMonth('tanggal_mulai', $date->month)
                ->whereYear('tanggal_mulai', $date->year)
                ->count();

            $trendData[] = [
                'bulan' => $date->format('M Y'),
                'total' => $count
            ];
        }

        return [
            'total_pengelolaan' => $totalPengelolaan,
            'pengelolaan_bulan_ini' => $pengelolaanBulanIni,
            'total_limbah_dikelola' => number_format($totalLimbahDikelola, 2),
            'status_stats' => $statusStats,
            'jenis_stats' => $jenisStats,
            'trend_data' => $trendData
        ];
    }
}
