<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSampah extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kategori',
        'harga_per_kg',
        'deskripsi',
        'status'
    ];

    const KATEGORI = [
        'organik'      => 'Organik',
        'anorganik'    => 'Anorganik',
        'b3'           => 'B3 (Berbahaya)',
        'plastik'      => 'Plastik',
        'kertas'       => 'Kertas',
        'kaca'         => 'Kaca',
        'elektronik'   => 'Elektronik',
        'logam'        => 'Logam',
        'textile'      => 'Textile / Kain',
        'lainnya'      => 'Lainnya',
    ];

    public static function getKategoriOptions()
    {
        return self::KATEGORI;
    }
}
