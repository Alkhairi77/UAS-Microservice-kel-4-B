<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus perusahaan_id dari users jika ada
        if (Schema::hasColumn('users', 'perusahaan_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['perusahaan_id']);
                $table->dropColumn('perusahaan_id');
            });
        }

        // Pastikan perusahaans memiliki user_id
        if (!Schema::hasColumn('perusahaans', 'user_id')) {
            Schema::table('perusahaans', function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
            });
        }

        // Update penyimpanans untuk menggunakan user_id melalui perusahaan
        // Atau tetap gunakan perusahaan_id tapi pastikan konsisten
    }

    public function down(): void
    {
        // Rollback logic
    }
};
