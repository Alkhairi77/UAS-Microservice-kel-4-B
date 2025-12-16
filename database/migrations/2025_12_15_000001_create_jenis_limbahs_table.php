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
        Schema::create('jenis_limbahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_limbah', 20)->unique();
            $table->string('nama_limbah', 100);
            $table->text('deskripsi')->nullable();
            $table->enum('kategori', ['B3', 'Non-B3', 'Organik', 'Anorganik']);
            $table->string('satuan_default', 20)->default('kg'); // kg, liter, m3, ton
            $table->decimal('batas_aman', 10, 2)->nullable()->comment('Batas aman dalam satuan default');
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
            $table->timestamps();

            // Indexes for better query performance
            $table->index('kode_limbah');
            $table->index('kategori');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_limbahs');
    }
};
