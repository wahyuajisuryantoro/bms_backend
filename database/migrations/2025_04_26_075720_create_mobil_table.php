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
        Schema::create('mobil', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mobil');
            $table->foreignId('merk_id')->constrained('merk_mobil')->onDelete('cascade');
            $table->foreignId('transmisi_id')->constrained('transmisi');
            $table->foreignId('tipe_bodi_id')->constrained('tipe_bodi');
            $table->foreignId('bahan_bakar_id')->constrained('bahan_bakar');
            $table->foreignId('kapasitas_mesin_id')->constrained('kapasitas_mesin');
            $table->integer('tahun_keluaran');
            $table->string('thumbnail_foto')->nullable(false); 
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 15, 2)->default(0);
            $table->boolean('tersedia')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobil');
    }
};
