<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->string('nama_pic');
            $table->string('email')->unique();
            $table->string('telepon', 20);
            $table->text('alamat');
            $table->enum('jenis_layanan', [
                'pengumpulan',
                'pengangkutan', 
                'pengolahan',
                'pemusnahan',
                'daur_ulang',
                'full_service'
            ]);
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status']);
            $table->index(['jenis_layanan']);
            $table->index(['email']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendors');
    }
};
