<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanHasilPengelolaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'perusahaan_id',
        'pengelolaan_limbah_id',
        'tanggal_selesai',
        'status_hasil',
        'jumlah_berhasil_dikelola',
        'jumlah_residu',
        'satuan',
        'efisiensi_pengelolaan',
        'metode_disposal_akhir',
        'biaya_aktual',
        'nomor_sertifikat',
        'catatan_hasil',
        'dokumentasi',
    ];

    protected $casts = [
        'tanggal_selesai' => 'date',
        'jumlah_berhasil_dikelola' => 'decimal:2',
        'jumlah_residu' => 'decimal:2',
        'efisiensi_pengelolaan' => 'decimal:2',
        'biaya_aktual' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Auto-calculate efisiensi_pengelolaan
            $totalJumlah = $model->jumlah_berhasil_dikelola + ($model->jumlah_residu ?? 0);
            if ($totalJumlah > 0) {
                $model->efisiensi_pengelolaan = ($model->jumlah_berhasil_dikelola / $totalJumlah) * 100;
            } else {
                $model->efisiensi_pengelolaan = 0;
            }
        });
    }

    // Relationships
    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function pengelolaanLimbah(): BelongsTo
    {
        return $this->belongsTo(PengelolaanLimbah::class);
    }

    // Accessors
    public function getStatusHasilNameAttribute(): string
    {
        return match($this->status_hasil) {
            'berhasil' => 'Berhasil',
            'partial' => 'Sebagian Berhasil',
            'gagal' => 'Gagal',
            default => ucfirst($this->status_hasil)
        };
    }

    public function getStatusHasilBadgeClassAttribute(): string
    {
        return match($this->status_hasil) {
            'berhasil' => 'green',
            'partial' => 'yellow',
            'gagal' => 'red',
            default => 'gray'
        };
    }

    public function getDokumentasiArrayAttribute(): array
    {
        if (!$this->dokumentasi) {
            return [];
        }
        
        $decoded = json_decode($this->dokumentasi, true);
        return is_array($decoded) ? $decoded : [];
    }

    // Methods
    public function canEdit(): bool
    {
        // Bisa diedit jika belum lewat 30 hari dari tanggal selesai
        return $this->tanggal_selesai->diffInDays(now()) <= 30;
    }

    public function canDelete(): bool
    {
        // Bisa dihapus jika belum lewat 7 hari dari tanggal selesai
        return $this->tanggal_selesai->diffInDays(now()) <= 7;
    }

    // Static methods
    public static function getStatusHasilOptions(): array
    {
        return [
            'berhasil' => 'Berhasil',
            'partial' => 'Sebagian Berhasil',
            'gagal' => 'Gagal',
        ];
    }

    public static function getMetodeDisposalOptions(): array
    {
        return [
            'landfill' => 'Landfill',
            'incineration' => 'Insinerasi',
            'recycling' => 'Daur Ulang',
            'composting' => 'Kompos',
            'treatment' => 'Pengolahan',
            'reuse' => 'Penggunaan Kembali',
            'stabilization' => 'Stabilisasi',
            'neutralization' => 'Netralisasi',
            'other' => 'Lainnya',
        ];
    }

    // Scopes
    public function scopeByPerusahaan($query, $perusahaanId)
    {
        return $query->where('perusahaan_id', $perusahaanId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status_hasil', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_selesai', [$startDate, $endDate]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tanggal_selesai', now()->month)
                    ->whereYear('tanggal_selesai', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('tanggal_selesai', now()->year);
    }
}
