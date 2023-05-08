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
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->char('kode_pasar', 2);
            $table->string('nama_pasar', 50);
            $table->string('alamat');
            $table->year('tahun_berdiri');
            $table->year('tahun_pembangunan');
            $table->string('koordinat');
            $table->string('kondisi_pasar');
            $table->bigInteger('luas_lahan');
            $table->string('pengelola', 20);
            $table->string('operasional_pasar');
            $table->bigInteger('jumlah_pedagang');
            $table->bigInteger('omzet_perbulan');
            // $table->unsignedBigInteger('kabupaten_id');
            $table->unsignedBigInteger('kelompok_pasar_id');
            // $table->foreign('kabupaten_id')->references('id')->on('regencies')->onDelete('cascade');
            $table->foreign('kelompok_pasar_id')->references('id')->on('market_groups')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};
