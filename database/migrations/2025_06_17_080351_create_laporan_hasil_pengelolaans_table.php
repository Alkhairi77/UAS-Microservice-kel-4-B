<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_hasil_pengelolaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->onDelete('cascade');
            $table->foreignId('pengelolaan_limbah_id')->constrained('pengelolaan_limbahs')->onDelete('cascade');
            
            // Informasi Hasil
            $table->date('tanggal_selesai');
            $table->enum('status_hasil', ['berhasil', 'gagal', 'partial'])->default('berhasil');
            $table->decimal('jumlah_berhasil_dikelola', 10, 2);
            $table->decimal('jumlah_residu', 10, 2)->default(0);
            $table->string('satuan', 20);
            
            // Detail Hasil
            $table->string('metode_disposal_akhir')->nullable();
            $table->decimal('biaya_aktual', 12, 2)->nullable();
            $table->decimal('efisiensi_pengelolaan', 5, 2)->nullable(); // Persentase
            
            // Dokumentasi
            $table->json('dokumentasi')->nullable(); // File paths
            $table->string('nomor_sertifikat')->nullable();
            $table->text('catatan_hasil')->nullable();
            
            // Soft delete timestamp
            $table->softDeletes(); // For soft deletes
            $table->timestamps();
            
            // Indexes
            $table->index(['perusahaan_id', 'status_hasil']);
            $table->index(['tanggal_selesai']);
            $table->unique(['pengelolaan_limbah_id']); // Satu laporan per pengelolaan
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_hasil_pengelolaans');
    }
};
