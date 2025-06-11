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
      Schema::create('opsi_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mobil_id');
            $table->decimal('harga', 15, 2);
            $table->boolean('is_active')->default(true); 
            $table->boolean('is_kredit')->default(false);
            $table->unsignedBigInteger('konfigurasi_kredit_id')->nullable();
            $table->timestamps();
            $table->foreign('mobil_id')->references('id')->on('mobil')->onDelete('cascade');
            $table->foreign('konfigurasi_kredit_id')->references('id')->on('konfigurasi_kredit')->onDelete('set null');
            $table->unique('mobil_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opsi_pembayaran');
    }
};
