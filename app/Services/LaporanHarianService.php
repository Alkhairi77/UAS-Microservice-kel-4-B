<?php

namespace App\Services;

use App\Models\LaporanHarian;
use App\Models\Penyimpanan;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;

class LaporanHarianService
{
    /**
     * Create new laporan harian
     */
    public function create(array $data, $perusahaanId)
    {
        return DB::transaction(function () use ($data, $perusahaanId) {
            // Validate penyimpanan
            $penyimpanan = $this->validatePenyimpanan($data['penyimpanan_id'], $data['jenis_limbah_id'], $perusahaanId);
            
            // Check capacity
            $this->checkCapacity($penyimpanan, $data['jumlah']);
            
            // Create laporan
            $laporan = LaporanHarian::create([
                'perusahaan_id' => $perusahaanId,
                'tanggal' => $data['tanggal'],
                'jenis_limbah_id' => $data['jenis_limbah_id'],
                'penyimpanan_id' => $data['penyimpanan_id'],
                'jumlah' => $data['jumlah'],
                'satuan' => $data['satuan'],
                'keterangan' => $data['keterangan'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'tanggal_laporan' => now(),
            ]);

            // Update capacity if submitted
            if (($data['status'] ?? 'draft') === 'submitted') {
                $this->updatePenyimpananCapacity($penyimpanan, $data['jumlah']);
            }

            return $laporan;
        });
    }

    /**
     * Update laporan harian
     */
    public function update(LaporanHarian $laporan, array $data)
    {
        return DB::transaction(function () use ($laporan, $data) {
            // Validate penyimpanan
            $penyimpanan = $this->validatePenyimpanan($data['penyimpanan_id'], $data['jenis_limbah_id'], $laporan->perusahaan_id);
            
            // Check capacity
            $this->checkCapacity($penyimpanan, $data['jumlah']);
            
            // Update laporan
            $laporan->update([
                'tanggal' => $data['tanggal'],
                'jenis_limbah_id' => $data['jenis_limbah_id'],
                'penyimpanan_id' => $data['penyimpanan_id'],
                'jumlah' => $data['jumlah'],
                'satuan' => $data['satuan'],
                'keterangan' => $data['keterangan'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'tanggal_laporan' => now(),
            ]);

            // Update capacity if submitted
            if (($data['status'] ?? 'draft') === 'submitted') {
                $this->updatePenyimpananCapacity($penyimpanan, $data['jumlah']);
            }

            return $laporan;
        });
    }

    /**
     * Submit laporan
     */
    public function submit(LaporanHarian $laporan)
    {
        return DB::transaction(function () use ($laporan) {
            if (!$laporan->canSubmit()) {
                throw new \Exception('Laporan tidak dapat disubmit.');
            }

            // Check capacity
            if (!$laporan->penyimpanan->canAccommodate($laporan->jumlah)) {
                throw new \Exception('Kapasitas penyimpanan tidak mencukupi.');
            }

            $laporan->submit();
            NotificationHelper::laporanHarianSubmitted($laporan);

            return $laporan;
        });
    }

    /**
     * Validate penyimpanan
     */
    private function validatePenyimpanan($penyimpananId, $jenisLimbahId, $perusahaanId)
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
     * Check capacity
     */
    private function checkCapacity($penyimpanan, $jumlah)
    {
        $sisaKapasitas = $penyimpanan->kapasitas_maksimal - $penyimpanan->kapasitas_terpakai;
        
        if ($jumlah > $sisaKapasitas) {
            throw new \Exception(
                'Jumlah melebihi sisa kapasitas penyimpanan. Sisa kapasitas: ' .
                number_format($sisaKapasitas, 2) . ' ' . $penyimpanan->satuan
            );
        }
    }

    /**
     * Update penyimpanan capacity
     */
    private function updatePenyimpananCapacity($penyimpanan, $jumlah)
    {
        $penyimpanan->increment('kapasitas_terpakai', $jumlah);
        
        // Check if storage is getting full
        $percentage = ($penyimpanan->kapasitas_terpakai / $penyimpanan->kapasitas_maksimal) * 100;
        if ($percentage >= 90) {
            NotificationHelper::penyimpananFull($penyimpanan);
        }
    }
}