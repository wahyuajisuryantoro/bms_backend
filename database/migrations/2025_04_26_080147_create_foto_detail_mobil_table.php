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
        Schema::create('foto_detail_mobil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobil_id')->constrained('mobil')->onDelete('cascade');
            $table->string('foto_path');
            $table->enum('jenis_foto', ['eksterior', 'interior', 'mesin', 'dashboard', 'lainnya'])->default('lainnya');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_detail_mobil');
    }
};
