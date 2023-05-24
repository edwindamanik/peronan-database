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
        Schema::create('qris_payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->date('date');
            $table->string('acquirer_name');
            $table->string('merchant_pan');
            $table->string('issuer_name');
            $table->integer('issuer_id');
            $table->integer('convenience_fee');
            $table->string('transaksi_id');
            $table->string('terminal_id');
            $table->bigInteger('amount');
            $table->string('words');
            $table->string('origin');
            $table->string('reference_id');
            $table->enum('status', ['menunggu', 'sudah_setor', 'belum_setor', 'ditolak']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qris_payments');
    }
};
