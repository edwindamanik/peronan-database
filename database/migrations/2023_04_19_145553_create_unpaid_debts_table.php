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
        Schema::create('unpaid_debts', function (Blueprint $table) {
            $table->id();
            $table->integer('lama_menunggak');
            $table->bigInteger('denda');
            $table->bigInteger('total_tagihan');
            $table->unsignedBigInteger('retribusi_wajib_id');
            $table->foreign('retribusi_wajib_id')->references('id')->on('mandatory_retributions')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unpaid_debts');
    }
};
