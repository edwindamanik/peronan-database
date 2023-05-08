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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat', 100);
            $table->date('tanggal_kontrak')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['benar', 'kurang_benar', 'menunggu']);
            $table->string('file_pdf');
            $table->text('feedback')->nullable();
            $table->unsignedBigInteger('wajib_retribusi_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('pengaturan_id');
            $table->foreign('wajib_retribusi_id')->references('id')->on('obligation_retributions')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('pengaturan_id')->references('id')->on('letter_settings')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
