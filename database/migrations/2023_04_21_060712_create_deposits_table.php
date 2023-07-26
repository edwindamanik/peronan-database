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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('jumlah_setoran');
            $table->enum('penyetoran_melalui', ['transfer_bank', 'langsung', 'VA', 'QRIS'])->nullable();
            $table->date('tanggal_penyetoran');
            $table->date('tanggal_disetor')->nullable();
            $table->string('bukti_setoran')->nullable();
            $table->enum('status', ['menunggu_konfirmasi', 'disetujui', 'belum_setor', 'ditolak']);
            $table->string('alasan_tidak_setor')->nullable();
            $table->unsignedBigInteger('users_id')->nullable();
            $table->unsignedBigInteger('wajib_retribusi_id')->nullable();
            $table->unsignedBigInteger('pasar_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('wajib_retribusi_id')->references('id')->on('obligation_retributions')->onDelete('cascade');
            $table->foreign('pasar_id')->references('id')->on('markets')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
