<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jenis_limbahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            // Tambah kolom kategori
            $table->enum('kategori', [
                'hazardous', // B3/Berbahaya
                'non_hazardous', // Non-B3/Tidak Berbahaya
                'organic', // Organik
                'inorganic', // Anorganik
                'recyclable', // Dapat Didaur Ulang
                'electronic', // Elektronik
                'medical', // Medis
                'radioactive' // Radioaktif
            ]);
            
            // Tambah kode limbah untuk referensi
            $table->string('kode_limbah', 20)->unique();
            
            // Tambah satuan default
            $table->enum('satuan_default', ['kg', 'liter', 'ton', 'm3', 'unit'])->default('kg');
            
            // Tambah tingkat bahaya
            $table->enum('tingkat_bahaya', ['rendah', 'sedang', 'tinggi', 'sangat_tinggi'])->nullable();
            
            // Tambah metode pengelolaan yang direkomendasikan
            $table->json('metode_pengelolaan_rekomendasi')->nullable();

            // Tambah deskripsi
            $table->text('deskripsi')->nullable();
        
            // Tambah status aktif/tidak aktif
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('jenis_limbahs', function (Blueprint $table) {
            $table->dropColumn([
                'nama',
                'kategori',
                'kode_limbah', 
                'satuan_default',
                'tingkat_bahaya',
                'metode_pengelolaan_rekomendasi',
                'deskripsi',
                'status',
                
            ]);
        });
    }
};
