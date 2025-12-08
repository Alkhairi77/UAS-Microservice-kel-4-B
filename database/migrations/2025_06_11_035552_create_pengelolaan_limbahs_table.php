<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengelolaan_limbahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->onDelete('cascade');
            $table->foreignId('jenis_limbah_id')->constrained('jenis_limbahs')->onDelete('cascade');
            $table->foreignId('penyimpanan_id')->constrained('penyimpanans')->onDelete('cascade');
            $table->date('tanggal_sampai')->nullable();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('set null');
            
            $table->date('tanggal_mulai');
            $table->decimal('jumlah_dikelola', 10, 2);
            $table->string('satuan', 20);
            
            // Jenis pengelolaan: internal, vendor_eksternal, disposal, recycling
            $table->enum('jenis_pengelolaan', ['internal', 'vendor_eksternal', 'disposal', 'recycling']);
            
            // Metode pengelolaan: daur_ulang, pengolahan_internal, vendor_eksternal, disposal, treatment
            $table->enum('metode_pengelolaan', ['daur_ulang', 'pengolahan_internal', 'vendor_eksternal', 'disposal', 'treatment']);
            
            // Status: draft, dalam_proses, selesai, dibatalkan
            $table->enum('status', ['draft', 'dalam_proses', 'selesai', 'dibatalkan'])->default('draft');
            
            $table->decimal('biaya', 12, 2)->nullable();
            $table->string('nomor_manifest')->nullable();
            $table->text('catatan')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['perusahaan_id', 'status']);
            $table->index(['tanggal_mulai']);
            $table->index(['jenis_pengelolaan']);
            $table->index(['jenis_limbah_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengelolaan_limbahs');
    }
};
