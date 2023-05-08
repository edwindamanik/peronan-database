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
        Schema::create('menu_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu');
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
        Schema::dropIfExists('menu_settings');
    }
};
