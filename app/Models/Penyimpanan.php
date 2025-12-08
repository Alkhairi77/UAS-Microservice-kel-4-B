<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penyimpanan extends Model
{
    use HasFactory;

    protected $table = 'penyimpanans';

    protected $fillable = [
        'perusahaan_id',
        'jenis_limbah_id',
        'nama_penyimpanan',
        'lokasi',
        'jenis_penyimpanan',
        'kapasitas_maksimal',
        'kapasitas_terpakai',
        'kondisi',
        'tanggal_pembuatan',
        'catatan',
        'status'
    ];

    protected $casts = [
        'tanggal_pembuatan' => 'date',
        'kapasitas_maksimal' => 'decimal:2',
        'kapasitas_terpakai' => 'decimal:2'
    ];

    /**
     * Relationship dengan Perusahaan
     */
    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }
    /**
     * Relationship dengan Jenis Limbah
     */
    public function jenisLimbah(): BelongsTo
    {
        return $this->belongsTo(JenisLimbah::class);
    }

    /**
     * Relasi dengan laporan harian
     */
    public function laporanHarian(): HasMany
    {
        return $this->hasMany(LaporanHarian::class);
    }

    // Tambahkan method ini
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }


    /**
     * Hitung sisa kapasitas
     */
    public function getSisaKapasitasAttribute()
    {
        return $this->kapasitas_maksimal - $this->kapasitas_terpakai;
    }

    /**
     * Hitung persentase kapasitas terpakai
     */
    public function getPersentaseKapasitas()
    {
        if ($this->kapasitas_maksimal <= 0) {
            return 0;
        }
        return ($this->kapasitas_terpakai / $this->kapasitas_maksimal) * 100;
    }

    /**
     * Text untuk status kapasitas
     */
    public function getStatusKapasitasTextAttribute()
    {
        $persentase = $this->persentase_kapasitas;

        if ($persentase >= 90) {
            return 'Hampir Penuh';
        } elseif ($persentase >= 70) {
            return 'Kapasitas Tinggi';
        } elseif ($persentase >= 50) {
            return 'Setengah Penuh';
        } else {
            return 'Kapasitas Tersedia';
        }
    }

    /**
     * Warna badge untuk status kapasitas
     */
    public function getStatusKapasitasColorAttribute()
    {
        $persentase = $this->persentase_kapasitas;

        if ($persentase >= 90) {
            return 'red';
        } elseif ($persentase >= 70) {
            return 'orange';
        } elseif ($persentase >= 50) {
            return 'yellow';
        } else {
            return 'green';
        }
    }
    /**
     * Hitung total limbah yang masuk hari ini
     */
    public function getLimbahHariIniAttribute(): float
    {
        return $this->laporanHarian()
            ->whereDate('tanggal_laporan', today())
            ->sum('jumlah') ?? 0;
    }

    /**
     * Hitung total limbah minggu ini
     */
    public function getLimbahMingguIniAttribute(): float
    {
        return $this->laporanHarian()
            ->whereBetween('tanggal_laporan', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->sum('jumlah') ?? 0;
    }

    /**
     * Cek apakah penyimpanan hampir penuh (>= 75%)
     */
    public function isNearFull(): bool
    {
        return $this->persentase_kapasitas >= 75;
    }

    /**
     * Cek apakah penyimpanan penuh (>= 90%)
     */
    public function isFull(): bool
    {
        return $this->persentase_kapasitas >= 90;
    }

    // UPDATE: Ambil satuan dari jenis limbah
    public function getSatuanAttribute()
    {
        return $this->jenisLimbah?->satuan_default ?? 'kg';
    }

    // TAMBAH: Cek apakah bisa menampung limbah
    public function canAccommodate($jumlah)
    {
        return ($this->kapasitas_terpakai + $jumlah) <= $this->kapasitas_maksimal;
    }

    // TAMBAH: Tambah limbah ke penyimpanan
    public function addLimbah($jumlah)
    {
        if (!$this->canAccommodate($jumlah)) {
            throw new \Exception('Kapasitas penyimpanan tidak mencukupi');
        }

        $this->increment('kapasitas_terpakai', $jumlah);
    }

    // TAMBAH: Kurangi limbah dari penyimpanan
    public function reduceLimbah($jumlah)
    {
        if ($this->kapasitas_terpakai < $jumlah) {
            throw new \Exception('Jumlah limbah tidak mencukupi');
        }

        $this->decrement('kapasitas_terpakai', $jumlah);
    }

    /**
     * Cek apakah penyimpanan aktif
     */
    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }

    /**
     * Scope untuk filter berdasarkan perusahaan
     */
    public function scopeByPerusahaan($query, $perusahaanId)
    {
        return $query->where('perusahaan_id', $perusahaanId);
    }

    /**
     * Scope untuk user tertentu (melalui perusahaan)
     */
    public function scopeByUser($query, $userId)
    {
        return $query->whereHas('perusahaan', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /**
     * Scope untuk penyimpanan aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama_penyimpanan', 'like', "%{$search}%")
                ->orWhere('lokasi', 'like', "%{$search}%")
                ->orWhere('jenis_penyimpanan', 'like', "%{$search}%");
        });
    }

    /**
     * Options untuk jenis penyimpanan
     */
    public static function getJenisPenyimpananOptions(): array
    {
        return [
            'tangki' => 'Tangki Penyimpanan',
            'drum' => 'Drum/Tong',
            'container' => 'Container',
            'gudang' => 'Gudang Khusus',
            'kolam' => 'Kolam Penampungan',
            'silo' => 'Silo',
            'lainnya' => 'Lainnya'
        ];
    }

    /**
     * Options untuk kondisi penyimpanan
     */
    public static function getKondisiOptions(): array
    {
        return [
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            'maintenance' => 'Dalam Maintenance'
        ];
    }




    /**
     * Options untuk status
     */
    public static function getStatusOptions(): array
    {
        return [
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif',
            'maintenance' => 'Maintenance'
        ];
    }

    /**
     * Validation rules
     */
    public static function validationRules($id = null): array
    {
        return [
            'nama_penyimpanan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'jenis_penyimpanan' => 'required|string|in:' . implode(',', array_keys(self::getJenisPenyimpananOptions())),
            'kapasitas_maksimal' => 'required|numeric|min:0',
            'kapasitas_terpakai' => 'nullable|numeric|min:0',
            'kondisi' => 'required|string|in:' . implode(',', array_keys(self::getKondisiOptions())),
            'tanggal_pembuatan' => 'required|date|before_or_equal:today',
            'catatan' => 'nullable|string',
            'status' => 'required|string|in:' . implode(',', array_keys(self::getStatusOptions()))
        ];
    }

    /**
     * Validation messages
     */
    public static function validationMessages(): array
    {
        return [
            'nama_penyimpanan.required' => 'Nama penyimpanan wajib diisi.',
            'lokasi.required' => 'Lokasi penyimpanan wajib diisi.',
            'jenis_penyimpanan.required' => 'Jenis penyimpanan wajib dipilih.',
            'kapasitas_maksimal.required' => 'Kapasitas maksimal wajib diisi.',
            'kapasitas_maksimal.numeric' => 'Kapasitas maksimal harus berupa angka.',
            'kapasitas_maksimal.min' => 'Kapasitas maksimal tidak boleh kurang dari 0.',
            'kapasitas_terpakai.numeric' => 'Kapasitas terpakai harus berupa angka.',
            'kapasitas_terpakai.min' => 'Kapasitas terpakai tidak boleh kurang dari 0.',

            'kondisi.required' => 'Kondisi penyimpanan wajib dipilih.',
            'tanggal_pembuatan.required' => 'Tanggal pembuatan wajib diisi.',
            'tanggal_pembuatan.date' => 'Format tanggal tidak valid.',
            'tanggal_pembuatan.before_or_equal' => 'Tanggal pembuatan tidak boleh lebih dari hari ini.',
            'status.required' => 'Status wajib dipilih.'
        ];
    }

    // Tambahkan relationship ke PengelolaanLimbah
    public function pengelolaanLimbahs()
    {
        return $this->hasMany(PengelolaanLimbah::class);
    }
}
