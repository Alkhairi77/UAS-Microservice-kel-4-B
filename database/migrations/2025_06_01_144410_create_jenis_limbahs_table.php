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
            $table->enum('kategori', [
                'hazardous', 
                'non_hazardous', 
                'organic', 
                'inorganic', 
                'recyclable', 
                'electronic', 
                'medical', 
                'radioactive'
            ]);
            $table->string('kode_limbah', 20)->unique();
            $table->enum('satuan_default', ['kg', 'liter', 'ton', 'm3', 'unit'])->default('kg');
            $table->enum('tingkat_bahaya', ['rendah', 'sedang', 'tinggi', 'sangat_tinggi'])->nullable();
            $table->json('metode_pengelolaan_rekomendasi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_limbahs');
    }
};
