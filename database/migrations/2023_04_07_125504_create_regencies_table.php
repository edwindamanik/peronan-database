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
        Schema::create('regencies', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dinas');
            $table->string('logo');
            $table->string('perda', 50);
            $table->string('kepala_dinas', 25);
            $table->string('provinsi', 25);
            $table->string('kabupaten', 50);
            $table->string('email_dinas');
            $table->string('no_telp_dinas', 20);
            $table->string('upload_perda');
            $table->text('base_image')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'ditolak'])->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regencies');
    }
};
