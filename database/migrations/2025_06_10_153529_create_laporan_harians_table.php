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
    Schema::create('laporan_harians', function (Blueprint $table) {
        $table->id();
        $table->foreignId('perusahaan_id')->constrained('perusahaans')->onDelete('cascade');
        $table->date('tanggal');
        $table->timestamp('tanggal_laporan');
        $table->foreignId('jenis_limbah_id')->constrained('jenis_limbahs')->onDelete('cascade');
        $table->foreignId('penyimpanan_id')->constrained('penyimpanans')->onDelete('cascade');
        $table->float('jumlah');
        $table->string('satuan', 20)->nullable();
        $table->enum('status', ['draft', 'submitted'])->default('draft');
        $table->text('keterangan')->nullable();
        $table->timestamps();

        // Index
        $table->index(['perusahaan_id', 'tanggal_laporan']);
        $table->index(['jenis_limbah_id', 'tanggal_laporan']);
        $table->index(['penyimpanan_id']);
        $table->index(['status']);
    });

    // Update satuan untuk data existing (jika ada)
    // Menggunakan syntax yang kompatibel dengan SQLite dan MySQL
    if (Schema::hasTable('laporan_harians') && Schema::hasTable('jenis_limbahs')) {
        // Untuk MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                UPDATE laporan_harians lh 
                JOIN jenis_limbahs jl ON lh.jenis_limbah_id = jl.id 
                SET lh.satuan = jl.satuan_default 
                WHERE lh.satuan IS NULL OR lh.satuan = ''
            ");
        }
        // Untuk SQLite (untuk testing)
        else {
            DB::statement("
                UPDATE laporan_harians 
                SET satuan = (
                    SELECT satuan_default 
                    FROM jenis_limbahs 
                    WHERE jenis_limbahs.id = laporan_harians.jenis_limbah_id
                )
                WHERE satuan IS NULL OR satuan = ''
            ");
        }
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_harians');
    }
};
