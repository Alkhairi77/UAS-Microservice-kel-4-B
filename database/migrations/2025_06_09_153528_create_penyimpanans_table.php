<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penyimpanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->onDelete('cascade');
            $table->foreignId('jenis_limbah_id')->constrained('jenis_limbahs')->onDelete('restrict');
            $table->string('nama_penyimpanan');
            $table->string('lokasi');
            $table->string('jenis_penyimpanan');
            $table->decimal('kapasitas_maksimal', 10, 2);
            $table->decimal('kapasitas_terpakai', 10, 2)->default(0);
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'maintenance'])->default('baik');
            $table->date('tanggal_pembuatan');
            $table->enum('status', ['aktif', 'tidak_aktif', 'maintenance'])->default('aktif');
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            // Indexes untuk performa
            $table->index(['perusahaan_id', 'status']);
            $table->index(['jenis_limbah_id']);
            $table->index(['jenis_penyimpanan']);
            $table->index(['kondisi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyimpanans');
    }
};