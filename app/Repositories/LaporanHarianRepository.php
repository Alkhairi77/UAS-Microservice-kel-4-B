<?php

namespace App\Repositories;

use App\Models\LaporanHarian;
use Illuminate\Database\Eloquent\Builder;

class LaporanHarianRepository
{
    protected $model;

    public function __construct(LaporanHarian $model)
    {
        $this->model = $model;
    }

    /**
     * Get filtered query
     */
    public function getFilteredQuery(array $filters = []): Builder
    {
        $query = $this->model->with(['jenisLimbah', 'penyimpanan', 'perusahaan']);

        if (isset($filters['perusahaan_id'])) {
            $query->where('perusahaan_id', $filters['perusahaan_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['jenis_limbah_id'])) {
            $query->where('jenis_limbah_id', $filters['jenis_limbah_id']);
        }

        if (isset($filters['date_from'])) {
            $query->where('tanggal', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('tanggal', '<=', $filters['date_to']);
        }

        return $query;
    }

    /**
     * Get statistics
     */
    public function getStatistics($perusahaanId, $startDate = null, $endDate = null)
    {
        $query = $this->model->where('perusahaan_id', $perusahaanId);

        if ($startDate) {
            $query->where('tanggal', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('tanggal', '<=', $endDate);
        }

        return [
            'total_laporan' => $query->count(),
            'laporan_draft' => (clone $query)->where('status', 'draft')->count(),
            'laporan_submitted' => (clone $query)->where('status', 'submitted')->count(),
            'total_limbah' => (clone $query)->where('status', 'submitted')->sum('jumlah'),
        ];
    }
}