<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('artikels', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->longText('konten');
            $table->text('excerpt')->nullable();
            $table->string('gambar_utama')->nullable();
            $table->foreignId('kategori_artikel_id')->constrained('kategori_artikels')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('tanggal_publikasi')->nullable();
            $table->string('meta_title', 60)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->text('tags')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->integer('reading_time')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'tanggal_publikasi']);
            $table->index(['kategori_artikel_id', 'status']);
            $table->index('slug');
        });
    }

    public function down()
    {
        Schema::dropIfExists('artikels');
    }
};
