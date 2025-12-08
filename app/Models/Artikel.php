<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Artikel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'judul',
        'slug',
        'excerpt',
        'konten',
        'gambar_utama',
        'kategori_artikel_id',
        'user_id',
        'status',
        'tanggal_publikasi',
        'meta_title',
        'meta_description',
        'views_count',
        'reading_time'
    ];

    protected $casts = [
        'tanggal_publikasi' => 'datetime',
        'tags' => 'array',
        'views_count' => 'integer',
        'reading_time' => 'integer'
    ];

    protected $dates = [
        'tanggal_publikasi',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Boot method untuk auto-generate slug dan reading time
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = self::generateSlug($model->judul);
            }
            
            if (empty($model->reading_time)) {
                $model->reading_time = self::calculateReadingTime($model->konten);
            }
        });
        static::updating(function ($model) {
            if ($model->isDirty('judul')) {
                $model->slug = self::generateSlug($model->judul, $model->id);
            }
            
            if ($model->isDirty('konten')) {
                $model->reading_time = self::calculateReadingTime($model->konten);
            }
        });
    }

    // Generate unique slug
    private static function generateSlug($judul, $id = null)
    {
        $slug = Str::slug($judul);
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

    // Calculate reading time
    private static function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        $readingTime = ceil($wordCount / 200); // Assuming 200 words per minute
        return max(1, $readingTime); // Minimum 1 minute
    }

    // Relationships
    public function kategoriArtikel()
    {
        return $this->belongsTo(KategoriArtikel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getStatusNameAttribute()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'draft' => 'yellow',
            'published' => 'green',
            'archived' => 'gray',
            default => 'gray'
        };
    }

    public function getTagsArrayAttribute()
    {
        return is_array($this->tags) ? $this->tags : [];
    }

    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        // Auto-generate excerpt from content
        return Str::limit(strip_tags($this->konten), 150);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('tanggal_publikasi', '<=', now());
    }

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_artikel_id', $kategoriId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%")
              ->orWhere('konten', 'like', "%{$search}%")
              ->orWhereJsonContains('tags', $search);
        });
    }

    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('views_count', 'desc')->limit($limit);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest('tanggal_publikasi')->limit($limit);
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function isPublished()
    {
        return $this->status === 'published' && 
               $this->tanggal_publikasi && 
               $this->tanggal_publikasi <= now();
    }

    public function canBeEditedBy(User $user)
    {
        return $user->isAdmin() || $this->user_id === $user->id;
    }

    // Static methods
    public static function getStatusOptions(): array
    {
        return [
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived'
        ];
    }

    public static function validationRules($id = null): array
    {
        return [
            'judul' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'konten' => 'required|string',
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori_artikel_id' => 'required|exists:kategori_artikels,id',
            'status' => 'required|in:draft,published,archived',
            'tanggal_publikasi' => 'nullable|date',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'tags' => 'nullable|string'
        ];
    }

    public static function validationMessages(): array
    {
        return [
            'judul.required' => 'Judul artikel wajib diisi.',
            'judul.max' => 'Judul artikel maksimal 255 karakter.',
            'excerpt.max' => 'Excerpt maksimal 500 karakter.',
            'konten.required' => 'Konten artikel wajib diisi.',
            'gambar_utama.image' => 'File harus berupa gambar.',
            'gambar_utama.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'gambar_utama.max' => 'Ukuran gambar maksimal 2MB.',
            'kategori_artikel_id.required' => 'Kategori artikel wajib dipilih.',
            'kategori_artikel_id.exists' => 'Kategori artikel tidak valid.',
            'status.required' => 'Status artikel wajib dipilih.',
            'status.in' => 'Status artikel tidak valid.',
            'tanggal_publikasi.date' => 'Format tanggal publikasi tidak valid.',
            'meta_title.max' => 'Meta title maksimal 60 karakter.',
            'meta_description.max' => 'Meta description maksimal 160 karakter.'
        ];
    }
}
