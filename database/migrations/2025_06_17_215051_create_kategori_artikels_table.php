<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori_artikels', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('warna', 7)->default('#3B82F6'); // Hex color
            $table->string('icon')->nullable(); // Icon class atau nama file
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->integer('urutan')->default(0);
            $table->timestamps();
            
            $table->index(['status', 'urutan']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_artikels');
    }
};
