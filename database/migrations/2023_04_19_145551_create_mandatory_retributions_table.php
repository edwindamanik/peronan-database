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
        Schema::create('mandatory_retributions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('no_tagihan');
            $table->bigInteger('no_tagihan_ref')->nullable();
            // $table->char('durasi_pembayaran');
            $table->bigInteger('biaya_retribusi');
            $table->bigInteger('total_retribusi')->nullable();
            $table->date('tanggal_pembayaran')->nullable();
            $table->date('jatuh_tempo');
            $table->enum('metode_pembayaran', ['cash', 'virtual_account', 'qris'])->nullable();
            $table->enum('status_pembayaran', ['sudah_dibayar', 'belum_dibayar']);
            $table->char('url_pembayaran_va')->nullable();
            $table->char('url_pembayaran_qris')->nullable();
            $table->unsignedBigInteger('petugas_id')->nullable();
            $table->unsignedBigInteger('contract_id');
            $table->foreign('petugas_id')->references('id')->on('market_officers')->onDelete('cascade');
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mandatory_retributions');
    }
};
