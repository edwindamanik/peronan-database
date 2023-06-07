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
        Schema::create('daily_retributions', function (Blueprint $table) {
            $table->id();
            $table->string('no_bukti_pembayaran');
            $table->bigInteger('biaya_retribusi');
            $table->date('tanggal');
            $table->string('bukti_pembatalan', 100)->nullable();
            $table->string('bukti_pembayaran', 100)->nullable();
            $table->enum('status', ['0', '1', '2', '3']);
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('pasar_id');
            $table->unsignedBigInteger('deposit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('pasar_id')->references('id')->on('markets')->onDelete('cascade');
            $table->foreign('deposit_id')->references('id')->on('deposits')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_retributions');
    }
};
