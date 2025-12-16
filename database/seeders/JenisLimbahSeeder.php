<?php

namespace Database\Seeders;

use App\Models\JenisLimbah;
use Illuminate\Database\Seeder;

class JenisLimbahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisLimbahs = [
            [
                'kode_limbah' => 'B3-001',
                'nama_limbah' => 'Limbah Medis Infeksius',
                'deskripsi' => 'Limbah yang mengandung patogen berbahaya dari fasilitas kesehatan',
                'kategori' => 'B3',
                'satuan_default' => 'kg',
                'batas_aman' => 100.00,
                'status' => 'aktif',
            ],
            [
                'kode_limbah' => 'B3-002',
                'nama_limbah' => 'Limbah Kimia Beracun',
                'deskripsi' => 'Limbah mengandung bahan kimia berbahaya dan beracun',
                'kategori' => 'B3',
                'satuan_default' => 'liter',
                'batas_aman' => 50.00,
                'status' => 'aktif',
            ],
            [
                'kode_limbah' => 'ORG-001',
                'nama_limbah' => 'Limbah Organik Rumah Tangga',
                'deskripsi' => 'Sisa makanan, dedaunan, dan limbah organik yang mudah terurai',
                'kategori' => 'Organik',
                'satuan_default' => 'kg',
                'batas_aman' => 500.00,
                'status' => 'aktif',
            ],
            [
                'kode_limbah' => 'ANORG-001',
                'nama_limbah' => 'Plastik dan Kemasan',
                'deskripsi' => 'Limbah plastik, botol, dan kemasan yang sulit terurai',
                'kategori' => 'Anorganik',
                'satuan_default' => 'kg',
                'batas_aman' => 300.00,
                'status' => 'aktif',
            ],
            [
                'kode_limbah' => 'NON-B3-001',
                'nama_limbah' => 'Limbah Kertas dan Karton',
                'deskripsi' => 'Limbah kertas, karton, dan material daur ulang',
                'kategori' => 'Non-B3',
                'satuan_default' => 'kg',
                'batas_aman' => 1000.00,
                'status' => 'aktif',
            ],
            [
                'kode_limbah' => 'B3-003',
                'nama_limbah' => 'Minyak Pelumas Bekas',
                'deskripsi' => 'Oli bekas dan minyak pelumas industri',
                'kategori' => 'B3',
                'satuan_default' => 'liter',
                'batas_aman' => 200.00,
                'status' => 'aktif',
            ],
        ];

        foreach ($jenisLimbahs as $limbah) {
            JenisLimbah::create($limbah);
        }
    }
}
