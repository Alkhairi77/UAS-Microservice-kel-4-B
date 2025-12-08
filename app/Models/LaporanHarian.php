<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanHarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'perusahaan_id',
        'jenis_limbah_id',
        'penyimpanan_id',
        'tanggal',
        'jumlah',
        'satuan',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    // Relationships
    public function perusahaan()
    {
        return $this->belongsTo(\App\Models\Perusahaan::class);
    }

    public function jenisLimbah()
    {
        return $this->belongsTo(\App\Models\JenisLimbah::class);
    }

    public function penyimpanan()
    {
        return $this->belongsTo(\App\Models\Penyimpanan::class);
    }

    // Scopes
    public function scopeByPerusahaan($query, $perusahaanId)
    {
        return $query->where('perusahaan_id', $perusahaanId);
    }

    public function scopeByJenisLimbah($query, $jenisLimbahId)
    {
        return $query->where('jenis_limbah_id', $jenisLimbahId);
    }

    public function scopeByPenyimpanan($query, $penyimpananId)
    {
        return $query->where('penyimpanan_id', $penyimpananId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Tambahkan scope untuk dashboard
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tanggal', now()->month)
                     ->whereYear('tanggal', now()->year);
    }

    public function scopeLastMonths($query, $months = 6)
    {
        return $query->where('tanggal', '>=', now()->subMonths($months));
    }

    // Accessors
    public function getTanggalLaporanAttribute()
    {
        return $this->created_at;
    }

    public function getStatusNameAttribute()
    {
        return [
            'draft' => 'Draft',
            'submitted' => 'Disubmit'
        ][$this->status] ?? 'Unknown';
    }

    public function getStatusBadgeClassAttribute()
    {
        return [
            'draft' => 'gray',
            'submitted' => 'blue'
        ][$this->status] ?? 'gray';
    }

    // Methods
    public function canEdit()
    {
        return $this->status === 'draft';
    }

    public function canSubmit()
    {
        return $this->status === 'draft';
    }

    public function canDelete()
    {
        return $this->status === 'draft';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isSubmitted()
    {
        return $this->status === 'submitted';
    }

    // Submit method
    public function submit()
    {
        if (!$this->canSubmit()) {
            throw new \Exception('Laporan tidak dapat disubmit.');
        }

        $this->update(['status' => 'submitted']);
        
        // Update kapasitas penyimpanan
        $this->penyimpanan->addLimbah($this->jumlah);
    }
    // Static methods
    public static function getStatusOptions(): array
    {
        return [
            'draft' => 'Draft',
            'submitted' => 'Disubmit'
        ];
    }
    public static function validationRules($id = null): array
    {
        return [
            'tanggal' => 'required|date|before_or_equal:today',
            'jenis_limbah_id' => 'required|exists:jenis_limbahs,id',
            'penyimpanan_id' => 'required|exists:penyimpanans,id',
            'jumlah' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string|max:1000'
        ];
    }
    public static function validationMessages(): array
    {
        return [
            'tanggal.required' => 'Tanggal laporan wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'jenis_limbah_id.required' => 'Jenis limbah wajib dipilih.',
            'jenis_limbah_id.exists' => 'Jenis limbah tidak valid.',
            'penyimpanan_id.required' => 'Penyimpanan wajib dipilih.',
            'penyimpanan_id.exists' => 'Penyimpanan tidak valid.',
            'jumlah.required' => 'Jumlah limbah wajib diisi.',
            'jumlah.numeric' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal 0.01.',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter.'
        ];
    }
}