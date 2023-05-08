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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('no_unit', 25);
            $table->string('blok', 25);
            $table->text('deskripsi_lokasi');
            $table->unsignedBigInteger('pasar_id');
            $table->unsignedBigInteger('jenis_unit_id');
            $table->foreign('pasar_id')->references('id')->on('markets')->onDelete('cascade');
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
        Schema::dropIfExists('units');
    }
};
