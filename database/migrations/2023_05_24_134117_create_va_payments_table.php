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
        Schema::create('va_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payment_id');
            $table->date('date');
            $table->string('original_request_id');
            $table->string('transaksi_id');
            $table->string('terminal_id');
            $table->integer('invoice_number');
            $table->integer('amount');
            $table->string('virtual_account_number');
            $table->enum('status', ['menunggu', 'sudah_setor', 'belum_setor', 'ditolak']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('va_payments');
    }
};
