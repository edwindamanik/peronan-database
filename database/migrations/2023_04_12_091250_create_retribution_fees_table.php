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
        Schema::create('retribution_fees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('harga_satuan');
            $table->char('satuan', 25);
            $table->bigInteger('harga');
            $table->unsignedBigInteger('kelompok_pasar');
            $table->unsignedBigInteger('jenis_unit_id');
            $table->foreign('kelompok_pasar')->references('id')->on('market_groups')->onDelete('cascade');
            $table->foreign('jenis_unit_id')->references('id')->on('unit_types')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retribution_fees');
    }
};
