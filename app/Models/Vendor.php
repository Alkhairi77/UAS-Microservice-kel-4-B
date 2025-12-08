<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_perusahaan',
        'nama_pic',
        'email',
        'telepon',
        'alamat',
        'jenis_layanan',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $dates = ['deleted_at'];

    // Tambahkan relationship ke PengelolaanLimbah
    public function pengelolaanLimbahs()
    {
        return $this->hasMany(PengelolaanLimbah::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByJenisLayanan($query, $jenisLayanan)
    {
        return $query->where('jenis_layanan', $jenisLayanan);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama_perusahaan', 'like', "%{$search}%")
              ->orWhere('nama_pic', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('jenis_layanan', 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getJenisLayananNameAttribute()
    {
        return self::getJenisLayananOptions()[$this->jenis_layanan] ?? $this->jenis_layanan;
    }

    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'aktif' ? 'green' : 'red';
    }

    // Helper Methods
    public function isActive()
    {
        return $this->status === 'aktif';
    }

    // Static Methods untuk Options
    public static function getJenisLayananOptions()
    {
        return [
            'pengumpulan' => 'Pengumpulan Limbah',
            'pengangkutan' => 'Pengangkutan Limbah', 
            'pengolahan' => 'Pengolahan Limbah',
            'pemusnahan' => 'Pemusnahan Limbah',
            'daur_ulang' => 'Daur Ulang',
            'full_service' => 'Full Service'
        ];
    }

    public static function getStatusOptions()
    {
        return [
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif'
        ];
    }

    // Validation Rules
    public static function validationRules($id = null)
    {
        return [
            'nama_perusahaan' => 'required|string|max:255|unique:vendors,nama_perusahaan,' . $id,
            'nama_pic' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email,' . $id,
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string|max:500',
            'jenis_layanan' => 'required|in:' . implode(',', array_keys(self::getJenisLayananOptions())),
            'status' => 'required|in:aktif,tidak_aktif'
        ];
    }

    public static function validationMessages()
    {
        return [
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi.',
            'nama_pic.required' => 'Nama PIC wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan vendor lain.',
            'telepon.required' => 'Nomor telepon wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'jenis_layanan.required' => 'Jenis layanan wajib dipilih.',
            'status.required' => 'Status wajib dipilih.'
        ];
    }
}
