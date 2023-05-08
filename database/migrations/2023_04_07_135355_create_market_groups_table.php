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
        Schema::create('market_groups', function (Blueprint $table) {
            $table->id();
            $table->string('kelompok_pasar', 10);
            $table->unsignedBigInteger('kabupaten_id');
            $table->foreign('kabupaten_id')->references('id')->on('regencies')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_groups');
    }
};
