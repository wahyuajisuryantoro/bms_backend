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
            $table->foreignId('mobil_id')->constrained('mobil')->onDelete('cascade');
            $table->enum('metode', ['Cash', 'Kredit']);
            $table->integer('tenor')->nullable()->comment('Dalam bulan, NULL untuk metode Cash');
            $table->decimal('harga', 15, 2);
            $table->decimal('dp_minimal', 15, 2)->nullable();
            $table->decimal('angsuran_per_bulan', 15, 2)->nullable();
            $table->decimal('bunga', 5, 2)->nullable();
            $table->decimal('biaya_admin', 15, 2)->nullable();
            $table->decimal('biaya_asuransi', 15, 2)->nullable();
            $table->timestamps();
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
