<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisLimbah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jenis_limbahs';

    protected $fillable = [
        'nama',
        'kode_limbah',
        'kategori',
        'satuan_default',
        'tingkat_bahaya',
        'metode_pengelolaan_rekomendasi',
        'deskripsi',
        'status'
    ];

    protected $casts = [
        'metode_pengelolaan_rekomendasi' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Constants untuk dropdown options
    const KATEGORI = [
        'hazardous' => 'Limbah Berbahaya (B3)',
        'non_hazardous' => 'Limbah Non-B3',
        'recyclable' => 'Limbah Daur Ulang',
        'organic' => 'Limbah Organik',
        'electronic' => 'Limbah Elektronik',
        'medical' => 'Limbah Medis',
    ];

    const SATUAN = [
        'kg' => 'Kilogram (kg)',
        'ton' => 'Ton',
        'liter' => 'Liter (L)',
        'm3' => 'Meter Kubik (m³)',
        'unit' => 'Unit/Buah',
        'drum' => 'Drum',
        'karung' => 'Karung',
        'kontainer' => 'Kontainer',
    ];

    const TINGKAT_BAHAYA = [
        'rendah' => 'Rendah',
        'sedang' => 'Sedang',
        'tinggi' => 'Tinggi',
        'sangat_tinggi' => 'Sangat Tinggi',
    ];

    const METODE_PENGELOLAAN = [
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeByTingkatBahaya($query, $tingkatBahaya)
    {
        return $query->where('tingkat_bahaya', $tingkatBahaya);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('kode_limbah', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getKategoriNameAttribute()
    {
        return self::KATEGORI[$this->kategori] ?? $this->kategori;
    }

    public function getSatuanNameAttribute()
    {
        return self::SATUAN[$this->satuan_default] ?? $this->satuan_default;
    }

    public function getTingkatBahayaNameAttribute()
    {
        return $this->tingkat_bahaya ? (self::TINGKAT_BAHAYA[$this->tingkat_bahaya] ?? $this->tingkat_bahaya) : null;
    }

    public function getMetodePengelolaanNamesAttribute()
    {
        if (!$this->metode_pengelolaan_rekomendasi) {
            return [];
        }

        return collect($this->metode_pengelolaan_rekomendasi)
            ->map(function($metode) {
                return self::METODE_PENGELOLAAN[$metode] ?? $metode;
            })
            ->toArray();
    }

    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'active' ? 'green' : 'red';
    }

    public function getKategoriBadgeClassAttribute()
    {
        $classes = [
            'hazardous' => 'red',
            'non_hazardous' => 'blue',
            'recyclable' => 'green',
            'organic' => 'yellow',
            'electronic' => 'purple',
            'medical' => 'pink',
        ];

        return $classes[$this->kategori] ?? 'gray';
    }

    public function getTingkatBahayaBadgeClassAttribute()
    {
        $classes = [
            'rendah' => 'green',
            'sedang' => 'yellow',
            'tinggi' => 'orange',
            'sangat_tinggi' => 'red',
        ];

        return $classes[$this->tingkat_bahaya] ?? 'gray';
    }

    // Relationships
    public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class, 'jenis_limbah_id');
    }

    public function penyimpanans()
    {
        return $this->hasMany(Penyimpanan::class);
    }

    public function pengelolaanLimbahs()
    {
        return $this->hasMany(PengelolaanLimbah::class);
    }

    
    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isHazardous()
    {
        return in_array($this->kategori, ['hazardous', 'medical']);
    }

    public function requiresSpecialHandling()
    {
        return $this->isHazardous() || in_array($this->tingkat_bahaya, ['tinggi', 'sangat_tinggi']);
    }

    public function getRecommendedMethods()
    {
        return $this->metode_pengelolaan_rekomendasi ?? [];
    }

    // Static methods
    public static function getKategoriOptions()
    {
        return self::KATEGORI;
    }

    public static function getSatuanOptions()
    {
        return self::SATUAN;
    }

    public static function getTingkatBahayaOptions()
    {
        return self::TINGKAT_BAHAYA;
    }

    public static function getMetodePengelolaanOptions()
    {
        return self::METODE_PENGELOLAAN;
    }

    // Boot method untuk auto-generate kode limbah jika kosong
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_limbah)) {
                $model->kode_limbah = self::generateKodeLimbah($model->kategori);
            }
        });
    }

    private static function generateKodeLimbah($kategori)
    {
        $prefix = [
            'hazardous' => 'B3',
            'non_hazardous' => 'NB3',
            'recyclable' => 'RCY',
            'organic' => 'ORG',
            'electronic' => 'ELK',
            'medical' => 'MED',
        ];

        $kodePrefiks = $prefix[$kategori] ?? 'LMB';
        $lastNumber = self::where('kode_limbah', 'like', $kodePrefiks . '%')
                         ->count() + 1;

        return $kodePrefiks . str_pad($lastNumber, 3, '0', STR_PAD_LEFT);
    }
    public static function validationRules($id = null)
    {
        return [
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:' . implode(',', array_keys(self::KATEGORI)),
            'kode_limbah' => 'required|string|max:20|unique:jenis_limbahs,kode_limbah,' . $id,
            'satuan_default' => 'required|in:' . implode(',', array_keys(self::SATUAN)),
            'tingkat_bahaya' => 'nullable|in:' . implode(',', array_keys(self::TINGKAT_BAHAYA)),
            'metode_pengelolaan_rekomendasi' => 'nullable|array',
            'metode_pengelolaan_rekomendasi.*' => 'in:' . implode(',', array_keys(self::METODE_PENGELOLAAN)),
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ];
    }
    public static function validationMessages()
    {
        return [
            'nama.required' => 'Nama jenis limbah harus diisi.',
            'kategori.required' => 'Kategori jenis limbah harus dipilih.',
            'kode_limbah.required' => 'Kode limbah harus diisi.',
            'kode_limbah.unique' => 'Kode limbah sudah digunakan.',
            'satuan_default.required' => 'Satuan default harus dipilih.',
            'tingkat_bahaya.in' => 'Tingkat bahaya harus sesuai dengan pilihan yang ada.',
            'metode_pengelolaan_rekomendasi.array' => 'Metode pengelolaan rekomendasi harus berupa array.',
            'status.required' => 'Status jenis limbah harus dipilih.'
        ];
    }
}
