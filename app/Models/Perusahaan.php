<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = 'perusahaans';

    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'alamat',
        'no_telp',
        'jenis_usaha',
        'no_registrasi',
        'deskripsi',
        'logo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class);
    }

    public function penyimpanan()
    {
        return $this->hasMany(Penyimpanan::class);
    }
} 