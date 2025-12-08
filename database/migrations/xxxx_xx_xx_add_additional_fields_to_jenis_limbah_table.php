<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jenis_limbahs', function (Blueprint $table) {
            // Tambahkan kolom jika belum ada
            if (!Schema::hasColumn('jenis_limbahs', 'kode_limbah')) {
                $table->string('kode_limbah')->unique()->after('id');
            }
            
            if (!Schema::hasColumn('jenis_limbahs', 'satuan_default')) {
                $table->string('satuan_default')->default('kg')->after('kategori');
            }
            
            if (!Schema::hasColumn('jenis_limbahs', 'tingkat_bahaya')) {
                $table->enum('tingkat_bahaya', ['rendah', 'sedang', 'tinggi', 'sangat_tinggi'])->nullable()->after('satuan_default');
            }
            
            if (!Schema::hasColumn('jenis_limbahs', 'metode_pengelolaan_rekomendasi')) {
                $table->json('metode_pengelolaan_rekomendasi')->nullable()->after('tingkat_bahaya');
            }
            
            if (!Schema::hasColumn('jenis_limbahs', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('deskripsi');
            }
            
            // Update kategori enum jika diperlukan
            $table->enum('kategori', ['hazardous', 'non_hazardous', 'recyclable', 'organic', 'electronic', 'medical'])->change();
            
            // Tambahkan soft deletes jika belum ada
            if (!Schema::hasColumn('jenis_limbahs', 'deleted_at')) {
                $table->softDeletes();
            }
            
            // Tambahkan index untuk performa
            $table->index(['kategori', 'status']);
            $table->index(['tingkat_bahaya']);
            $table->index(['status']);
        });
    }

    public function down()
    {
        Schema::table('jenis_limbahs', function (Blueprint $table) {
            $table->dropIndex(['kategori', 'status']);
            $table->dropIndex(['tingkat_bahaya']);
            $table->dropIndex(['status']);
            
            $table->dropColumn([
                'kode_limbah',
                'satuan_default', 
                'tingkat_bahaya',
                'metode_pengelolaan_rekomendasi',
                'status',
                'deleted_at'
            ]);
        });
    }
};