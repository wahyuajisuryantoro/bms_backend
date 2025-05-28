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
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn(['kelurahan', 'kota_kabupaten']);
            
            $table->char('province_id', 2)->nullable()->after('user_id');
            $table->char('regency_id', 4)->nullable()->after('province_id');
            $table->char('district_id', 7)->nullable()->after('regency_id');
            $table->char('village_id', 10)->nullable()->after('district_id');

            $table->foreign('province_id')
                ->references('id')
                ->on('provinces')
                ->onUpdate('cascade')
                ->onDelete('restrict');
                
            $table->foreign('regency_id')
                ->references('id')
                ->on('regencies')
                ->onUpdate('cascade')
                ->onDelete('restrict');
                
            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->onUpdate('cascade')
                ->onDelete('restrict');
                
            $table->foreign('village_id')
                ->references('id')
                ->on('villages')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            
            $table->text('alamat_lengkap')->nullable()->after('village_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['regency_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['village_id']);
            
            $table->dropColumn(['province_id', 'regency_id', 'district_id', 'village_id', 'alamat_lengkap']);
            
            $table->string('kelurahan')->nullable()->after('dusun');
            $table->string('kota_kabupaten')->nullable()->after('kelurahan');
        });
    }
};
