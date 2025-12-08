<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengelolaanLimbah extends Model
{
    use HasFactory;

    protected $fillable = [
        'perusahaan_id',
        'jenis_limbah_id',
        'penyimpanan_id',
        'vendor_id',
        'jumlah_dikelola',
        'satuan',
        'jenis_pengelolaan',    // Siapa yang mengelola
        'metode_pengelolaan',   // Bagaimana cara mengelola
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'biaya',
        'nomor_manifest',
        'catatan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'jumlah_dikelola' => 'decimal:2',
        'biaya' => 'decimal:2',
    ];

    // Boot method untuk debugging
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            \Log::info('Creating PengelolaanLimbah', $model->toArray());
        });

        static::created(function ($model) {
            \Log::info('Created PengelolaanLimbah', ['id' => $model->id]);
        });
    }

    // Relationships
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function jenisLimbah()
    {
        return $this->belongsTo(JenisLimbah::class);
    }

    public function penyimpanan()
    {
        return $this->belongsTo(Penyimpanan::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function laporanHarian()
    {
        return $this->belongsTo(LaporanHarian::class);
    }

    public function laporanHasilPengelolaan()
    {
        return $this->hasOne(\App\Models\LaporanHasilPengelolaan::class, 'pengelolaan_limbah_id');
    }

    public function hasLaporanHasil()
    {
        return $this->laporanHasil()->exists();
    }

    // Tambahkan method ini
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }


    public function canCreateLaporanHasil()
    {
        return $this->status === 'selesai' && !$this->hasLaporanHasil();
    }

    // Accessors
    public function getStatusNameAttribute()
    {
        return self::getStatusOptions()[$this->status] ?? 'Unknown';
    }

    public function getStatusBadgeClassAttribute()
    {
        return [
            'diproses' => 'yellow',
            'dalam_pengangkutan' => 'blue',
            'selesai' => 'green',
            'dibatalkan' => 'red'
        ][$this->status] ?? 'gray';
    }

    public function getJenisPengelolaanNameAttribute()
    {
        return self::getJenisPengelolaanOptions()[$this->jenis_pengelolaan] ?? 'Unknown';
    }

    public function getMetodePengelolaanNameAttribute()
    {
        return self::getMetodePengelolaanOptions()[$this->metode_pengelolaan] ?? 'Unknown';
    }

    // // Alias untuk tanggal_mulai (untuk kompatibilitas dengan dashboard)
    // public function getTanggalMulaiAttribute()
    // {
    //     return $this->tanggal_mulai;
    // }

    // Methods
    public function canEdit()
    {
        return in_array($this->status, ['diproses', 'dalam_pengangkutan']);
    }

    public function canCancel()
    {
        return in_array($this->status, ['diproses', 'dalam_pengangkutan']);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'selesai',
            'tanggal_selesai' => now()
        ]);
    }

    // Tambahkan scope untuk dashboard
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['diproses', 'dalam_pengangkutan']);
    }

    public function scopeOverdue($query, $days = 30)
    {
        return $query->where('status', '!=', 'selesai')
            ->where('tanggal_mulai', '<', now()->subDays($days));
    }
    // Static methods
    public static function getJenisPengelolaanOptions(): array
    {
        return [
            'internal' => 'Pengelolaan Internal',
            'vendor_eksternal' => 'Vendor Eksternal',
        ];
    }
    public static function getMetodePengelolaanOptions(): array
    {
        return [
            'reduce' => 'Reduce (Pengurangan)',
            'reuse' => 'Reuse (Penggunaan Kembali)',
            'recycle' => 'Recycle (Daur Ulang)',
            'recovery' => 'Recovery (Pemulihan)',
            'treatment' => 'Treatment (Pengolahan)',
            'disposal' => 'Disposal (Pembuangan)',
            'incineration' => 'Incineration (Pembakaran)',
            'landfill' => 'Landfill (Penimbunan)',
            'composting' => 'Composting (Pengomposan)',
            'stabilization' => 'Stabilization (Stabilisasi)',
        ];
    }
    public static function getStatusOptions(): array
    {
        return [
            'diproses' => 'Sedang Diproses',
            'dalam_pengangkutan' => 'Dalam Pengangkutan',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];
    }

    public static function validationRules($id = null): array
    {
        return [
            'jenis_limbah_id' => 'required|exists:jenis_limbahs,id',
            'penyimpanan_id' => 'required|exists:penyimpanans,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'jumlah_dikelola' => 'required|numeric|min:0.01',
            'jenis_pengelolaan' => 'required|in:' . implode(',', array_keys(self::getJenisPengelolaanOptions())),
            'metode_pengelolaan' => 'required|in:' . implode(',', array_keys(self::getMetodePengelolaanOptions())),
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'biaya' => 'nullable|numeric|min:0',
            'nomor_manifest' => 'nullable|string|max:100',
            'catatan' => 'nullable|string|max:1000'
        ];
    }

    public static function validationMessages(): array
    {
        return [
            'jenis_limbah_id.required' => 'Jenis limbah wajib dipilih.',
            'penyimpanan_id.required' => 'Penyimpanan wajib dipilih.',
            'jumlah_dikelola.required' => 'Jumlah yang dikelola wajib diisi.',
            'jumlah_dikelola.min' => 'Jumlah minimal 0.01.',
            'jenis_pengelolaan.required' => 'Jenis pengelolaan wajib dipilih.',
            'metode_pengelolaan.required' => 'Metode pengelolaan wajib dipilih.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.'
        ];
    }
}
