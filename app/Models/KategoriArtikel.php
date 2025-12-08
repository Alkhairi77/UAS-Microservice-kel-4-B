<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriArtikel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
        'warna',
        'icon',
        'status',
        'urutan'
    ];

    // Boot method untuk auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = self::generateSlug($model->nama_kategori);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('nama_kategori')) {
                $model->slug = self::generateSlug($model->nama_kategori, $model->id);
            }
        });
    }

    // Generate unique slug
    private static function generateSlug($nama, $id = null)
    {
        $slug = Str::slug($nama);
        $originalSlug = $slug;
        $counter = 1;

        while (self::where('slug', $slug)->when($id, function ($query, $id) {
            return $query->where('id', '!=', $id);
        })->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // Relationships
    public function artikels()
    {
        return $this->hasMany(Artikel::class);
    }

    public function publishedArtikels()
    {
        return $this->hasMany(Artikel::class)->published();
    }

    // Accessors
    public function getStatusNameAttribute()
    {
        return $this->status === 'aktif' ? 'Aktif' : 'Tidak Aktif';
    }

    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'aktif' ? 'green' : 'red';
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByUrutan($query)
    {
        return $query->orderBy('urutan', 'asc')->orderBy('nama_kategori', 'asc');
    }

    // Static methods
    public static function getStatusOptions(): array
    {
        return [
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif'
        ];
    }

    public static function getIconOptions(): array
    {
        return [
            'fas fa-leaf' => 'Daun (Lingkungan)',
            'fas fa-recycle' => 'Recycle',
            'fas fa-industry' => 'Industri',
            'fas fa-newspaper' => 'Berita',
            'fas fa-lightbulb' => 'Tips',
            'fas fa-book' => 'Panduan',
            'fas fa-chart-line' => 'Analisis',
            'fas fa-users' => 'Komunitas',
            'fas fa-globe' => 'Global',
            'fas fa-heart' => 'Kesehatan'
        ];
    }

    public static function validationRules($id = null): array
    {
        return [
            'nama_kategori' => 'required|string|max:255|unique:kategori_artikels,nama_kategori,' . $id,
            'deskripsi' => 'nullable|string|max:500',
            'warna' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:100',
            'status' => 'required|in:aktif,tidak_aktif',
            'urutan' => 'required|integer|min:0'
        ];
    }

    public static function validationMessages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.',
            'warna.required' => 'Warna kategori wajib dipilih.',
            'warna.regex' => 'Format warna harus berupa kode hex (contoh: #3B82F6).',
            'status.required' => 'Status wajib dipilih.',
            'urutan.required' => 'Urutan wajib diisi.',
            'urutan.integer' => 'Urutan harus berupa angka.',
            'urutan.min' => 'Urutan minimal 0.'
        ];
    }
}
